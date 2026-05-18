<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Request Product Improvements
        </x-slot>

        <x-slot name="description">
            Share ideas that help your clinic. Requests are reviewed by the platform team and tracked below.
        </x-slot>

        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>
