<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //DAN KIRIM EMAIL DENGAN SUBJECT BERIKUT
        //TEMPLATE YANG DIGUNAKAN ADALAH ORDER.BLADE.PHP YANG ADA DI FOLDER EMAILS
        //DAN PASSING DATA ORDER KE FILE ORDER.BLADE.PHP
        return $this->subject('Pesanan Anda Dikirim' . $this->order->invoice)->view('emails.order')->with(['order' => $this->order]);
    }
}
