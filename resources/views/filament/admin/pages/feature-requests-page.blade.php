<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Tenant Feature Requests
        </x-slot>

        <x-slot name="description">
            Review, prioritize, and track requests submitted by clinics.
        </x-slot>

        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>
