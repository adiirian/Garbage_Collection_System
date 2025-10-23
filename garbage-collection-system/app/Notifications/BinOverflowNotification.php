<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BinOverflowNotification extends Notification implements ShouldQueue
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
            ->subject('Bin Overflow Alert')
            ->line('The garbage bin located at ' . $this->bin->location . ' is overflowing.')
            ->action('Check Bin Status', url('/bins/' . $this->bin->id))
            ->line('Please take immediate action to resolve this issue.');
    }

    public function toArray($notifiable)
    {
        return [
            'bin_id' => $this->bin->id,
            'status' => 'overflowing',
            'location' => $this->bin->location,
        ];
    }
}