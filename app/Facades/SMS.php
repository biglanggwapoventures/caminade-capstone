<?php
namespace App\Facades;

class SMS
{
    const URL = 'https://www.itexmo.com/php_api/api.php';

    protected $recipient;
    protected $message;

    public static $apiResponses = [
        '1' => 'Invalid Number',
        '2' => 'Number prefix not supported. Please contact us so we can add.',
        '3' => 'Invalid ApiCode.',
        '4' => 'Maximum Message per day reached. This will be reset every 12MN.',
        '5' => 'Maximum allowed characters for message reached.',
        '6' => 'System OFFLINE.',
        '7' => 'Expired ApiCode.',
        '8' => 'iTexMo Error. Please try again later',
        '9' => 'Invalid Function Parameters',
        '10' => 'Recipient\'s number is blocked due to FLOODING, message was ignored.',
        '11' => 'Recipient\'s number is blocked temporarily due to HARD sending (after 3 retries of sending and message still failed to send) and the message was ignored. Try again after an hour',
        '12' => 'Invalid request. You can\'t set message priorities on non corporate apicodes',
        '13' => 'Invalid or Not Registered Custom Sender ID',
        '0' => 'Success! Message is now on queue and will be sent soon.',
    ];

    public function __construct($recipient, $message = null)
    {
        $this->recipient = $recipient;
        $this->message = $message;
    }

    private function requestBody()
    {
        return http_build_query([
            '1' => $this->recipient,
            '2' => $this->message,
            '3' => config('app.itexmo_api_code'),
        ]);
    }

    private function requestMethod()
    {
        return 'POST';
    }

    private function requestHeader()
    {
        return "Content-type: application/x-www-form-urlencoded\r\n";
    }

    private function createStreamContext()
    {
        return stream_context_create([
            'http' => [
                'header' => $this->requestHeader(),
                'method' => $this->requestMethod(),
                'content' => $this->requestBody(),
            ],
        ]);
    }

    public function send($message = null)
    {
        $this->message = $message ?: $this->message;
        $responseCode = file_get_contents(self::URL, false, $this->createStreamContext());

        if ($responseCode != 0) {
            return self::$apiResponses[$responseCode];
            // $body = json_encode([$this->recipient, $this->message, config('app.itexmo_api_code')]);
            // throw new \Exception(self::$apiResponses[$responseCode] . ": {<1></1>body}", $responseCode);
        }

        return true;
    }
}
