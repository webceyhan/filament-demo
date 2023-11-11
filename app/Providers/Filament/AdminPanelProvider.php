<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()            
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            // ->maxContentWidth('full')
            // ->breadcrumbs(false) // disable breadcrumbs
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop() // collapse with icons
            // ->sidebarFullyCollapsibleOnDesktop() // full collapse without icons
            ->navigationItems([
                NavigationItem::make('Source Code')
                    ->url('https://github.com/webceyhan/filament-demo', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt')
                    ->group('External Links')
                    ->sort(2)
            ])
            ->navigationGroups([ // order of groups
                'Shop',
                'Roles and Permissions',
                'External Links',
            ])
            ->userMenuItems([
                // add new item
                MenuItem::make()
                    ->label('Settings')
                    ->url('--to-be-implemented--')
                    ->icon('heroicon-o-cog-6-tooth'),
                // customize existing item
                'logout' => MenuItem::make()->label('Log Out')
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // disable default dashboard widgets
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugins([
                \Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin::make(),
                \pxlrbt\FilamentSpotlight\SpotlightPlugin::make()
            ]);
    }
}
