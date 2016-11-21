<?php

/**
 * Webhook for Foodtaps Menu Bot - Facebook Messenger Bot
 * User : Saatvik Shukla <me@saatvikshukla.com>
 * Date: 20/11/2016
 * Time: 05.39 PM IST
 */

$access_token = "";
$verify_token = "fb_time_bot";
$hub_verify_token = null;

if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}

if ($hub_verify_token === $verify_token) {
    echo $challenge;
}

$input = json_decode(file_get_contents('php://input'), true);

$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];

$message_to_reply = '';

/**
 * Some Basic rules to validate incoming messages
 */
$helloMessage = array("Hey! :)","Hello!","Hi!","Namaste!","Hello!\u000AHow can I help you?");

if(preg_match('[hi|hello|hey|heyy|heyy|namaste|yo|sup|halo|hallo]', strtolower($message))) {

    $message_to_reply = $helloMessage[rand(0,3)];

} elseif(preg_match('[help]', strtolower($message))) {

    // Reply with a general Help 
    $message_to_reply = "I am Foodtaps Menu Bot\u000AI can tell you about the day's Dinner Menu.\u000AI am currently configured for the Kitchen 'Daawat' and will be able to help you with other kitchens in the coming future.\u000A";

} elseif (preg_match('[menu|dinner]', strtolower($message))) {
    
    // Execute foodtaps script and fetch details
    include "./fetch_dinner.php";
    $message_to_reply = $finalDinner;

} elseif (preg_match('[thanks|thank|cool|nice|great|sweet|neat]', strtolower($message))) {
    
    $message_to_reply = "I'm glad I could help! :)";

} elseif (preg_match('[morning|mornin|gm]', strtolower($message))) {
    
    $message_to_reply = "Good morning!";

} elseif (preg_match('[night|gn]', strtolower($message))) {
    
    $message_to_reply = "Good morning!";

} elseif(preg_match('[kemcho]', strtolower($message))) {

    $message_to_reply = "Majaama ^_^";
    
} elseif(preg_match('[about]', strtolower($message))) {

    $message_to_reply = "I am a bot, duh.\u000A[Unofficial] Representative of Foodtaps. \u000ASupporter of khaleesi and her dragons, A fan of Tyrion and Protector of the dinner menu. :)";
    
} else {
    $message_to_reply = "I am sorry, I can't understand you.\u000ATry 'help' for assistance." ;
}

//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;

//Initiate cURL.
$ch = curl_init($url);

//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';

//Encode the array into JSON.
$jsonDataEncoded = $jsonData;

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}