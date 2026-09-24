<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Gudang AFNALINK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'Inter',system-ui,sans-serif}</style>
</head>
<body class="min-h-screen bg-[#08080f] flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <img src="https://ber.afna.link/storage/uploads/logos/company_logo_1768520380.png" class="w-16 h-16 mx-auto rounded-2xl mb-4">
            <h1 class="text-2xl font-extrabold tracking-wide text-white">GUDANG AFNALINK</h1>
            <p class="text-sm text-zinc-500 mt-1">Warehouse Management System</p>
        </div>
        <div class="glass rounded-2xl p-8 border border-white/[0.08]">
            <h2 class="text-lg font-semibold text-white mb-6">Masuk ke Akun</h2>
            @if($errors->any())
                <div class="mb-4 rounded-xl bg-red-500/10 border border-red-500/20 px-4 py-3 text-sm text-red-300">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-medium text-zinc-300">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-xl bg-white/[0.06] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-500 focus:border-cyan-500/50 focus:outline-none focus:ring-2 focus:ring-cyan-500/20" placeholder="admin@afnalink.my.id">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-300">Password</label>
                    <input type="password" name="password" required class="mt-2 w-full rounded-xl bg-white/[0.06] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-500 focus:border-cyan-500/50 focus:outline-none" placeholder="••••••••">
                </div>
                <label class="flex items-center gap-2 text-sm text-zinc-400">
                    <input type="checkbox" name="remember" class="rounded border-white/10 bg-white/5 text-cyan-500"> Ingat saya
                </label>
                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 py-3.5 text-sm font-semibold text-white shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/30 transition">Masuk</button>
            </form>
            <div class="mt-6 rounded-xl bg-white/[0.04] border border-white/[0.06] p-4">
                <div class="text-xs font-medium text-zinc-400 mb-2">Akun Demo:</div>
                <div class="space-y-1 text-xs font-mono">
                    <div class="flex justify-between"><span class="text-zinc-500">Admin</span><span class="text-zinc-300">admin@afnalink.my.id / password</span></div>
                    <div class="flex justify-between"><span class="text-zinc-500">Operator</span><span class="text-zinc-300">operator@afnalink.my.id / password</span></div>
                </div>
            </div>
        </div>
        <p class="text-center text-xs text-zinc-600 mt-6">© 2026 AFNALINK • Gudang System PWA</p>
    </div>
</body>
</html>
