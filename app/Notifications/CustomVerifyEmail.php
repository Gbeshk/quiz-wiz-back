<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    protected function verificationUrl($notifiable): string
    {
        $hash = sha1($notifiable->getEmailForVerification());

        $backendUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => $hash,
            ]
        );

        $frontendUrl = rtrim(config('app.frontend_url', 'http://localhost:5173'), '/');
        $query = parse_url($backendUrl, PHP_URL_QUERY);
        $path = "{$frontendUrl}/email/verify/{$notifiable->getKey()}/{$hash}";

        return $query ? "{$path}?{$query}" : $path;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->view('emails.verify', [
                'url' => $this->verificationUrl($notifiable),
                'user' => $notifiable,
            ]);
    }
}
