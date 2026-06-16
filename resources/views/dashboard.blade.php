<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuthShield - Dashboard</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            🛡️ AuthShield System
        </h1>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600 font-medium">Halo, {{ Auth::user()->username }}!</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm transition duration-200 shadow-sm">
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto mt-10 p-6">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-2 text-gray-800">Selamat Datang di Halaman Utama</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                Anda berhasil masuk. Login Anda divalidasi dengan mencocokkan input password, salt unik dari database, dan kode rahasia server.
            </p>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded text-sm text-blue-700 inline-block">
                <strong>Info Sesi:</strong> ID User Anda di database adalah <code class="bg-blue-200 px-2 py-0.5 rounded font-mono">{{ Auth::id() }}</code>
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-4">Analisis Kriptografi Sesi Anda</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="text-gray-500 text-sm font-semibold mb-1 uppercase tracking-wider">Hash Key (Database)</div>
                <div class="text-3xl mb-3">🗄️</div>
                <div class="bg-gray-800 text-green-400 font-mono text-xs p-3 rounded overflow-x-auto whitespace-nowrap scrollbar-hide">
                    {{ $userHash }}
                </div>
                <p class="text-xs text-gray-400 mt-3">Kombinasi password Anda yang sudah dilebur bersama Salt & Secret Key.</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="text-gray-500 text-sm font-semibold mb-1 uppercase tracking-wider">Total Iterasi Bcrypt</div>
                <div class="text-3xl mb-1">🔄</div>
                <div class="text-4xl font-black text-gray-800 my-2">
                    {{ number_format($totalIterations, 0, ',', '.') }} <span class="text-lg font-medium text-gray-400">putaran</span>
                </div>
                <p class="text-xs text-gray-400">
                    Berasal dari <strong>Cost Factor: {{ $costFactor }}</strong>. Artinya, CPU mengeksekusi algoritma tepat sebanyak angka di atas.
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="text-gray-500 text-sm font-semibold mb-1 uppercase tracking-wider">Live Benchmark CPU</div>
                <div class="text-3xl mb-1">⏱️</div>
                <div class="text-4xl font-black text-blue-600 my-2">
                    {{ $executionTimeMs }} <span class="text-lg font-medium text-gray-400">ms</span>
                </div>
                <p class="text-xs text-gray-400">Waktu nyata yang dibutuhkan server Anda untuk menyelesaikan {{ number_format($totalIterations, 0, ',', '.') }} putaran Bcrypt saat ini.</p>
            </div>
        </div>
    </main>
</body>
</html>