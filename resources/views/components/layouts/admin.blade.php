@props(['active' => null, 'title' => __('general.dashboard')])

<x-layouts.app>
    <x-layouts.dash-admin :active="$active" :title="$title">
        {{ $slot }}
    </x-layouts.dash-admin>
</x-layouts.app>
