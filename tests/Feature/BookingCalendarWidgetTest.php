<?php

declare(strict_types=1);

use App\Filament\Tenant\Widgets\BookingCalendarWidget;

// The widget is intentionally hidden from the auto-discovered dashboard widgets
// (canView() === false) yet is still rendered directly via @livewire() on the
// CalendarPage. Access there is guarded by CalendarPage::canAccess(). This test
// pins that deliberate behaviour so it is not "fixed" back to the default.
it('is hidden from the auto-discovered dashboard widgets', function (): void {
    expect(BookingCalendarWidget::canView())->toBeFalse();
});
