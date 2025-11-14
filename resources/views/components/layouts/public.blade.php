<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen p-4 flex flex-col items-center">

    <header class="w-full max-w-4xl mb-6">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4 text-sm">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] rounded-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] rounded-sm">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="w-full max-w-4xl">
        {{ $slot }}
        @fluxScripts
    </main>

    @livewireScripts
</body>


</html>
