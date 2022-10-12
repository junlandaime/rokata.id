<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerRegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $customer;
    protected $randowPassword;
    
    public function __construct(Customer $customer, $randowPassword)
    {
        $this->customer = $customer;
        $this->randowPassword = $randowPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verifikasi Pendaftaran Anda')->view('emails.register')->with([
            'customer' => $this->customer,
            'password' => $this->randowPassword
        ]);
    }
}
