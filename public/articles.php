<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-l from-rose-950 via-rose-600 to-rose-950 text-white h-screen fixed p-2">
        <div class="flex justify-center items-center mx-auto">
            <img src="../assets/imgs/writer-8.png" alt="photo devisiteur" class="rounded-full w-52 h-52 border border-rose-950 border-4">
        </div>
        <div class="p-4 text-center">
            <h1 class="text-lg font-bold">Guest</h1>
        </div>
        <nav class="mt-6">
            <ul>
                <li class="p-3 my-1 hover:bg-rose-500 border border-2 border-rose-950 cursor-pointer"><a href="#">Mon Profil</a></li>
                <li class="p-3 my-1 hover:bg-rose-500 border border-2 border-rose-950 cursor-pointer"><a href="#">les règles du Blog</a></li>
                <li class="p-3 my-1 bg-rose-700 hover:bg-rose-500 border border-2 border-rose-950 cursor-pointer"><a href="#">Déconnexion</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6">
        <section class="bg-gray-50 py-8 px-4 antialiased md:py-12">
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <!-- Heading & Filters -->
                <div class="mb-4 items-end justify-between sm:flex sm:space-y-0 md:mb-8">
                    <div>
                        <h3 class="font-semi-bold text-rose-600">Bonjour <span class="text-rose-900 font-bold">Guest,</span></h3>
                        <h2 class="mt-3 text-xl font-semibold text-rose-900 sm:text-2xl">Nos articles :</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="sortDropdownButton1" data-dropdown-toggle="dropdownSort1" type="button" class="flex w-full items-center justify-center rounded-lg border border-rose-600 px-3 py-2 font-medium text-gray-300 bg-rose-600 hover:bg-rose-800 hover:text-white focus:z-10 focus:outline-none focus:ring-4 focus:ring-rose-100 sm:w-auto">
                            Afficher par :
                            <svg class="-me-0.5 ms-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="dropdownSort1" class="z-50 hidden w-40 divide-y divide-gray-100 rounded-lg bg-rose-600 shadow" data-popper-placement="bottom">
                            <ul class="p-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400" aria-labelledby="sortDropdownButton">
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> The most popular </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> Newest </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> Increasing price </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> Decreasing price </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> No. reviews </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> Discount % </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>



                <div class="mb-4 grid gap-4 sm:grid-cols-1 md:mb-8 lg:grid-cols-3 xl:gap-10 xl:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-2xl">
                        <div class="h-56 w-full">
                            <a href="#">
                                <!-- <img class="mx-auto h-full dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg" alt="" /> -->
                                <!-- <img class="mx-auto hidden h-full dark:block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg" alt="" /> -->
                            </a>
                        </div>
                        <div class="pt-6">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <span class="me-2 rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800">Catégorie : <span class="font-bold">Médecine</span></span>
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" data-tooltip-target="tooltip-reading-time" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                                <span class="sr-only"> Reading Time </span>
                                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                                    <path stroke="currentColor" stroke-width="2" d="M12 7v5l3 3" />
                                </svg>
                            </button>
                        <button type="button" data-tooltip-target="tooltip-add-to-favorites" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 flex">
                            <span class="sr-only"> Add to Favorites </span>
                            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-500">(455)</span>
                        </button>
                    <div id="tooltip-add-to-favorites" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300" data-popper-placement="top">
                Add to favorites
                <div class="tooltip-arrow" data-popper-arrow=""></div>
              </div>
            </div>
          </div>
          <a href="#" class="text-xl font-bold leading-tight text-purple-600">Title</a>
          <div>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus, quis deserunt doloremque inventore dolores, eum unde, magni quae id ipsa dolorem! Omnis, maiores vitae suscipit est ipsa sint veritatis facilis?
            </p>
          </div>
          <div class="flex w-full">
            <button type="button" class="mx-auto rounded-lg px-2 py-1 mt-2 text-sm font-medium text-gray-500 hover:text-purple-700">
                Continuer la lecture..
            </button>
          </div>
          </div>
        </div>
      </div>




      
    </div>
    <div class="w-full text-center">
      <button type="button" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Show more</button>
    </div>
  </div>


  
</section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
