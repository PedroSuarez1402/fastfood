<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 antialiased font-sans">

    <div class="flex min-h-screen w-full" x-data="{ sidebarOpen: false }">

        {{-- 1. SIDEBAR DESKTOP --}}
        <aside
            class="hidden lg:flex flex-col w-64 fixed inset-y-0 z-50 border-r border-zinc-200 dark:border-zinc-800 bg-zinc-200 dark:bg-zinc-900">

            {{-- Logo --}}
            <div class="flex items-center justify-center h-16 border-b border-zinc-200 dark:border-zinc-800 px-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl" wire:navigate>
                    <x-app-logo class="h-8 w-auto" />
                </a>
            </div>

            {{-- Navegación --}}
            <nav class="flex-1 overflow-y-auto p-4 space-y-6">

                {{-- Plataforma --}}
                <x-sidebar.group heading="Plataforma">
                    <x-sidebar.link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="fas fa-home" wire:navigate>
                        Dashboard
                    </x-sidebar.link>
                </x-sidebar.group>

                {{-- Administración (Protegido con @can) --}}
                @can('ver_dashboard') {{-- O un permiso general para ver menú admin --}}
                    <x-sidebar.group heading="Administración">

                        @can('gestionar_productos')
                            <x-sidebar.link href="{{ route('admin.productos.index') }}" :active="request()->routeIs('admin.productos.*')"
                                icon="fas fa-shopping-bag" wire:navigate>
                                Productos
                            </x-sidebar.link>
                        @endcan

                        @can('gestionar_pedidos')
                            <x-sidebar.link href="{{ route('admin.pedidos.index') }}" :active="request()->routeIs('admin.pedidos.*')" icon="fas fa-ticket-alt"
                                wire:navigate>
                                Pedidos
                            </x-sidebar.link>
                        @endcan

                        <x-sidebar.link href="{{ route('admin.mesas.index') }}" :active="request()->routeIs('admin.mesas.*')" icon="fas fa-th"
                            wire:navigate>
                            Mesas
                        </x-sidebar.link>
                        @can('gestionar_productos')
                            <x-sidebar.link href="{{ route('admin.ingredients.index') }}" :active="request()->routeIs('admin.ingredients.*')" icon="fas fa-boxes-stacked" wire:navigate>
                                Inventario
                            </x-sidebar.link>
                        @endcan

                        {{-- Menú Desplegable 'Gestión' --}}
                        @can('gestionar_usuarios')
                            <x-sidebar.dropdown title="Gestión" icon="fas fa-cog" :active="request()->routeIs('admin.users.*')">
                                <x-sidebar.link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" wire:navigate>
                                    Usuarios
                                </x-sidebar.link>
                                {{-- Aquí puedes agregar Roles, Permisos, etc. --}}
                                <x-sidebar.link href="{{ route('admin.roles.index') }}" :active="request()->routeIs('admin.roles.*')" wire:navigate>
                                    Roles
                                </x-sidebar.link>
                            </x-sidebar.dropdown>
                        @endcan

                    </x-sidebar.group>
                @endcan

            </nav>

            {{-- Perfil Usuario (Footer del Sidebar) --}}
            <div class="border-t border-zinc-200 dark:border-zinc-800 p-4">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-3 w-full p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-left">
                        <div
                            class="h-9 w-9 rounded-lg bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center font-bold text-sm">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <i class="fas fa-chevron-up text-xs text-zinc-400"></i>
                    </button>

                    {{-- Dropdown Usuario --}}
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute bottom-full left-0 w-full mb-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg py-1 z-50">
                        <a href="{{ route('profile.edit') }}" wire:navigate
                            class="block px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700">
                            Configuración
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- 2. SIDEBAR MOBILE (Overlay + Drawer) --}}
        <div x-show="sidebarOpen" class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="sidebarOpen = false" x-transition.opacity>
            </div>

            {{-- Drawer --}}
            <div class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-zinc-900 shadow-xl flex flex-col h-full"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">

                {{-- Cabecera del Drawer --}}
                <div class="flex justify-between items-center p-4 border-b border-zinc-200 dark:border-zinc-800">
                    <span class="font-bold text-xl">Menú</span>
                    <button @click="sidebarOpen = false"
                        class="p-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Navegación (Scrollable) --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-6">
                    {{-- Aquí repites los mismos links que en desktop --}}
                    <x-sidebar.group heading="Plataforma">
                        <x-sidebar.link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="fas fa-home"
                            wire:navigate>
                            Dashboard
                        </x-sidebar.link>
                    </x-sidebar.group>

                    @can('ver_dashboard')
                        <x-sidebar.group heading="Administración">
                            @can('gestionar_productos')
                                <x-sidebar.link href="{{ route('admin.productos.index') }}" :active="request()->routeIs('admin.productos.*')"
                                    icon="fas fa-shopping-bag" wire:navigate>
                                    Productos
                                </x-sidebar.link>
                            @endcan
                            {{-- ... resto de links ... --}}
                            @can('gestionar_pedidos')
                                <x-sidebar.link href="{{ route('admin.pedidos.index') }}" :active="request()->routeIs('admin.pedidos.*')"
                                    icon="fas fa-ticket-alt" wire:navigate>
                                    Pedidos
                                </x-sidebar.link>
                            @endcan

                            <x-sidebar.link href="{{ route('admin.mesas.index') }}" :active="request()->routeIs('admin.mesas.*')" icon="fas fa-th"
                                wire:navigate>
                                Mesas
                            </x-sidebar.link>
                            @can('gestionar_productos')
                                <x-sidebar.link href="{{ route('admin.ingredients.index') }}" :active="request()->routeIs('admin.ingredients.*')" icon="fas fa-boxes-stacked" wire:navigate>
                                    Inventario
                                </x-sidebar.link>
                            @endcan
                            @can('gestionar_usuarios')
                                <x-sidebar.dropdown title="Gestión" icon="fas fa-cog" :active="request()->routeIs('admin.users.*')">
                                    <x-sidebar.link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" wire:navigate>
                                        Usuarios
                                    </x-sidebar.link>
                                    <x-sidebar.link href="{{ route('admin.roles.index') }}" :active="request()->routeIs('admin.roles.*')" wire:navigate>
                                        Roles
                                    </x-sidebar.link>
                                </x-sidebar.dropdown>
                            @endcan
                        </x-sidebar.group>
                    @endcan
                </div>

                {{-- Footer de Usuario (ESTO ES LO QUE FALTABA) --}}
                <div class="border-t border-zinc-200 dark:border-zinc-800 p-4 bg-zinc-50 dark:bg-zinc-900">
                    <div x-data="{ userOpen: false }" class="relative">
                        <button @click="userOpen = !userOpen"
                            class="flex items-center gap-3 w-full p-2 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-800 transition-colors text-left">
                            <div
                                class="h-9 w-9 rounded-lg bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center font-bold text-sm">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            {{-- Icono que rota al abrir --}}
                            <i class="fas fa-chevron-up text-xs text-zinc-400 transition-transform duration-200"
                                :class="{ 'rotate-180': userOpen }"></i>
                        </button>

                        {{-- Dropdown Usuario (Hacia arriba) --}}
                        <div x-show="userOpen" x-collapse
                            class="mt-2 space-y-1 px-2 border-l-2 border-zinc-200 dark:border-zinc-700 ml-4">

                            <a href="{{ route('profile.edit') }}" wire:navigate
                                class="flex items-center gap-2 px-3 py-2 text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                <i class="fas fa-cog w-4"></i> Configuración
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md text-left">
                                    <i class="fas fa-sign-out-alt w-4"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 3. CONTENIDO PRINCIPAL --}}
        <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">

            {{-- Header Mobile (Solo visible en móviles) --}}
            <header
                class="lg:hidden h-16 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between px-4 bg-white dark:bg-zinc-900 sticky top-0 z-40">
                <button @click="sidebarOpen = true"
                    class="p-2 -ml-2 rounded-md text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <a href="{{ route('home') }}">
                    <x-app-logo />
                </a>
                <div class="w-8"></div> {{-- Espaciador --}}
            </header>

            {{-- Main Slot --}}
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>

    </div>

    @fluxScripts
</body>

</html>
