<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class DonationMessage extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $subject;
    public $type;


    public function __construct($data,$subject,$type)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->type = $type;
    }

    public function build()
    {
        $donation_details = $this->data;
        $invoice_details = PDF::loadView('invoice.donation',compact('donation_details'));

        $mail = $this->from(get_static_option('site_global_email'), get_static_option('site_title'))
                    ->subject($this->subject)
                    ->view('mail.donation-payment-success')
                    ->attachData($invoice_details->output(), "invoice.pdf");

        return $mail;
    }
}
