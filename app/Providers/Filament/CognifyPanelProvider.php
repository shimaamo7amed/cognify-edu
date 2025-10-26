<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\App;
use Filament\Support\Enums\MaxWidth;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Admin\Pages\Dashboard;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class CognifyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('cognify')
            ->path('cognifyAdmin')
            ->brandLogo(asset('images/logo.jpeg'))
            ->brandLogoHeight('2rem')
            ->brandName('Cognify')
            ->login(\App\Filament\Pages\Auth\Login::class)
            // ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
            MenuItem::make()
                ->label(fn () => App::getLocale() === 'ar' ? 'English' : 'العربية')
                ->url(fn () => route('change-language', ['lang' => App::getLocale() === 'ar' ? 'en' : 'ar']))
                ->icon('heroicon-o-globe-alt')
        ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Dashboard::class,
                \App\Filament\Pages\OverviewPage::class,
                \App\Filament\Pages\AllRequests::class,
                \App\Filament\Pages\ViewRequest::class,
                \App\Filament\Pages\ChildInfoPage::class,
                \App\Filament\Pages\CreateReportPage::class,
                \App\Filament\Pages\DocumentsPage::class,
                \App\Filament\Pages\TimelinePage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetLocale::class,
                \App\Http\Middleware\AuthBothGuards::class,
            ])
            ->authGuard('admin')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            // ->topNavigation()
            ->maxContentWidth(MaxWidth::Full);
    }
}
