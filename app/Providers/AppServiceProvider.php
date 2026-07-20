<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $signedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(config('auth.verification.expire', 120)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            $query = parse_url($signedUrl, PHP_URL_QUERY);

            return sprintf(
                '%s/verify-email?id=%s&hash=%s&%s',
                rtrim(config('app.frontend_url'), '/'),
                $notifiable->getKey(),
                sha1($notifiable->getEmailForVerification()),
                $query
            );
        });
    }
}
