<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuthShield - Register</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">AuthShield - Register</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-16" required>
                    
                    <button type="button" onclick="togglePassword('password', 'toggleText1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm font-semibold text-gray-500 hover:text-blue-600 focus:outline-none">
                        <span id="toggleText1">Lihat</span>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Gunakan kombinasi huruf besar, kecil, angka, dan simbol agar lolos cek entropi.</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-16" required>
                    
                    <button type="button" onclick="togglePassword('password_confirmation', 'toggleText2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm font-semibold text-gray-500 hover:text-blue-600 focus:outline-none">
                        <span id="toggleText2">Lihat</span>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">Daftar</button>
        </form>

        <p class="text-sm text-center text-gray-600 mt-4">
            Sudah punya akun? <a href="{{ route('login.view') }}" class="text-blue-500 hover:underline">Login di sini</a>
        </p>
    </div>

    <script>
        function togglePassword(inputId, textId) {
            const input = document.getElementById(inputId);
            const text = document.getElementById(textId);

            // Jika tipe saat ini adalah password (tersembunyi)
            if (input.type === 'password') {
                input.type = 'text';          // Ubah menjadi teks (terlihat)
                text.innerText = 'Tutup';     // Ubah label tombol
            } else {
                input.type = 'password';      // Kembalikan ke password
                text.innerText = 'Lihat';     // Kembalikan label tombol
            }
        }
    </script>
</body>
</html>