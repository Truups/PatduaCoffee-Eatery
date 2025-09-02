<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patdua Coffee & Eatery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss"></style>
    <link href="img/logo.svg" rel="icon" type="image/x-icon">
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

        #home 
        {
            position: relative;
            overflow: hidden;
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

<body class="overflow-x-hidden">

    <!-- Header Section Start -->
    <header class="bg-green-600 fixed top-0 left-0 w-full flex items-center z-10 transform transition-transform duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between relative">
                <div class="px-4">
                    <a href="#home" class=" block py-4">
                        <img src="/img/logo.svg" alt="logo" class="h-16 w-auto">
                    </a>
                </div>
                <div class="flex items-center px-4">
                    <button id="hamburger" name="hamburger" type="button" class="z-10 block absolute flex flex-col items-center right-4 lg:hidden">
                        <span class="hamburger-menu transition duration-300 ease-in-out origin-top-left"></span>
                        <span class="hamburger-menu transition duration-300 ease-in-out"></span>
                        <span class="hamburger-menu transition duration-300 ease-in-out origin-bottom-left"></span>
                    </button>

                    <nav id="navbar-menu" class="z-20 hidden fixed top-0 right-0 h-screen w-1/2 transform transition-transform duration-300 translate-x-full bg-green-600/70 lg:block lg:static lg:h-auto lg:w-auto lg:translate-x-0 lg:bg-transparent lg:shadow-none">
                        <ul class="block lg:flex lg:bg-green-600/95 lg:rounded-lg backdrop-blur-sm">
                            <li class="group">
                                <a href=".././index.php" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Home</a>
                            </li>
                            <li class="group">
                                <a href=".././index.php#about" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">About Us</a>
                            </li>
                            <li class="group">
                                <a href=".././index.php#menu" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Menu</a>
                            </li>
                            <li class="group">
                                <a href=".././index.php#promo" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Promo</a>
                            </li>
                            <li class="group">
                                <a href="#contact" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Reserve Now</a>
                            </li>
                            <li class="group">
                                <a href=".././index.php#kritik" class="text-3xl lg:text-lg justify-start lg:justify-center font-semibold text-white py-2 lg:py-2 mx-8 my-8 lg:my-2 flex hover:text-translate-y-1 hover:scale-110 hover:underline decoration-2 underline-offset-8 transition duration-300 ease-in-out">Feedback</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Home Section -->
    <section id="home" class="min-h-screen relative">
        <div class="parallax-bg absolute top-0 left-0 w-full h-full bg-center bg-no-repeat bg-cover bg-[url('img/g3.png')] z-0"></div>

        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative  w-full h-screen flex justify-center items-center p-4">
            <div class="flex justify-items-center w-2xs hero-logo">
                <img src="img/logo.svg">
            </div>
    </section>
    <!-- Home Section End -->

    <!-- About Section -->
    <section id="about" class="py-24 sm:py-32 bg-gray-200 overflow-hidden">
        <div class="container flex justify-center mx-auto px-4 py-16 lg:translate-x-46 ">

            <div class="relative mx-auto w-full max-w-xs h-[600px] -translate-x-0 lg:translate-x-24">

                <div class="absolute inset-0 bg-white  shadow-xl transform -rotate-10 -translate-x-24 translate-y-26">
                    <img src="img/fuud.jpg" class="w-full h-full object-cover p-2 pb-6">
                </div>

                <div class="absolute inset-0 bg-white  shadow-xl transform rotate-10 translate-x-46 translate-y-20 photo-card">
                    <img src="img/place.jpg" class="w-full h-full object-cover p-2 pb-6">
                </div>

                <div class="absolute inset-0 max-w-xs mx-auto px-8 py-20 sm:p-10 bg-center bg-no-repeat bg-cover bg-[url('img/paper.jpg')] text-center">

                    <h2 class="font-montserrat font-bold text-3xl sm:text-4xl text-green-600 mb-4">
                        Selamat Datang
                    </h2>

                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami bukan sekadar kedai kopi, kami adalah rumah kedua Anda di Surabaya. Tempat di mana Anda bisa menikmati secangkir kopi berkualitas, hidangan lezat, dan suasana yang hangat untuk bekerja, bertemu teman, atau sekadar bersantai.
                    </p>

                    <p class="text-gray-700 leading-relaxed mb-8">
                        Setiap biji kopi kami pilih dengan saksama dan setiap menu kami ciptakan dengan hati. Misi kami adalah menyajikan pengalaman yang tak terlupakan dalam setiap cangkir dan piring yang kami sajikan untuk Anda.
                    </p>
                </div>

            </div>
        </div>

        <div class="container flex justify-center mx-auto px-4 py-16 lg:-translate-x-46 mt-36">

            <div class="relative mx-auto w-full max-w-xs h-[600px] translate-x-0 lg:translate-x-24">

                <div class="absolute inset-0 bg-white  shadow-xl transform  lg:-translate-x-76 lg:translate-y-2 h-[400px] card">
                    <img src="img/card.jpg" class="w-full h-full object-cover p-2 pb-10">
                </div>

                <div class="absolute inset-0 bg-white  shadow-xl transform -rotate-10  translate-x-46 translate-y-20 lg:-translate-x-70 lg:translate-y-48 h-[400px] card1">
                    <img src="img/card1.jpg" class="w-full h-full object-cover p-2 pb-10">
                </div>

                <div class="absolute inset-0 bg-white  shadow-xl transform rotate-2 -translate-x-24 translate-y-26 lg:-translate-x-90 lg:translate-y-80 h-[400px] card2">
                    <img src="img/card2.jpg" class="w-full h-full object-cover p-2 pb-10">
                </div>

                <div class="absolute inset-0 max-w-xs mx-auto px-8 py-20 sm:p-10 bg-center bg-no-repeat bg-cover bg-[url('img/paper.jpg')] text-center">

                    <h3 class="font-montserrat font-bold text-2xl sm:text-4xl text-green-600 mb-4">
                        Ruang Bersama & Komunitas
                    </h3>

                    <p class="text-gray-700 leading-relaxed mb-4">
                        Di PATDUA, di sinilah semuanya berpadu. Mulai dari sesi musik akustik, diskusi inspiratif, pameran seni, hingga pertemuan komunitas. Sebuah tempat di mana makanan lezat bertemu dengan orang-orang terbaik.
                    </p>

                    <p class="text-gray-700 leading-relaxed mb-8">
                        Ada acara yang rutin, ada yang hanya sesekali—namun setiap momen di sini selalu unik dan istimewa. Temukan pengalaman favoritmu bersama kami.
                    </p>
                </div>

            </div>
        </div>
    </section>
    <!-- About Section End -->

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
                        Yuk tunggu apalagi cari tahu lebih dalam tentang kami. Klik dibawah ini untuk Reservasi dan Informasi.
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



    <!-- Footer Section -->
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
                        <li><a href="#about" class="inline-block text-base mb-3">Tentang Kami</a></li>
                        <li><a href="#promo" class="inline-block text-base mb-3">Promo</a></li>
                        <li><a href="#menu" class="inline-block text-base mb-3">Pesan</a></li>
                        <li><a href="#contact" class="inline-block text-base mb-3">Informasi & Reservasi</a></li>
                        <li><a href="#kritik" class="inline-block text-base mb-3">Kritik & Saran</a></li>
                    </ul>
                </div>
            </div>
            <div class="w-full pt-10 border-t border-slate-300">
                <div class="flex items-center justify-center mb-5">
                    <a href="https://www.instagram.com/patdua_eatery?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="w-9 h-9 mr-3 rounded-full flex justify-center items-center border border-slate-400 text-slate-400 hover:border-green-600 hover:bg-green-600 hover:text-white">
                        <svg role="img" width="20" class="fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <title>Instagram</title>
                            <path d="M7.0301.084c-1.2768.0602-2.1487.264-2.911.5634-.7888.3075-1.4575.72-2.1228 1.3877-.6652.6677-1.075 1.3368-1.3802 2.127-.2954.7638-.4956 1.6365-.552 2.914-.0564 1.2775-.0689 1.6882-.0626 4.947.0062 3.2586.0206 3.6671.0825 4.9473.061 1.2765.264 2.1482.5635 2.9107.308.7889.72 1.4573 1.388 2.1228.6679.6655 1.3365 1.0743 2.1285 1.38.7632.295 1.6361.4961 2.9134.552 1.2773.056 1.6884.069 4.9462.0627 3.2578-.0062 3.668-.0207 4.9478-.0814 1.28-.0607 2.147-.2652 2.9098-.5633.7889-.3086 1.4578-.72 2.1228-1.3881.665-.6682 1.0745-1.3378 1.3795-2.1284.2957-.7632.4966-1.636.552-2.9124.056-1.2809.0692-1.6898.063-4.948-.0063-3.2583-.021-3.6668-.0817-4.9465-.0607-1.2797-.264-2.1487-.5633-2.9117-.3084-.7889-.72-1.4568-1.3876-2.1228C21.2982 1.33 20.628.9208 19.8378.6165 19.074.321 18.2017.1197 16.9244.0645 15.6471.0093 15.236-.005 11.977.0014 8.718.0076 8.31.0215 7.0301.0839m.1402 21.6932c-1.17-.0509-1.8053-.2453-2.2287-.408-.5606-.216-.96-.4771-1.3819-.895-.422-.4178-.6811-.8186-.9-1.378-.1644-.4234-.3624-1.058-.4171-2.228-.0595-1.2645-.072-1.6442-.079-4.848-.007-3.2037.0053-3.583.0607-4.848.05-1.169.2456-1.805.408-2.2282.216-.5613.4762-.96.895-1.3816.4188-.4217.8184-.6814 1.3783-.9003.423-.1651 1.0575-.3614 2.227-.4171 1.2655-.06 1.6447-.072 4.848-.079 3.2033-.007 3.5835.005 4.8495.0608 1.169.0508 1.8053.2445 2.228.408.5608.216.96.4754 1.3816.895.4217.4194.6816.8176.9005 1.3787.1653.4217.3617 1.056.4169 2.2263.0602 1.2655.0739 1.645.0796 4.848.0058 3.203-.0055 3.5834-.061 4.848-.051 1.17-.245 1.8055-.408 2.2294-.216.5604-.4763.96-.8954 1.3814-.419.4215-.8181.6811-1.3783.9-.4224.1649-1.0577.3617-2.2262.4174-1.2656.0595-1.6448.072-4.8493.079-3.2045.007-3.5825-.006-4.848-.0608M16.953 5.5864A1.44 1.44 0 1 0 18.39 4.144a1.44 1.44 0 0 0-1.437 1.4424M5.8385 12.012c.0067 3.4032 2.7706 6.1557 6.173 6.1493 3.4026-.0065 6.157-2.7701 6.1506-6.1733-.0065-3.4032-2.771-6.1565-6.174-6.1498-3.403.0067-6.156 2.771-6.1496 6.1738M8 12.0077a4 4 0 1 1 4.008 3.9921A3.9996 3.9996 0 0 1 8 12.0077" />
                        </svg>
                    </a>
                    <a href="https://maps.app.goo.gl/your-location" target="_blank" class="w-9 h-9 mr-3 rounded-full flex justify-center items-center border border-slate-400 text-slate-400 hover:border-green-600 hover:bg-green-600 hover:text-white">
                        <svg role="img" width="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <title>Google Maps</title>
                            <path d="M12 0C7.8 0 4.2 3.6 4.2 8.4c0 6.3 7.8 15.6 7.8 15.6s7.8-9.3 7.8-15.6C19.8 3.6 16.2 0 12 0zm0 12.6c-2.31 0-4.2-1.89-4.2-4.2s1.89-4.2 4.2-4.2 4.2 1.89 4.2 4.2-1.89 4.2-4.2 4.2z" />
                        </svg>
                    </a>
                </div>
            </div>
            <p class="font-medium text-sm text-slate-300 text-center">Copyright PATDUA Coffee & Eatery</p>
        </div>
    </footer>
    <script src="/func/header.js"></script>
    <script src="/func/hamburger.js" defer></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);

        gsap.to(".parallax-bg", 
        {
            scrollTrigger: 
            {
                trigger: "#home",
                start: "top top",
                end: "bottom top",
                scrub: true,
            },
            yPercent: 30
        });

        gsap.to(".hero-content", 
        {
            scrollTrigger: 
            {
                trigger: "#home",
                start: "top top",
                end: "bottom top",
                scrub: 1,
            },
            yPercent: -25
        });

        gsap.from(".hero-logo", 
        {
            opacity: 0,
            scale: 0.5,
            duration: 2,
            ease: "power1.out"
        });

        gsap.from(".photo-card", 
        {
            scrollTrigger: 
            {
                trigger: "#about",
                start: "top top",
                end: "bottom center",
                toggleActions: "play reverse play reverse"
            },
            xPercent: -30
        })

        gsap.from(".card", 
        {
            scrollTrigger: 
            {
                trigger: "#about",
                start: "top center",
                end: "bottom center",
                toggleActions: "play reverse play reverse",

            },
            xPercent: -5
        })


        gsap.from(".card1", 
        {
            scrollTrigger: 
            {
                trigger: "#about",
                start: "top center",
                end: "bottom bottom",
                toggleActions: "play reverse play reverse",

            },
            xPercent: -40
        })

        gsap.from(".card2", 
        {
            scrollTrigger: 
            {
                trigger: "#about",
                start: "top center",
                end: "bottom bottom",
                toggleActions: "play reverse play reverse",

            },
            xPercent: -45
        })

        gsap.from(".reserve-card", 
        {
            scrollTrigger: 
            {
                trigger: "#contact",
                start: "top center",
                end: "center bottom",
                toggleActions: "play",

            },
            yPercent: 40
        })
    </script>
</body>

</html>