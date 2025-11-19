<div class="w-full space-y-6">

    {{-- Header --}}
    <flux:header>
        <flux:heading size="xl" weight="semibold" class="px-6">
            {{ __('Mesas') }}
        </flux:heading>

        <flux:button href="{{ route('admin.mesas.create') }}" icon="plus" >
            Nueva Mesa
        </flux:button>
    </flux:header>

    {{-- Plano de mesas --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">

        @foreach ($mesas as $mesa)
            <div class="
                relative p-4 rounded-2xl border shadow-sm transition hover:scale-105 cursor-pointer
                {{ $mesa->estado === 'ocupada' 
                    ? 'bg-red-100 dark:bg-red-900/40 border-red-400' 
                    : 'bg-emerald-100 dark:bg-emerald-900/40 border-emerald-400'
                }}
            ">
                {{-- Estado --}}
                <span class="
                    absolute top-2 right-2 text-xs px-2 py-0.5 rounded-full
                    {{ $mesa->estado === 'ocupada' 
                        ? 'bg-red-600 text-white' 
                        : 'bg-emerald-600 text-white'
                    }}
                ">
                    {{ ucfirst($mesa->estado) }}
                </span>

                {{-- Número de mesa --}}
                <div class="text-center mt-2">
                    <div class="text-3xl font-bold">
                        {{ $mesa->numero }}
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-300">
                        Capacidad: {{ $mesa->capacidad }}
                    </div>
                </div>

                {{-- Botón editar --}}
                <div class="mt-4 flex justify-center">
                    <flux:button 
                        variant="subtle" 
                        icon="pencil"
                        href="{{ route('admin.mesas.edit', $mesa) }}"
                        class="w-full"
                    >
                        Editar
                    </flux:button>
                </div>
            </div>
        @endforeach

    </div>

</div>
