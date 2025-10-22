<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Clipzo Manager</title>
    <link rel="icon" href="{{ asset('clipzo-manager.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-black">

    <div class="min-h-screen grid lg:grid-cols-2">

        <div class="hidden lg:flex flex-col items-center justify-center bg-black text-white p-12">
            <h1 class="text-4xl font-bold tracking-wider">Clipzo Manager</h1>
            <p class="mt-2 text-neutral-300 text-lg">All in one Transaction Manager for Clipzo.</p>
        </div>

        <div class="flex flex-col items-center justify-center p-8 sm:p-12">
            <div class="w-full max-w-md space-y-8">

                <div>
                    <img class="mx-auto h-12 w-auto" src="{{ asset('clipzo-manager.ico') }}" alt="Clipzo Manager">
                </div>

                <div class="text-center">
                    <h1 class="text-3xl font-bold">Selamat Datang</h1>
                    <p class="mt-2 text-neutral-600">Silahkan log in untuk melanjutkan.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Username --}}
                    <div class="relative">
                        <label for="username" class="block text-sm text-neutral-600 mb-1">Username</label>
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            value="{{ old('username') }}" 
                            placeholder="Masukan username"
                            class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-black focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition duration-300 @error('username') border-red-500 @enderror"
                            required>
                        @error('username')
                            <span class="text-red-600 text-sm font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="block text-sm text-neutral-600 mb-1">Password</label>
                        <div style="position: relative;">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                placeholder="Masukan password"
                                style="width: 100%; padding-right: 2.5rem; padding-left: 0.75rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.5rem;"
                                required>
                            <button type="button"
                                onclick="togglePassword()"
                                style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 0; color: #6B7280;">
                                <!-- Eye Open Icon -->
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-600 text-sm font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button type="submit" class="w-full bg-black text-white font-bold py-3 rounded-lg hover:bg-white hover:text-black border-2 border-transparent hover:border-black transition duration-300 ease-in-out">
                            Login
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }
</script>
</html>