<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailController extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    public $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details,$id)
    {
        $this->details = $details;
        $this->id=$id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->id>=0) {
            return $this->subject('Confirmar correo electronico')
                ->view('verificar-email', ['link' => route('verification', ['id' => $this->id])]);
        }else{
            return $this->subject('Confirmar correo electronico')
                ->view('verificar-email', ['link' => route('confirmacion', ['id' => $this->id])]);
        }
    }
}
