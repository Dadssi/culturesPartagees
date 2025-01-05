<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register form</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' 
    rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    
    <!-- Navbar Start -->
    <nav class="bg-purple-900 text-white shadow-xl ">
      <div class="container mx-auto px-4 flex justify-between items-center py-4">
        <a href="index.php" class="text-lg font-bold hover:text-gray-300">CULTURES PARTAGEES</a>
        <div class="hidden md:flex space-x-6 items-center">
          <a href="../public/index.php" class="hover:text-gray-300">Accueil</a>
          <a href="about.php" class="hover:text-gray-300">À propos</a>
          <a href="services.php" class="hover:text-gray-300">Services</a>
          <a href="contact.php" class="hover:text-gray-300">Contact</a>
        </div>

        <button id="menu-btn" class="block md:hidden">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
          </svg>
        </button>
      </div>

      <div id="mobile-menu" class="hidden md:hidden bg-gray-700">
        <a href="index.php" class="block px-4 py-2 hover:bg-gray-600">Accueil</a>
        <a href="about.php" class="block px-4 py-2 hover:bg-gray-600">À propos</a>
        <a href="services.php" class="block px-4 py-2 hover:bg-gray-600">Services</a>
        <a href="contact.php" class="block px-4 py-2 hover:bg-gray-600">Contact</a>
      </div>
    </nav>

    <script>
            // Toggle Mobile Menu
      const menuBtn = document.getElementById('menu-btn');
      const mobileMenu = document.getElementById('mobile-menu');

      menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
      });
    </script>

    <!-- Navbar End -->