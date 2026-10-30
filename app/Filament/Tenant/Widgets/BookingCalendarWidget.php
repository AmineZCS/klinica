<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Widgets;

use App\Filament\Tenant\Resources\BookingResource;
use App\Models\Booking;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class BookingCalendarWidget extends CalendarWidget
{
    /**
     * Hide from the auto-discovered dashboard widgets list,
     * but do NOT abort when directly rendered via @livewire() on CalendarPage.
     */
    public static function canView(): bool
    {
        return false;
    }

    /**
     * Override Filament's widget access boot hook to suppress the built-in
     * abort_unless(static::canView(), 403) — canView() is false only to hide
     * this widget from the auto-discovered dashboard. The widget is rendered
     * directly on CalendarPage, which guards access via CalendarPage::canAccess();
     * here we only require an authenticated tenant user.
     */
    public function bootCanAuthorizeAccess(): void
    {
        abort_unless(auth('tenant')->check(), 403);
    }

    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    protected string|HtmlString|bool|null $heading = 'Bookings Calendar';

    protected bool $eventClickEnabled = true;

    protected bool $eventDragEnabled = false;

    protected bool $eventResizeEnabled = false;

    protected function getEvents(FetchInfo $info): Collection|array|Builder
    {
        return Booking::query()
            ->with(['patient', 'service', 'employee'])
            ->where('start_at', '>=', $info->start->toDateTimeString())
            ->where('start_at', '<=', $info->end->toDateTimeString())
            ->get();
    }

    protected function onEventClick(EventClickInfo $info, Model $event, ?string $action = null): void
    {
        $this->redirect(BookingResource::getUrl('edit', ['record' => $event->getKey()]));
    }
}
