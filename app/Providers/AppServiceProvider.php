<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interfaces\JobRepositoryInterface::class,
            \App\Repositories\JobRepository::class
        );

        $this->app->bind(
            \App\Interfaces\ApplicationRepositoryInterface::class,
            \App\Repositories\ApplicationRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_starts_with(config('app.url'), 'https://') || app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Pagination\Paginator::useTailwind();

        \Illuminate\Support\Facades\RateLimiter::for('api', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') || $user->hasRole('HR') || $user->hasRole('Company Owner') ? true : null;
        });

        \Illuminate\Validation\Rules\Password::defaults(function () {
            return \Illuminate\Validation\Rules\Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

        // Dynamic SMTP Configuration from Web System Settings
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
                $host = \App\Models\SystemSetting::getByKey('mail_host');
                if ($host) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $host,
                        'mail.mailers.smtp.port' => \App\Models\SystemSetting::getByKey('mail_port', 587),
                        'mail.mailers.smtp.encryption' => \App\Models\SystemSetting::getByKey('mail_encryption', 'tls'),
                        'mail.mailers.smtp.username' => \App\Models\SystemSetting::getByKey('mail_username'),
                        'mail.mailers.smtp.password' => \App\Models\SystemSetting::getByKey('mail_password'),
                        'mail.from.address' => \App\Models\SystemSetting::getByKey('mail_from_address', 'noreply@talentflow.com'),
                        'mail.from.name' => \App\Models\SystemSetting::getByKey('mail_from_name', 'TalentFlow System'),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Ignore during initial migrations or build
        }
    }
}
