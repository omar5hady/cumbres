<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;


class TwilioSmsController extends Controller
{
    public function sendMessage(Request $request){
        $this->createSMS("SMS Prueba desde SII Cumbres", "+524444605232");
    }

    private function createSMS($message, $recipients)
    {
        $account_sid = getenv("TWILIO_ACCOUNT_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        try{
            $client->messages->create($recipients, ['from' => $twilio_number, 'body' => $message]);
        }
        catch (Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }


        // $sid = getenv("TWILIO_ACCOUNT_SID");
        // $token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages
        //                 ->create("whatsapp:".$recipients, // to
        //                         [
        //                             "from" => "whatsapp:+14155238886",
        //                             "body" => "Prueba Whatsapp desde SiiCumbres!"
        //                         ]
        //                 );

        // print($message->sid);


    }
}
