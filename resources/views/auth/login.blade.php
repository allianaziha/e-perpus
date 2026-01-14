<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan</title>

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/backend/images/logos/logo-mini.png') }}" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen">

    <!-- Background perpustakaan -->
    <div class="fixed inset-0 bg-cover bg-center" 
        style="background-image: url('{{ asset('assets/images/bg-perpus.jpg') }}'); filter: brightness(0.8);">
    </div>

    <!-- Form container -->
    <div class="flex items-center justify-center h-screen relative z-10">
        <div class="bg-white bg-opacity-75 rounded-xl shadow-lg p-8 w-full max-w-md">
           <!-- Ganti teks jadi logo -->
            <div class="text-center">
                <img src="{{ asset('assets/images/logo-perpus.png') }}" alt="Logo Perpustakaan" class="mx-auto w-30 h-20 mb-4">
            </div>

            <!-- Error message -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-gray-700" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium text-gray-700" for="password">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Login
                </button>
            </form>
            <div class="text-center mt-4">
                <p class="text-sm text-gray-700">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Register</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>