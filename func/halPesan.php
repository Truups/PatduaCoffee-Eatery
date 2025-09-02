<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

// Ambil kategori dari URL
$category = isset($_GET['katg_Menu']) ? urldecode($_GET['katg_Menu']) : '';

// Class untuk Item-Based Collaborative Filtering
class ItemBasedCollaborativeFiltering
{
    private $db;
    private $ratings = [];
    private $items = [];

    public function __construct($database)
    {
        $this->db = $database;
        $this->loadRatings();
    }

    // Load semua rating dari database
    private function loadRatings()
    {
        $query = "SELECT id_Pelanggan, id_Menu, rating FROM rating";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) 
        {
            $this->ratings[$row['id_Pelanggan']][$row['id_Menu']] = $row['rating'];
            // Buat struktur item-user untuk similarity calculation
            $this->items[$row['id_Menu']][$row['id_Pelanggan']] = $row['rating'];
        }
    }

    // Hitung cosine similarity antar item (diperbaiki)
    private function cosineSimilarity($item1Ratings, $item2Ratings)
    {
        // Cari user yang memberi rating pada kedua item
        $commonUsers = array_intersect_key($item1Ratings, $item2Ratings);

        if (count($commonUsers) < 2) 
        {
            return 0; // Minimal 2 user untuk similarity yang valid
        }

        $dotProduct = 0;
        $norm1 = 0;
        $norm2 = 0;

        foreach ($commonUsers as $userId => $rating1) 
        {
            $rating2 = $item2Ratings[$userId];

            $dotProduct += $rating1 * $rating2;
            $norm1 += $rating1 * $rating1;
            $norm2 += $rating2 * $rating2;
        }

        if ($norm1 == 0 || $norm2 == 0) 
        {
            return 0;
        }

        return $dotProduct / (sqrt($norm1) * sqrt($norm2));
    }

    // Hitung adjusted cosine similarity (lebih akurat)
    private function adjustedCosineSimilarity($item1Ratings, $item2Ratings)
    {
        $commonUsers = array_intersect_key($item1Ratings, $item2Ratings);

        if (count($commonUsers) < 2) 
        {
            return 0;
        }

        // Hitung rata-rata rating per user
        $userAverages = [];
        foreach ($commonUsers as $userId => $rating) 
        {
            if (!isset($userAverages[$userId])) 
            {
                $userRatings = $this->ratings[$userId] ?? [];
                $userAverages[$userId] = count($userRatings) > 0 ? array_sum($userRatings) / count($userRatings) : 0;
            }
        }

        $numerator = 0;
        $sumSquares1 = 0;
        $sumSquares2 = 0;

        foreach ($commonUsers as $userId => $rating1) 
        {
            $rating2 = $item2Ratings[$userId];
            $userAvg = $userAverages[$userId];

            $diff1 = $rating1 - $userAvg;
            $diff2 = $rating2 - $userAvg;

            $numerator += $diff1 * $diff2;
            $sumSquares1 += $diff1 * $diff1;
            $sumSquares2 += $diff2 * $diff2;
        }

        if ($sumSquares1 == 0 || $sumSquares2 == 0) 
        {
            return 0;
        }

        return $numerator / (sqrt($sumSquares1) * sqrt($sumSquares2));
    }

    // Dapatkan rekomendasi untuk user
    public function getRecommendations($userId, $limit = 5, $method = 'adjusted')
    {
        if (!isset($this->ratings[$userId])) 
        {
            return [];
        }

        $userRatings = $this->ratings[$userId];
        $scores = [];

        // Untuk setiap item yang belum dirating user
        foreach ($this->items as $itemId => $itemRatings) 
        {
            if (isset($userRatings[$itemId])) 
            {
                continue; // Skip item yang sudah dirating
            }

            $weightedSum = 0;
            $similaritySum = 0;

            // Hitung prediksi berdasarkan item yang sudah dirating user
            foreach ($userRatings as $ratedItemId => $userRating) 
            {
                if (!isset($this->items[$ratedItemId])) 
                {
                    continue;
                }

                // Hitung similarity antara item target dan item yang sudah dirating
                if ($method === 'adjusted') 
                {
                    $similarity = $this->adjustedCosineSimilarity(
                        $this->items[$itemId],
                        $this->items[$ratedItemId]
                    );
                } else 
                {
                    $similarity = $this->cosineSimilarity(
                        $this->items[$itemId],
                        $this->items[$ratedItemId]
                    );
                }

                if ($similarity > 0) 
                {
                    $weightedSum += $similarity * $userRating;
                    $similaritySum += abs($similarity);
                }
            }

            // Hitung prediksi rating
            if ($similaritySum > 0) 
            {
                $scores[$itemId] = $weightedSum / $similaritySum;
            }
        }

        // Sort berdasarkan score tertinggi
        arsort($scores);

        return array_slice($scores, 0, $limit, true);
    }

    // Dapatkan item yang paling mirip dengan item tertentu
    public function getSimilarItems($itemId, $limit = 5, $method = 'adjusted')
    {
        if (!isset($this->items[$itemId])) 
        {
            return [];
        }

        $similarities = [];
        $targetItemRatings = $this->items[$itemId];

        foreach ($this->items as $otherItemId => $otherItemRatings) 
        {
            if ($otherItemId == $itemId) 
            {
                continue;
            }

            if ($method === 'adjusted') 
            {
                $similarity = $this->adjustedCosineSimilarity($targetItemRatings, $otherItemRatings);
            } else 
            {
                $similarity = $this->cosineSimilarity($targetItemRatings, $otherItemRatings);
            }

            if ($similarity > 0) 
            {
                $similarities[$otherItemId] = $similarity;
            }
        }

        arsort($similarities);
        return array_slice($similarities, 0, $limit, true);
    }

    // Evaluasi akurasi sistem
    public function evaluateAccuracy($testRatings)
    {
        $predictions = [];
        $actuals = [];

        foreach ($testRatings as $userId => $userTestRatings) 
        {
            foreach ($userTestRatings as $itemId => $actualRating) 
            {
                // Hapus rating dari data training sementara
                $originalRating = null;
                if (isset($this->ratings[$userId][$itemId])) 
                {
                    $originalRating = $this->ratings[$userId][$itemId];
                    unset($this->ratings[$userId][$itemId]);
                    unset($this->items[$itemId][$userId]);
                }

                // Prediksi rating
                $recs = $this->getRecommendations($userId, 1);
                if (isset($recs[$itemId])) 
                {
                    $predictions[] = $recs[$itemId];
                    $actuals[] = $actualRating;
                }

                // Kembalikan rating asli
                if ($originalRating !== null) 
                {
                    $this->ratings[$userId][$itemId] = $originalRating;
                    $this->items[$itemId][$userId] = $originalRating;
                }
            }
        }

        // Hitung MAE (Mean Absolute Error)
        if (count($predictions) > 0) 
        {
            $mae = array_sum(array_map(function ($pred, $actual) 
            {
                return abs($pred - $actual);
            }, $predictions, $actuals)) / count($predictions);

            return ['mae' => $mae, 'predictions' => count($predictions)];
        }

        return ['mae' => 0, 'predictions' => 0];
    }
}

