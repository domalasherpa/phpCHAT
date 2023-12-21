<?php

/**
 * ChatBot Class
 */

 require "authKey.php";

class ChatBot
{
    /**
     * @var string The authorization token for the API
     */
    private $authorization;

    /**
     * @var string The endpoint URL for the OpenAI.com API
     */
    private $endpoint;

    /**
     * ChatBot constructor.
     */
    public function __construct()
    {
        // This token is not real, in case you were thinking what I'm thinking...
        $this->authorization = $GLOBALS["key"];
        $this->endpoint = 'https://api.openai.com/v1/chat/completions';
    }

    /**
     * Send a message to the API and return the response.
     *
     * @param string $message The message to send
     * @return string The response message
     * @throws Exception If there is an error in sending the message via cURL
     */
    public function sendMessage(string $message): string
    {
        // Read sample data from our JSON file
        $jsonSampleData = file_get_contents("test-data.json");

        // Prepare data for sending
        $data = [
            'messages' => [
                [
                    'role' => 'system',
                    // 'content' => 'You are a kind and helpful customer service member at a PC components store. 
                    // If the user asks how to buy, refer them to our website at https://medium.com/winkhosting.
                    // If the user asks anything about CPUs or RAM, use exclusively the cpu or ram input in the following JSON string to suggest options:' . $jsonSampleData
                    'content' => 'just have a cnonversation with the user'
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ],
            ],
            'model' => 'gpt-3.5-turbo'
        ];

        // Set headers for the API request
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->authorization,
        ];

        // Send the request to the API using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        // Check for errors in the API response
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('Error sending the message: ' . $error);
        }

        curl_close($ch);

        // Parse the API response
        $arrResult = json_decode($response, true);
        $resultMessage = $arrResult["choices"][0]["message"]["content"];

        // Return the response message
        return $resultMessage;
    }
}