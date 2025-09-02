<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patdua Coffee & Eatery</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss"></style>
    <link href="/img/logo.svg" rel="icon" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js"></script>

    <style>
        .font-montserrat 
        {
            font-family: 'Montserrat', sans-serif;
        }

        .hamburger-menu 
        {
            width: 24px;
            height: 4px;
            background-color: white;
            margin: 4px 0;
            border-radius: 4px;
            display: block;
        }

        #hamburger span
        {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        
        #hamburger.hamburger-active span:nth-child(1)
        {
            transform: translateY(12px) rotate(45deg);
            transform-origin: center;
        }

         #hamburger.hamburger-active span:nth-child(2)
        {
            opacity: 0;
            transform: translatex(-100%);
        }

        #hamburger.hamburger-active span:nth-child(3)
        {
            transform: translateY(-12px) rotate(-45deg);
            transform-origin: center;
        }

        
    </style>
</head>

<body>
    <!-- Header  Start -->
    <header class="bg-green-600 fixed top-0 left-0 w-full flex items-center z-10 transform transition-transform duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between relative">
                <div class="px-4">
                    <a href="#home" class=" block py-4">
                        <img src="/img/logo.svg" alt="logo" class="h-16 w-auto">
                    </a>
                </div>
                <div class="flex items-center px-4">
                    <button id="hamburger" name="hamburger" type="button" class="block absolute flex flex-col items-center right-4 lg:hidden">
                        <span class="hamburger-menu transition duration-300 ease-in-out origin-top-left"></span>
                        <span class="hamburger-menu transition duration-300 ease-in-out"></span>
                        <span class="hamburger-menu transition duration-300 ease-in-out origin-bottom-left"></span>
                    </button>

                    <nav id="navbar-menu" class="hidden fixed top-0 right-0 h-screen w-1/2 transform transition-transform duration-300 translate-x-full bg-green-600/70 lg:block lg:static lg:h-auto lg:w-auto lg:translate-x-0 lg:bg-transparent lg:shadow-none">
                        <ul class="block lg:flex lg:bg-green-600/95 lg:rounded-lg backdrop-blur-sm">
                            <li class="group">
                                <a href="#home" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Home</a>
                            </li>
                            <li class="group">
                                <a href="#about" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">About Us</a>
                            </li>
                            <li class="group">
                                <a href="#menu" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Menu</a>
                            </li>
                            <li class="group">
                                <a href="#promo" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Promo</a>
                            </li>
                            <li class="group">
                                <a href="#contact" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Reserve Now</a>
                            </li>
                            <li class="group">
                                <a href="#kritik" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Feedback</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->


    <!-- Hero Section Start -->
    <section id="home" class="pt-40 min-h-screen bg-center bg-no-repeat bg-cover bg-[url('img/g3.png')] relative">
        <div class="hero-parallax absolute inset-0 z-0 bg-black/60">
            <div class="absolute inset-0 z-10 flex items-center">
                <div class="container mx-auto px-4">
                    <div class="flex flex-wrap">
                        <div class="w-full px-4 lg:w-1/2">
                            <h1 id="hero-tittle" class="text-4xl font-montserrat font-bold text-green-600 md:text-6xl lg:text-8xl">PATDUA
                                <span class="block font-montserrat font-semibold text-2xl mt-1 text-green-600 lg:text-3xl">COFFEE & EATERY</span>
                            </h1>
                            <h2 id="hero-description" class="text-white font-montserrat font-light text-lg mt-2 leading-tight">COFFEE - FOOD SHARING SPACE</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->


    <!-- About Section Start -->
    <section id="about" class="pt-36 pb-32 relative bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <div class="w-full px-4 mb-10 lg:w-1/2">
                    <h4 class="font-bold uppercase text-green-600 mb-3">Tentang Kami</h4>
                    <h2 class="font-bold font-montserrat text-3xl mb-5 max-w-md lg:text-4xl">Patdua Coffee & Eatery</h2>
                    <p class="font-medium text-base text-slate-500 max-w-xl lg:text-lg">Yuk, kenali kami lebih dekat!</p>
                    <div class="mt-10">
                        <a href="/aboutus.php" class="group text-base font-semibold bg-green-600 text-white py-3 px-8 rounded-full hover:shadow-lg hover:bg-white border-solid border-2 border-green-600 transition duration-300 ease-in-out">
                            <span class=" text-white group-hover:text-green-600">Lihat Selengkapnya</span>
                        </a>
                    </div>
                </div>

                <div class="w-full px-4 lg:w-1/2 ">
                    <h3 class="font-semibold font-montserrat text-2xl mb-4 lg:text-3xl lg:pt-10">Kunjungi Kami</h3>
                    <p class="text-base lg:text-lg">Ingin melihat berbagai menu yang kami tawarkan atau mencoba suasana tempat kami ?
                        Kunjungi kami dan temukan berbagai hal menarik yang kami tawarkan atau kunjungi ruang digital kami melalui Instagram. </p>
                    <div class="flex items-center mt-2">
                        <!-- Instagram SVG -->
                        <a href="https://www.instagram.com/patdua_eatery?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="w-9 h-9 mr-3 rounded-full flex justify-center items-center border border-slate-400 text-slate-400 hover:border-green-600 hover:bg-green-600 hover:text-white">
                            <svg role="img" width="20" class="fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <title>Instagram</title>
                                <path d="M7.0301.084c-1.2768.0602-2.1487.264-2.911.5634-.7888.3075-1.4575.72-2.1228 1.3877-.6652.6677-1.075 1.3368-1.3802 2.127-.2954.7638-.4956 1.6365-.552 2.914-.0564 1.2775-.0689 1.6882-.0626 4.947.0062 3.2586.0206 3.6671.0825 4.9473.061 1.2765.264 2.1482.5635 2.9107.308.7889.72 1.4573 1.388 2.1228.6679.6655 1.3365 1.0743 2.1285 1.38.7632.295 1.6361.4961 2.9134.552 1.2773.056 1.6884.069 4.9462.0627 3.2578-.0062 3.668-.0207 4.9478-.0814 1.28-.0607 2.147-.2652 2.9098-.5633.7889-.3086 1.4578-.72 2.1228-1.3881.665-.6682 1.0745-1.3378 1.3795-2.1284.2957-.7632.4966-1.636.552-2.9124.056-1.2809.0692-1.6898.063-4.948-.0063-3.2583-.021-3.6668-.0817-4.9465-.0607-1.2797-.264-2.1487-.5633-2.9117-.3084-.7889-.72-1.4568-1.3876-2.1228C21.2982 1.33 20.628.9208 19.8378.6165 19.074.321 18.2017.1197 16.9244.0645 15.6471.0093 15.236-.005 11.977.0014 8.718.0076 8.31.0215 7.0301.0839m.1402 21.6932c-1.17-.0509-1.8053-.2453-2.2287-.408-.5606-.216-.96-.4771-1.3819-.895-.422-.4178-.6811-.8186-.9-1.378-.1644-.4234-.3624-1.058-.4171-2.228-.0595-1.2645-.072-1.6442-.079-4.848-.007-3.2037.0053-3.583.0607-4.848.05-1.169.2456-1.805.408-2.2282.216-.5613.4762-.96.895-1.3816.4188-.4217.8184-.6814 1.3783-.9003.423-.1651 1.0575-.3614 2.227-.4171 1.2655-.06 1.6447-.072 4.848-.079 3.2033-.007 3.5835.005 4.8495.0608 1.169.0508 1.8053.2445 2.228.408.5608.216.96.4754 1.3816.895.4217.4194.6816.8176.9005 1.3787.1653.4217.3617 1.056.4169 2.2263.0602 1.2655.0739 1.645.0796 4.848.0058 3.203-.0055 3.5834-.061 4.848-.051 1.17-.245 1.8055-.408 2.2294-.216.5604-.4763.96-.8954 1.3814-.419.4215-.8181.6811-1.3783.9-.4224.1649-1.0577.3617-2.2262.4174-1.2656.0595-1.6448.072-4.8493.079-3.2045.007-3.5825-.006-4.848-.0608M16.953 5.5864A1.44 1.44 0 1 0 18.39 4.144a1.44 1.44 0 0 0-1.437 1.4424M5.8385 12.012c.0067 3.4032 2.7706 6.1557 6.173 6.1493 3.4026-.0065 6.157-2.7701 6.1506-6.1733-.0065-3.4032-2.771-6.1565-6.174-6.1498-3.403.0067-6.156 2.771-6.1496 6.1738M8 12.0077a4 4 0 1 1 4.008 3.9921A3.9996 3.9996 0 0 1 8 12.0077" />
                            </svg>
                        </a>
                        <!-- Google Maps SVG -->
                        <a href="https://www.instagram.com/patdua_eatery?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="w-9 h-9 mr-3 rounded-full flex justify-center items-center border border-slate-400 text-slate-400 hover:border-green-600 hover:bg-green-600 hover:text-white">
                            <svg role="img" width="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <title>Google Maps</title>
                                <path d="M19.527 4.799c1.212 2.608.937 5.678-.405 8.173-1.101 2.047-2.744 3.74-4.098 5.614-.619.858-1.244 1.75-1.669 2.727-.141.325-.263.658-.383.992-.121.333-.224.673-.34 1.008-.109.314-.236.684-.627.687h-.007c-.466-.001-.579-.53-.695-.887-.284-.874-.581-1.713-1.019-2.525-.51-.944-1.145-1.817-1.79-2.671L19.527 4.799zM8.545 7.705l-3.959 4.707c.724 1.54 1.821 2.863 2.871 4.18.247.31.494.622.737.936l4.984-5.925-.029.01c-1.741.601-3.691-.291-4.392-1.987a3.377 3.377 0 0 1-.209-.716c-.063-.437-.077-.761-.004-1.198l.001-.007zM5.492 3.149l-.003.004c-1.947 2.466-2.281 5.88-1.117 8.77l4.785-5.689-.058-.05-3.607-3.035zM14.661.436l-3.838 4.563a.295.295 0 0 1 .027-.01c1.6-.551 3.403.15 4.22 1.626.176.319.323.683.377 1.045.068.446.085.773.012 1.22l-.003.016 3.836-4.561A8.382 8.382 0 0 0 14.67.439l-.009-.003zM9.466 5.868L14.162.285l-.047-.012A8.31 8.31 0 0 0 11.986 0a8.439 8.439 0 0 0-6.169 2.766l-.016.018 3.665 3.084z" />
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- About Section End -->


    <!-- Menu Section Start-->
    <section id="menu" class="pt-36 pb-16 bg-white section-menu">
        <div class="container mx-auto px-4">
            <div class="w-full px-4">
                <div class="max-w-xl mx-auto text-center mb-16">
                    <h2 class="font-bold font-montserrat text-3xl text-green-600 mb-2">Menu</h2>
                    <h3 class="font-semibold text-slate-500 text-lg mb-4">
                        Jelajahi beragam pilihan menu dan temukan favorit barumu!
                    </h3>
                </div>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <div class="rounded-md shadow-md overflow-hidden card-menu">
                    <img src="img/fleurish.jpg" alt="Menu 1" class="w-full h-80 object-cover">
                </div>
                <div class="rounded-md shadow-md overflow-hidden card-menu">
                    <img src="img/gochujang.jpg" alt="Menu 2" class="w-full h-80 object-cover">
                </div>
                <div class="rounded-md shadow-md overflow-hidden card-menu">
                    <img src="img/namban.jpg" alt="Menu 3" class="w-full h-80 object-cover">
                </div>
                <div class="rounded-md shadow-md overflow-hidden card-menu">
                    <img src="img/seafud.jpg" alt="Menu 4" class="w-full h-80 object-cover">
                </div>
            </div>
            <!-- Tombol Selengkapnya -->
            <div class="mt-10 flex flex-wrap justify-center">
                <a href="/halPesan"
                    class="group text-base font-semibold bg-green-600 text-white py-3 px-8 rounded-full hover:shadow-lg hover:bg-white border-solid border-2 border-green-600 transition duration-300 ease-in-out">
                    <span class="text-white group-hover:text-green-600">Lihat Selengkapnya</span>
                </a>
            </div>
        </div>
    </section>
    <!-- Menu Section End-->


    <!-- Promo Section Start-->
    <section id="promo" class="pt-36 pb-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="w-full px-4">
                <div class="max-w-xl mx-auto text-center mb-16">
                    <h2 class="font-bold font-montserrat text-3xl text-green-600 mb-2">Promo PATDUA</h2>
                    <h3 class="font-semibold text-slate-500 text-lg mb-4"> Temukan berbagai promo menarik kami!</h3>
                </div>
            </div>
            <div class="w-full px-4 flex flex-wrap justify-center xl:w-10/12 xl:mx-auto">
                <div class="mb-12 p-4 md:w-1/2 promo-card">
                    <div class="rounded-md shadow-md overflow-hidden">
                        <img src="img/imlekpromo.webp" alt="Lunar New Year Promo " width="w-full">
                    </div>
                </div>
            </div>
            <div class="w-full px-4 flex flex-wrap justify-center">
                <div class="mb-12 p-4 md:w-1/2 promo-card">
                    <div class="rounded-md shadow-md overflow-hidden">
                        <img src="img/disc15.webp" alt="Promo PATDUA" width="w-full">
                    </div>
                </div>
            </div>
            <!-- <div class="w-full px-4 flex flex-wrap justify-center">
                <div class="mb-12 p-4 md:w-1/2">
                    <div class="rounded-md shadow-md overflow-hidden">
                        <img src="/img/3.jpg" alt="Lorem Ipsum" width="w-full">
                    </div>
                </div>
            </div>
            <div class="w-full px-4 flex flex-wrap justify-center">
                <div class="mb-12 p-4 md:w-1/2">
                    <div class="rounded-md shadow-md overflow-hidden">
                        <img src="/img/4.jpg" alt="Lorem Ipsum" width="w-full">
                    </div>
                </div>
            </div> -->
        </div>
    </section>
    <!-- Promo Section End -->


    <!-- Reservasi Section Start -->
    <section id="contact" class="pt-36 pb-32">
        <div class="max-w-5xl mx-auto h-[400px] rounded-2xl overflow-hidden shadow-lg relative reserve-card">
            <!-- Background image -->
            <div class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('https://images.unsplash.com/photo-1557683316-973673baf926?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80');">
            </div>
            <div class="absolute inset-0 bg-green-800 bg-opacity-80 mix-blend-multiply"></div>
            <!-- Konten -->
            <div class="relative px-12 py-24 text-white">
                <div class="text-left text-justifyn max-w-2xl">
                    <h2 class="text-4xl font-bold font-montserrat mb-4">Informasi & Reservasi</h2>
                    <p class="text-xl font-medium text-slate-100 mb-6">
                        Ingin memastikan tempat duduk saat jam sibuk? Klik di bawah ini untuk mendapatkan informasi lebih lanjut mengenai Reservasi.
                    </p>
                    <a href="https://api.whatsapp.com/send/?phone=%2B6285173450042&text&type=phone_number&app_absent=0"
                        class="inline-block bg-white text-green-700 font-semibold py-3 px-8 rounded-full hover:bg-green-600 hover:text-white transition duration-300">
                        Selengkapnya
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Reservasi Section End -->


    <!-- Kritik & Saran Section Start -->
    <section id="kritik" class="pt-36 pb-32">
        <div class="container mx-auto px-4">
            <div class="w-full px-4">
                <div class="w-full px-4">
                    <div class="max-w-xl mx-auto text-center mb-16">
                        <h2 class="font-bold font-montserrat text-3xl text-green-600 mb-2">Kritik dan Saran</h2>
                        <h3 class="font-semibold text-slate-500 text-lg mb-4">Punya masukan untuk kami? Ayo bagikan kritik & saran anda agar kami bisa menjadi lebih baik lagi</h3>
                    </div>
                </div>
            </div>
            <form>
                <div class="w-full lg:w-2/3 lg:mx-auto">
                    <div class="w-full px-4">
                        <a href="https://g.co/kgs/VNGctKh" class="flex text-base font-semibold text-white bg-green-600 py-3 px-8 rounded-full justify-self-center hover:opacity-80 hover:shadow-lg transition duration-500">Selengkapnya</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Kritik & Saran Section End -->



    <!-- Footer Section Start -->
    <footer class="bg-green-600 pt-24 pb-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <div class="w-full px-4 mb-12 text-slate-300 font-medium md:w-1/3">
                    <h2 class="font-bold font-montserrat text-4xl text-white">PATDUA Coffee & Eatery</h2>
                    <h3 class="font-bold font-montserrat text-2xl mb-2">Hubungi Kami</h3>
                    <p>patdua@gmail.com</p>
                    <p>Jl. Rungkut Madya No.203, Rungkut Kidul</p>
                    <p>Surabaya</p>
                </div>
                <div class="w-full px-4 mb-12 md:w-1/3">
                    <h3 class="font-semibold text-white">Informasi</h3>
                    <ul class="text-slate-300">
                        <li>
                            <a href="#about" class="inline-block text-base mb-3">Tentang Kami</a>
                        </li>
                    </ul>
                    <ul class="text-slate-300">
                        <li>
                            <a href="#promo" class="inline-block text-base mb-3">Promo</a>
                        </li>
                    </ul>
                    <ul class="text-slate-300">
                        <li>
                            <a href="#menu" class="inline-block text-base mb-3">Pesan</a>
                        </li>
                    </ul>
                    <ul class="text-slate-300">
                        <li>
                            <a href="#contact" class="inline-block text-base mb-3">Informasi & Reservasi</a>
                        </li>
                    </ul>
                    <ul class="text-slate-300">
                        <li>
                            <a href="#kritik" class="inline-block text-base mb-3">Kritik & Saran</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full pt-10 border-t border-slate-300">
                <div class="flex items-center justify-center mb-5">
                    <!-- Instagram SVG -->
                    <a href="https://www.instagram.com/patdua_eatery?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="w-9 h-9 mr-3 rounded-full flex justify-center items-center border border-slate-400 text-slate-400 hover:border-green-600 hover:bg-green-600 hover:text-white">
                        <svg role="img" width="20" class="fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <title>Instagram</title>
                            <path d="M7.0301.084c-1.2768.0602-2.1487.264-2.911.5634-.7888.3075-1.4575.72-2.1228 1.3877-.6652.6677-1.075 1.3368-1.3802 2.127-.2954.7638-.4956 1.6365-.552 2.914-.0564 1.2775-.0689 1.6882-.0626 4.947.0062 3.2586.0206 3.6671.0825 4.9473.061 1.2765.264 2.1482.5635 2.9107.308.7889.72 1.4573 1.388 2.1228.6679.6655 1.3365 1.0743 2.1285 1.38.7632.295 1.6361.4961 2.9134.552 1.2773.056 1.6884.069 4.9462.0627 3.2578-.0062 3.668-.0207 4.9478-.0814 1.28-.0607 2.147-.2652 2.9098-.5633.7889-.3086 1.4578-.72 2.1228-1.3881.665-.6682 1.0745-1.3378 1.3795-2.1284.2957-.7632.4966-1.636.552-2.9124.056-1.2809.0692-1.6898.063-4.948-.0063-3.2583-.021-3.6668-.0817-4.9465-.0607-1.2797-.264-2.1487-.5633-2.9117-.3084-.7889-.72-1.4568-1.3876-2.1228C21.2982 1.33 20.628.9208 19.8378.6165 19.074.321 18.2017.1197 16.9244.0645 15.6471.0093 15.236-.005 11.977.0014 8.718.0076 8.31.0215 7.0301.0839m.1402 21.6932c-1.17-.0509-1.8053-.2453-2.2287-.408-.5606-.216-.96-.4771-1.3819-.895-.422-.4178-.6811-.8186-.9-1.378-.1644-.4234-.3624-1.058-.4171-2.228-.0595-1.2645-.072-1.6442-.079-4.848-.007-3.2037.0053-3.583.0607-4.848.05-1.169.2456-1.805.408-2.2282.216-.5613.4762-.96.895-1.3816.4188-.4217.8184-.6814 1.3783-.9003.423-.1651 1.0575-.3614 2.227-.4171 1.2655-.06 1.6447-.072 4.848-.079 3.2033-.007 3.5835.005 4.8495.0608 1.169.0508 1.8053.2445 2.228.408.5608.216.96.4754 1.3816.895.4217.4194.6816.8176.9005 1.3787.1653.4217.3617 1.056.4169 2.2263.0602 1.2655.0739 1.645.0796 4.848.0058 3.203-.0055 3.5834-.061 4.848-.051 1.17-.245 1.8055-.408 2.2294-.216.5604-.4763.96-.8954 1.3814-.419.4215-.8181.6811-1.3783.9-.4224.1649-1.0577.3617-2.2262.4174-1.2656.0595-1.6448.072-4.8493.079-3.2045.007-3.5825-.006-4.848-.0608M16.953 5.5864A1.44 1.44 0 1 0 18.39 4.144a1.44 1.44 0 0 0-1.437 1.4424M5.8385 12.012c.0067 3.4032 2.7706 6.1557 6.173 6.1493 3.4026-.0065 6.157-2.7701 6.1506-6.1733-.0065-3.4032-2.771-6.1565-6.174-6.1498-3.403.0067-6.156 2.771-6.1496 6.1738M8 12.0077a4 4 0 1 1 4.008 3.9921A3.9996 3.9996 0 0 1 8 12.0077" />
                        </svg>
                    </a>
                    <!-- Google Maps SVG -->
                    <a href="https://www.instagram.com/patdua_eatery?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="w-9 h-9 mr-3 rounded-full flex justify-center items-center border border-slate-400 text-slate-400 hover:border-green-600 hover:bg-green-600 hover:text-white">
                        <svg role="img" width="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <title>Google Maps</title>
                            <path d="M19.527 4.799c1.212 2.608.937 5.678-.405 8.173-1.101 2.047-2.744 3.74-4.098 5.614-.619.858-1.244 1.75-1.669 2.727-.141.325-.263.658-.383.992-.121.333-.224.673-.34 1.008-.109.314-.236.684-.627.687h-.007c-.466-.001-.579-.53-.695-.887-.284-.874-.581-1.713-1.019-2.525-.51-.944-1.145-1.817-1.79-2.671L19.527 4.799zM8.545 7.705l-3.959 4.707c.724 1.54 1.821 2.863 2.871 4.18.247.31.494.622.737.936l4.984-5.925-.029.01c-1.741.601-3.691-.291-4.392-1.987a3.377 3.377 0 0 1-.209-.716c-.063-.437-.077-.761-.004-1.198l.001-.007zM5.492 3.149l-.003.004c-1.947 2.466-2.281 5.88-1.117 8.77l4.785-5.689-.058-.05-3.607-3.035zM14.661.436l-3.838 4.563a.295.295 0 0 1 .027-.01c1.6-.551 3.403.15 4.22 1.626.176.319.323.683.377 1.045.068.446.085.773.012 1.22l-.003.016 3.836-4.561A8.382 8.382 0 0 0 14.67.439l-.009-.003zM9.466 5.868L14.162.285l-.047-.012A8.31 8.31 0 0 0 11.986 0a8.439 8.439 0 0 0-6.169 2.766l-.016.018 3.665 3.084z" />
                        </svg>
                    </a>
                </div>
            </div>
            <p class="font-medium text-sm text-slate-300 text-center">Copyright PATDUA Coffee & Eatery</p>
        </div>

    </footer>
    <!-- JScript -->
    <script src="/func/ratingPop.js"></script>
    <script src="/func/header.js"></script>
    <script src="/func/hamburger.js"></script>

    <script>
        gsap.from("#hero-tittle", {opacity:0., y:50, duration:1, ease:"power4.out"});
        gsap.from("#hero-description", {opacity:0., y:50, duration:1, delay:0.5, ease:"power4.out"});
        gsap.registerPlugin(ScrollTrigger);

        gsap.from(".card-menu", 
        {
            opacity: 0,
            y: 50,
            scrollTrigger: 
            {
                trigger: "#menu",
                start: "top 80%",
                toggleActionis: "play none none",
            },

            duration: 0.8,
            ease: "power2.out",
            stagger: 0.2
        });

        gsap.from(".promo-card", 
        {
            opacity: 0,
            scale: 0.9,
            scrollTrigger: 
            {
                trigger: "#promo",
                start: "top 80%",
                toggleActionis: "play none none",
            },

            duration: 1,
            ease: "power1.out",
            stagger: 0.2
        });

        gsap.from(".reserve-card", 
        {
            opacity: 0,
            y: 40,
            scrollTrigger: 
            {
                trigger: "#contact",
                start: "top 80%",
                toggleActionis: "play none none",
            },

            duration: 1,
            ease: "power3.out",
            stagger: 0.2
        });
    </script>


</body>

</html>