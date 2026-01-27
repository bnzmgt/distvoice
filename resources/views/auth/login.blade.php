<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login — Invoice App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-semibold text-gray-800">
                Invoice App
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Sign in to continue
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    required
                    autofocus
                    class="w-full px-3 py-2 border border-gray-300 rounded-md
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            @if ($errors->any())
                <div class="text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-md
                       hover:bg-blue-700 transition"
            >
                Login
            </button>
        </form>

    </div>

</body>
</html>
