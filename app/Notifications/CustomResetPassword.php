<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    use Queueable;
    public $token;

    public function __construct($token)
    {
        $this->token = $token; // Nangkep token rahasia dari Laravel
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Bikin URL link buat di-klik di email
        $url = url('/reset-password/'.$this->token.'?email='.urlencode($notifiable->email));

        return (new MailMessage)
            ->subject('Permintaan Ganti Password - OUTFITOLOGY') // Ini buat ngubah "Reset Password Notification"
            ->view('emails.custom_reset', ['url' => $url]); // Ini buat manggil tampilan custom kita
    }
}