@if (session()->has('success'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 4000)" {{-- Se oculta automáticamente a los 4 segundos --}}
         x-show="show"
         {{-- Transiciones suaves para aparecer/desaparecer --}}
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         {{-- Diseño (Estilo Tailwind Emerald/Zinc) --}}
         class="fixed bottom-5 right-5 z-50 flex items-center p-4 mb-4 text-emerald-800 bg-white border border-emerald-200 rounded-lg shadow-lg dark:text-emerald-400 dark:bg-zinc-800 dark:border-emerald-900"
         role="alert">
        
        {{-- Ícono de Check --}}
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg dark:bg-emerald-800 dark:text-emerald-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            <span class="sr-only">Check icon</span>
        </div>
        
        {{-- Mensaje --}}
        <div class="ml-3 text-sm font-normal mx-2">
            {{ session('success') }}
        </div>
        
        {{-- Botón Cerrar (X) --}}
        <button type="button" @click="show = false" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-zinc-800 dark:hover:bg-zinc-700">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
@endif