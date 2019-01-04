<?php
//#########################################################################################################
// API key and secret configuration
require_once '../vendor/autoload.php';
$client = new Mocean\Client(new Mocean\Client\Credentials\Basic('MOCEAN_API_KEY', 'MOCEAN_API_SECRET'));

//#########################################################################################################
// Send MT SMS

$message = $client->message()->send([
    'mocean-to'   => 'MOCEAN_TO',
    'mocean-from' => 'MOCEAN_FROM',
    'mocean-text' => 'Test message from the Mocean PHP Client',
]);

echo $message; exit;

//#########################################################################################################
// Send Flash SMS

$message = $client->message()->send([
    'mocean-to'     => 'MOCEAN_TO',
    'mocean-from'   => 'MOCEAN_FROM',
    'mocean-mclass' => 1,
    'mocean-text'   => 'Test flash message from the Mocean PHP Client',
]);
echo $message; exit;

//#########################################################################################################
// Search SMS Status

$message = $client->message()->search([
    'mocean-msgid'       => 'MOCEAN_MESSAGE_ID',
    'mocean-resp-format' => 'json',
]);

echo $message; exit;

//#########################################################################################################
// Receive DLR

$message = $client->message()->receiveDLR();

echo $message; exit;

//#########################################################################################################
// Get Account Pricing

$message = $client->account()->getPricing([
    'mocean-resp-format' => 'json',
    'mocean-mcc'         => 'MOCEAN_MCC',
    'mocean-mnc'         => 'MOCEAN_MNC',
    'mocean-delimiter'   => ';',
]);
echo $message; exit;

//#########################################################################################################
// Get Account Balance

$message = $client->account()->getBalance([
    'mocean-resp-format' => 'json',
]);

echo $message; exit;

//#########################################################################################################
// Request Verify

$message = $client->verify()->start([
    'mocean-to'    => 'MOCEAN_TO',
    'mocean-brand' => 'MOCEAN_BRAND',
]);

echo $message; exit;

//#########################################################################################################
// Check Verify

$message = $client->verify()->check([
    'mocean-reqid' => 'MOCEAN_REQUEST_ID',
    'mocean-code'  => 'MOCEAN_CODE',
]);

echo $message; exit;

?>

