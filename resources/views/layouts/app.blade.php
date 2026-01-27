<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Invoice App')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-40">
        <div class="flex items-center justify-between px-4 py-3">
            <span class="font-semibold text-lg">Invoice App</span>

            <!-- placeholder menu button -->
            <button class="text-gray-700">
                ☰
            </button>
        </div>
    </header>

    <!-- Content -->
    <main class="p-4">
        @yield('content')
    </main>

</body>
</html>
