@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs text-slate-700 dark:text-slate-300 tracking-tight']) }}>
    {{ $value ?? $slot }}
</label>
