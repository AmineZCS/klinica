<x-filament::section>
    <x-slot name="heading">
        Tenant Usage Breakdown
    </x-slot>

    <x-slot name="description">
        Real usage metrics per clinic across bookings, services, employees, and landing visits.
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Tenant</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Plan</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Bookings</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Services</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Employees</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Visits (30d)</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Visits (all)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($rows as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $row['tenant_name'] }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $row['tenant_id'] }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $row['plan'] }}</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ number_format((int) $row['bookings_count']) }}</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ number_format((int) $row['services_count']) }}</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ number_format((int) $row['employees_count']) }}</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ number_format((int) $row['visits_last_30_days']) }}</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ number_format((int) $row['visits_total']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            No tenant usage data available yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::section>
