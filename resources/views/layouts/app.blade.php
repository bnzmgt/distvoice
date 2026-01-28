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
            <span class="font-semibold text-lg text-gray-800">
                Invoice App
            </span>

            <div class="relative">
                <button
                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md"
                    onclick="document.getElementById('app-menu').classList.toggle('hidden')"
                >
                    Menu
                </button>

                <div
                    id="app-menu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg"
                >
                    <a href="{{ route('app.dashboard') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Dashboard
                    </a>

                    <a href="{{ route('app.invoices.index') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Invoices
                    </a>

                    <a href="{{ route('app.clients.index') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Clients
                    </a>

                    <a href="{{ route('app.products.index') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Products
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="p-4">
        @yield('content')
    </main>

</body>
</html>
