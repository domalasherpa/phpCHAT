<?php

// Include the definition of the ChatBot class to be used for our query.
require("ChatBot.php");

// Decode the parameters received from the index.html file and store them in the $paramsFetch array.

$paramsFetch = json_decode(
    file_get_contents("php://input"),
    true
);


$ChatBot = new ChatBot();

// Send the message to our AI.
$resMessage = $ChatBot->sendMessage($paramsFetch["message"]);

// Next, we return the response in JSON format and exit the execution.
$jsonResponse = json_encode(array("responseMessage" => $resMessage));
echo $jsonResponse;

exit;