// Inisialisasi sistem CF
$cf = new ItemBasedCollaborativeFiltering($db);

//Query untuk menampilkan menu
if (!empty($category)) 
{
    if ($category === 'Rekomandasi') 
    {
        // Gunakan sistem rekomendasi jika user login
        if (isset($_SESSION['id_Pelanggan'])) 
        {
            $idPelanggan = $_SESSION['id_Pelanggan'];
            $recommendations = $cf->getRecommendations($idPelanggan, 20, 'adjusted');

            if (!empty($recommendations)) 
            {
                $recommendedIds = array_keys($recommendations);
                $placeholders = str_repeat('?,', count($recommendedIds) - 1) . '?';
                $query = "SELECT m.*, AVG(r.rating) AS avg_rating
                          FROM menu m
                          LEFT JOIN rating r ON m.id_Menu = r.id_Menu
                          WHERE m.id_Menu IN ($placeholders)
                          GROUP BY m.id_Menu
                          ORDER BY FIELD(m.id_Menu, " . implode(',', $recommendedIds) . ")";

                $stmt = $db->prepare($query);
                $stmt->bind_param(str_repeat('i', count($recommendedIds)), ...$recommendedIds);
                $stmt->execute();
                $result = $stmt->get_result();
            } else 
            {
                // Fallback ke rekomendasi berdasarkan rating tertinggi
                $query = "SELECT m.*, AVG(r.rating) AS avg_rating
                          FROM menu m
                          JOIN rating r ON m.id_Menu = r.id_Menu
                          GROUP BY m.id_Menu
                          ORDER BY avg_rating DESC
                          LIMIT 20";
                $result = $db->query($query);
            }
        } else 
        {
            // User belum login, tampilkan berdasarkan rating tertinggi
            $query = "SELECT m.*, AVG(r.rating) AS avg_rating
                      FROM menu m
                      JOIN rating r ON m.id_Menu = r.id_Menu
                      GROUP BY m.id_Menu
                      ORDER BY avg_rating DESC
                      LIMIT 20";
            $result = $db->query($query);
        }
    } else 
    {
        $query = "SELECT * FROM menu WHERE katg_Menu = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
    }
} else 
{
    $query = "SELECT * FROM menu";
    $result = $db->query($query);
}

