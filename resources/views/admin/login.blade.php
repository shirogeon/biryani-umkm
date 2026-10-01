<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengelola - Dapur Biryani Berkah</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-stone-900 min-h-screen flex items-center justify-center p-4 font-sans text-stone-100 antialiased">

    <div class="w-full max-w-md space-y-6">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Dapur Biryani Berkah" class="w-20 h-20 object-contain mx-auto bg-white p-2 rounded-2xl shadow-xl shadow-white/10 mb-4">
            <h1 class="text-2xl font-bold text-white tracking-tight">Dapur Nasi Biryani Berkah</h1>
            <p class="text-xs text-stone-400">Masuk ke Panel Pengelola Pesanan & Dapur</p>
        </div>

        <!-- Login Card -->
        <div class="bg-stone-800/90 border border-stone-700/80 p-8 rounded-3xl shadow-2xl backdrop-blur-md space-y-6">
            
            @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-950/80 border border-rose-800 text-rose-300 text-xs">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</p>
                @endforeach
            </div>
            @endif

            @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-950/80 border border-emerald-800 text-emerald-300 text-xs">
                <p class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</p>
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-stone-300 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" required value="{{ old('email', 'admin@biryani.com') }}" placeholder="admin@biryani.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-stone-600 bg-stone-900/70 text-sm text-white placeholder-stone-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-stone-500 text-sm"></i>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-stone-300 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required value="password123" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 rounded-xl border border-stone-600 bg-stone-900/70 text-sm text-white placeholder-stone-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-stone-500 text-sm"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-stone-400">
                        <input type="checkbox" name="remember" class="rounded bg-stone-900 border-stone-700 text-amber-600 focus:ring-0">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 bg-amber-600 hover:bg-amber-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-amber-600/30 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <div class="pt-4 border-t border-stone-700/60 text-center text-[11px] text-stone-500">
                <span class="block">Kredensial Default Demo:</span>
                <span class="text-stone-400 font-mono">admin@biryani.com / password123</span>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xs text-stone-400 hover:text-white transition-colors">
                &larr; Kembali ke Halaman Utama Pembeli
            </a>
        </div>

    </div>

</body>
</html>
