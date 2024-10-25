<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;

    // Update the constructor to accept both otp and name
    public function __construct($otp, $name) // Accept name as a parameter
    {
        $this->otp = $otp;
        $this->name = $name; 
    }

    public function build()
    {
        return $this->subject('Your OTP Code')
                    ->view('emails.otp')
                    ->with([
                        'otp' => $this->otp,
                        'name' => $this->name, // Pass the user's name to the view
                    ]);  
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Otp Mail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
