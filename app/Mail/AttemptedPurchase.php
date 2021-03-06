<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AttemptedPurchase extends Mailable
{
    use Queueable, SerializesModels;

    public $trades;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($trades)
    {
        $this->trades = $trades;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->trades[0]->account->refreshMoney();

        $sum = 0.00;
        foreach($this->trades as $td) {
            $sum += $td->total;
        }

        $subject = count($this->trades) . " Orders: $" . $sum;  
        return $this->markdown('emails.trades.purchase')
                    ->subject($subject);
    }
}
