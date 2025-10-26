<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Pages\ViewRequest;
use Filament\Http\Middleware\Authenticate;
use App\Filament\EmployeePanel\Pages\ViewChild;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\EmployeePanel\Pages\CreateDailyReport;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class EmployeePanelPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employeePanel')
            ->path('employee')
            ->brandLogo(asset('images/logo.jpeg'))
            ->brandLogoHeight('2rem')
            ->brandName('Cognify')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->authGuard('employee')
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->discoverResources(in: app_path('Filament/EmployeePanel/Resources'), for: 'App\\Filament\\EmployeePanel\\Resources')
            ->discoverPages(in: app_path('Filament/EmployeePanel/Pages'), for: 'App\\Filament\\EmployeePanel\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                \App\Filament\EmployeePanel\Pages\CaseDashboard::class,
                \App\Filament\Pages\AllRequests::class,
                \App\Filament\Pages\ViewRequest::class,
                \App\Filament\Pages\ChildInfoPage::class,
                \App\Filament\Pages\CreateReportPage::class,
                \App\Filament\Pages\DocumentsPage::class,
                \App\Filament\Pages\TimelinePage::class,
                \App\Filament\EmployeePanel\Pages\EmployeeChildView::class,
                CreateDailyReport::class,
                ViewChild::class,


            ])
            ->discoverWidgets(in: app_path('Filament/EmployeePanel/Widgets'), for: 'App\\Filament\\EmployeePanel\\Widgets')
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
