<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\App;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Filament\Actions\Action as FilamentAction;

class Login extends BaseLogin
{
    protected static ?string $configPath = 'filament/login';

    public function getTitle(): string
    {
        return __('filament/login.title');
    }

    public function getHeading(): string
    {
        return __('filament/login.heading');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction()->label(__('filament/login.buttons.submit.label')),
        ];
    }

    protected function getEmailFormComponentLabel(): string
    {
        return __('filament/login.fields.email.label');
    }

    protected function getPasswordFormComponentLabel(): string
    {
        return __('filament/login.fields.password.label');
    }

    protected function getHeaderWidgets(): array
    {
        FilamentView::registerRenderHook(
            'panels::auth.login.form.before',
            fn(): string => new HtmlString('
                <div class="flex justify-end mb-4">
                    <div class="relative">
                        <button id="languageButton" class="flex items-center space-x-2 text-primary-600 hover:text-primary-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                            <span>' . (App::getLocale() === 'ar' ? 'العربية' : 'English') . '</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="languageDropdown" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1" role="menu">
                                <a href="' . route('change-language', ['lang' => 'ar']) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">العربية</a>
                                <a href="' . route('change-language', ['lang' => 'en']) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">English</a>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.getElementById("languageButton").addEventListener("click", function() {
                        document.getElementById("languageDropdown").classList.toggle("hidden");
                    });

                    document.addEventListener("click", function(event) {
                        if (!event.target.closest("#languageButton")) {
                            document.getElementById("languageDropdown").classList.add("hidden");
                        }
                    });
                </script>
            '),
        );

        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            FilamentAction::make('language')
                ->label(App::getLocale() === 'ar' ? 'English' : 'العربية')
                ->url(route('change-language', ['lang' => App::getLocale() === 'ar' ? 'en' : 'ar']))
                ->icon('heroicon-o-globe-alt')
                ->color('primary'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('language')
                ->label('اللغة / Language')
                ->options([
                    'ar' => 'العربية',
                    'en' => 'English',
                ])
                ->default(App::getLocale())
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    redirect()->route('change-language', ['lang' => $state]);
                }),
            ...parent::getFormSchema(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        FilamentView::registerRenderHook(
            'panels::body.start',
            fn (): string => '
                <div class="fixed top-4 right-4 flex items-center gap-2">
                    <a href="' . route('change-language', ['lang' => 'ar']) . '"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition ' .
                       (App::getLocale() === 'ar' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100') . '">
                        العربية
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="' . route('change-language', ['lang' => 'en']) . '"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition ' .
                       (App::getLocale() === 'en' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100') . '">
                        English
                    </a>
                </div>
            '
        );
    }

    protected function getAuthGuard(): string
    {
        return 'admin';
    }
}