<?php 

class sendSMS {
    private $dbc;
    private $error_message;

    function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
	}

    public static function sendSMSNow($phoneNumber, $message){
       
        try {
            
            
           // AlvoSMS API endpoint
            $apiEndpoint = 'https://www.alvosms.in/api/v1/send';
            
            // Your AlvoSMS API token
            $token = '80pn1ahxy23j76tcdrvwb4zflisko9';
            
          
            // SMS route
            $route = 2;

            // Sender name
            $sender = 'MCHINT';
            
            // DLT Template ID (if applicable)
            $templateId = '1407170374076265542';
            
            
            // Prepare data for the API request
            $data = [
                'token' => $token,
                'numbers' => $phoneNumber,
                'route' => $route,
                'message' => $message,
                'sender' => $sender,
                'template-id' => $templateId,
            ];
            
            // Initialize cURL session
            $ch = curl_init($apiEndpoint);
            
            // Set cURL options for a POST request
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            // Execute cURL session and get the API response
            $response = curl_exec($ch);
            
            // Close cURL session
            curl_close($ch);
            
            // // Handle the API response
            // if ($response === false) {
            //     echo 'Error occurred while sending the SMS.';
            // } else {
            //     $responseData = json_decode($response, true);
                
            //     print_r($responseData);
            
            //     // Check if the API request was successful
            //     if ($responseData['status'] === 'success') {
            //         echo 'SMS sent successfully!';
            //     } else {
            //         echo 'Failed to send SMS. Error: ' . $responseData['message'];
            //     }
            // }
                                    
            
		
		} catch (Exception $e) {
			//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			// die(" I am here !");
		}

    }
}
?>