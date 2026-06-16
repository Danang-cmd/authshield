<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuthShield - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">AuthShield System</h1>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600 font-medium">Halo, {{ Auth::user()->username }}!</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm transition duration-200">
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Selamat Datang di Halaman Utama</h2>
        <p class="text-gray-600 leading-relaxed mb-4">
            Anda berhasil masuk.
        </p>
    </main>
</body>
</html>