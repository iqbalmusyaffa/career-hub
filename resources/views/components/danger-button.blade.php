<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-medium text-xs rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition shadow-xs']) }}>
    {{ $slot }}
</button>
