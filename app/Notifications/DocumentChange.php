<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentChange extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $document;
    protected $type;

    public function __construct(Document $document, $type)
    {
        $this->document = $document;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = '';
        $message = '';

        if ($this->type === 'updated') {
            $subject = 'Изменен документ: ' . $this->document->name;
            $message = 'Документ был изменен.';
        } elseif ($this->type === 'deleted') {
            $subject = 'Удален документ: ' . $this->document->name;
            $message = 'Документ был удален.';
        }

        return (new MailMessage)
            ->subject($subject)
            ->line($message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
