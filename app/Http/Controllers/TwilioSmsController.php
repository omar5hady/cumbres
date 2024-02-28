<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;
use App\Mail\birthdayCard;
use App\Mail\birthdayCardProspectos;
use Carbon\Carbon;

class TwilioSmsController extends Controller
{
    // Herramienta para administracion de mensajes
    public function sendMessage(Request $request){
        // Mail::to('0m4r5h4dy@gmail.com')->send(new birthdayCardProspectos());
        // Mail::to('omar.ramos@grupocumbres.com')->send(new birthdayCardProspectos());
        // Mail::to('omar_vazquez_7@hotmail.com')->send(new birthdayCardProspectos());
        //$this->createSMS("SMS Prueba desde SII Cumbres", "+524444605232");
        $month = Carbon::today()->format('m');
        $day = Carbon::today()->format('d');

        return [
            'day' => $day,
            'month' => $month
        ];
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
