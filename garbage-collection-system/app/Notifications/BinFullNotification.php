<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BinFullNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $bin;

    public function __construct($bin)
    {
        $this->bin = $bin;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bin Full Alert')
            ->line('The bin located at ' . $this->bin->location . ' is full.')
            ->action('Check Bin', url('/bins/' . $this->bin->id))
            ->line('Thank you for keeping our community clean!');
    }

    public function toArray($notifiable)
    {
        return [
            'bin_id' => $this->bin->id,
            'status' => 'full',
            'location' => $this->bin->location,
        ];
    }
}