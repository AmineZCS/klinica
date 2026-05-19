<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;

class CalendarPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected string $view = 'filament.tenant.pages.calendar-page';

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user !== null && (
            (bool) ($user->is_super_admin ?? false)
            || ($user->hasPermissionTo('view_bookings', 'tenant') ?? false)
        );
    }

    public static function getNavigationLabel(): string
    {
        return __('klinica.calendar.nav_label');
    }
}
