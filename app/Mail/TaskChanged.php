<?php

namespace App\Mail;

use App\Models\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskChanged extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Task $task, public string $mailView)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(
            address:config(key:'mail.from.address'),
            name:config('mail.from.name')
        )->markdown(view:$this->mailView);
    }
}