// Debug information (hapus di production)
if (isset($_SESSION['id_Pelanggan']) && $category === 'Rekomandasi') 
{
    $debugInfo = [
        'user_id' => $_SESSION['id_Pelanggan'],
        'recommendations_count' => isset($recommendations) ? count($recommendations) : 0,
        'method' => 'Item-Based Collaborative Filtering'
    ];
    // echo "<!-- Debug: " . json_encode($debugInfo) . " -->";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Patdua</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="../img/logo.svg" rel="icon" type="image/x-icon">
    <script src="/func/addCart.js" defer></script>
</head>

<body>
    <!-- Header Start -->
     <header class="bg-green-600 fixed top-0 left-0 w-full flex items-center z-10">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between relative">
                <div class="px-4">
                    <a href="#home" class=" block py-4">
                        <img src="../img/logo.svg" alt="logo" class="h-16 w-auto">
                    </a>
                </div>
                <div class="flex items-center px-4">
                    <button id="hamburger" name="hamburger" type="button" class="block absolute flex flex-col items-center right-4 lg:hidden">
                        <span class="hamburger-menu transition duration-300 ease-in-out origin-top-left"></span>
                        <span class="hamburger-menu transition duration-300 ease-in-out"></span>
                        <span class="hamburger-menu transition duration-300 ease-in-out origin-bottom-left"></span>
                    </button>

                    <nav id="navbar-menu" class="hidden absolute py-5 bg-white shadow-lg rounded-lg max-w-[250px] w-full right-4 top-full lg:block lg:static lg:bg-transparent lg:max-w-full lg:shadow-none lg:rounded-none">
                        <ul class="block lg:flex ">
                            <li class="group">
                                <a href=".././index.php" class="text-lg font-semibold text-white py-2 mx-8 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Home</a>
                            </li>
                            <li class="group">
                                <a href=".././index.php#about" class="text-lg font-semibold text-white py-2 mx-8 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">About Us</a>
                            </li>
                            <li class="group">
                                <a href=".././index.php#promo" class="text-lg font-semibold text-white py-2 mx-8 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Promo</a>
                            </li>
                            <li class="group">
                                <a href=".././index.php#contact" class="text-lg font-semibold text-white py-2 mx-8 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Reserve Now</a>
                            </li>
                            <li class="group">
                                <a href=".././index.php#kritik" class="text-lg font-semibold text-white py-2 mx-8 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Feedback</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <!-- Catalog Section Start -->
    <section class="pt-12">
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-6 py-20 sm:px-6 sm:py-24 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <aside class="lg:col-span-1 hidden lg:block">
                    <div class="bg-gray-100 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">
                            <a href="halPesan" class="text-lg font-semibold">Category</a>
                        </h2>
                        <ul class="space-y-4">
                            <li>
                                <a href="halPesan?katg_Menu=Food" class="text-lg font-semibold text-gray-600 hover:text-green-600 px-2 py-1 rounded-lg
                                <?= ($category == 'Food') ? 'hover:text-white bg-green-600 text-white' : ''; ?>">
                                    Makanan
                                </a>
                            </li>
                            <li>
                                <a href="halPesan?katg_Menu=Drink" class="text-lg font-semibold text-gray-600 hover:text-green-600 px-2 py-1 rounded-lg
                                <?= ($category == 'Drink') ? 'hover:text-white bg-green-600 text-white' : ''; ?>">
                                    Minuman
                                </a>
                            </li>
                            <li>
                                <a href="halPesan?katg_Menu=Snack" class="text-lg font-semibold text-gray-600 hover:text-green-600 px-2 py-1 rounded-lg
                                <?= ($category == 'Snack') ? 'hover:text-white bg-green-600 text-white' : ''; ?>">
                                    Snack
                                </a>
                            </li>
                            <li>
                                <a href="halPesan?katg_Menu=Rekomandasi" class="text-lg font-semibold text-gray-600 hover:text-green-600 px-2 py-1 rounded-lg
                                <?= ($category == 'Rekomandasi') ? 'hover:text-white bg-green-600 text-white' : ''; ?>">
                                    Rekomendasi Kami
                                </a>
                            </li>
                        </ul>
                    </div>
                </aside>

                <div class="lg:col-span-4">
                    <div class="lg:hidden mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Kategori</h2>
                        <div class="flex space-x-3 overflow-x-auto pb-2">
                            <a href="halPesan.php?katg_Menu=Food" class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold border <?= ($category == 'Food') ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700'; ?>">
                                Makanan
                            </a>
                            <a href="halPesan?katg_Menu=Drink" class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold border <?= ($category == 'Drink') ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700'; ?>">
                                Minuman
                            </a>
                            <a href="halPesan?katg_Menu=Snack" class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold border <?= ($category == 'Snack') ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700'; ?>">
                                Snack
                            </a>
                            <a href="halPesan?katg_Menu=Rekomandasi" class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold border <?= ($category == 'Rekomandasi') ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700'; ?>">
                                Rekomendasi
                            </a>
                        </div>
                    </div>

                    <?php if ($category === 'Rekomandasi' && isset($_SESSION['id_Pelanggan'])): ?>
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Rekomendasi dipersonalisasi berdasarkan preferensi Anda menggunakan Item-Based Collaborative Filtering
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="flex gap-2 justify-end mb-6">
                        <button onclick="openCartModal()" type="button" class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-6 h-6">
                                <title>Cart</title>
                                <path fill="#ffffff" d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                            </svg>
                            <span class="badge-cart absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-gray-900 rounded-full -mt-12">0</span>
                        </button>

                        <div id="cart-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-300/50">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl overflow-hidden relative">
                                <button onclick="closeCartModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-200 text-2xl leading-none">&times;</button>
                                <div class="bg-green-600 px-4 py-2">
                                    <h2 class="text-xl font-bold m-0 text-white">Keranjang Anda</h2>
                                </div>
                                <div id="modal-content" class="p-6"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 xl:gap-x-8">
                        <?php while ($row = $result->fetch_assoc()) : ?>
                        <div class="group relative">
                            <?php if ($category === 'Rekomandasi' && isset($recommendations) && isset($recommendations[$row['id_Menu']])): ?>
                            <div class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full z-10">
                                Rekomendasi
                            </div>
                            <?php endif; ?>

                            <img src="../img/<?= $row['gamb_Menu']; ?>" alt="<?= $row['nama_Menu']; ?>" class="w-60 h-60 w-full rounded-lg bg-gray-200 object-cover">
                            <h3 class="mt-4 text-lg font-medium text-gray-700"><?= $row['nama_Menu']; ?></h3>
                            <p class="mt-1 text-sm text-gray-500"><?= $row['desc_Menu']; ?></p>
                            <p class="mt-1 text-lg font-medium text-gray-900">Rp <?= number_format($row['harga_Menu'], 0, ',', '.'); ?></p>

                            <?php if (isset($row['avg_rating'])): ?>
                            <div class="mt-1 flex items-center">
                                <div class="flex items-center">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-4 h-4 <?= $i <= round($row['avg_rating']) ? 'text-yellow-300' : 'text-gray-300'; ?> fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <?php endfor; ?>
                                </div>
                                <span class="ml-1 text-sm text-gray-500">(<?= number_format($row['avg_rating'], 1); ?>)</span>
                            </div>
                            <?php endif; ?>

                            <button onclick="addToCart(<?= $row['id_Menu']; ?>)" class="mt-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-600/75">Tambahkan</button>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Catalog Section End -->
    
    <!-- Floating Buttons -->
<div class="fixed bottom-6 right-6 z-50 flex flex-col gap-4 lg:hidden">
    <!-- Scroll to Top -->
    <button onclick="scrollToTop()" id="scrollTopBtn" class="bg-green-600 text-white p-3 rounded-full shadow-lg hover:bg-green-500">
         ↑
    </button>

    <!-- Floating Checkout -->
    <button onclick="openCartModal()" type="button" class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-6 h-6">
            <title>Cart</title>
            <path fill="#ffffff" d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
        </svg>
        <span class="badge-cart absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-gray-900 rounded-full -mt-12">0</span>
    </button>
    <div id="cart-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-300/50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl overflow-hidden relative">
        <button onclick="closeCartModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-200 text-2xl leading-none">&times;</button>
            <div class="bg-green-600 px-4 py-2">
                    <h2 class="text-xl font-bold m-0 text-white">Keranjang Anda</h2>
                </div>
            <div id="modal-content" class="p-6"></div>
        </div>
    </div>
</div>

    <!-- Script -->
    <script src="/func/OpenCartModal.js"></script>
    <script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

const scrollTopBtn = document.getElementById("scrollTopBtn");

window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
        scrollTopBtn.classList.remove("hidden");
    } else {
        scrollTopBtn.classList.add("hidden");
    }
});
</script>
</body>

</html>