<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-slate-900 border border-slate-900 rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:bg-slate-800 focus:bg-slate-800 active:bg-slate-950 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:ring-offset-2 transition ease-in-out duration-150 shadow-2xs']) }}>
    {{ $slot }}
</button>
