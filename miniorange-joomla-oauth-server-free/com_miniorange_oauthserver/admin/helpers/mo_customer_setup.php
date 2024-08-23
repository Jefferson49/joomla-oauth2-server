<?php
/** Copyright (C) 2015  miniOrange

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
* @package 		miniOrange OAuth
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
/**
This library is miniOrange Authentication Service. 
Contains Request Calls to Customer service.

**/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('miniorangeoauthserver.utility.MoOAuthServerUtility');

class MoOauthServerCustomer{
	
	public $email;
	public $phone;
	public $customerKey;
	public $transactionId;

	/*
	** Initial values are hardcoded to support the miniOrange framework to generate OTP for email.
	** We need the default value for creating the OTP the first time,
	** As we don't have the Default keys available before registering the user to our server.
	** This default values are only required for sending an One Time Passcode at the user provided email address.
	*/
	
	//auth
	private $defaultCustomerKey = "16555";
	private $defaultApiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
	
	function create_customer(){
		if(!MoOAuthServerUtility::is_curl_installed()) {
			return json_encode(array("statusCode"=>'ERROR','statusMessage'=>$error . '. Please check your configuration. Also check troubleshooting under otp configuration.'));
		}
		$hostname = MoOAuthServerUtility::getHostname();
		
		$url = $hostname . '/moas/rest/customer/add';
		$ch = curl_init($url);
		$current_user =  JFactory::getUser();
		$customer_details = MoOAuthServerUtility::getCustomerDetails();
		
		$this->email = $customer_details['email'];
		$this->phone = $customer_details['admin_phone'];
		$password = $customer_details['password'];
		
		$fields = array(
			'companyName' => $_SERVER['SERVER_NAME'],
			'areaOfInterest' => 'JOOMLA OAuth Server',
			'firstname' => $current_user->name,
			'lastname' => '',
			'email' => $this->email,
			'phone' => $this->phone,
			'password' => $password
		);
		$field_string = json_encode($fields);
		
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'charset: UTF - 8',
			'Authorization: Basic'
			));
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec($ch);
		
		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   exit();
		}
		

		curl_close($ch);
		return $content;
	}

	function mo_oauth_request_for_demo($email, $plan, $description)
	 {
		$url 				=  'https://login.xecurify.com/moas/api/notify/send';
        $ch 				=	curl_init($url);   
		$customerKey 		= "16555";
		$apiKey 			= "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";

        $currentTimeInMillis= round(microtime(true) * 1000);
        $stringToHash 		= $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue 			= hash("sha512", $stringToHash);
        $customerKeyHeader 	= "Customer-Key: " . $customerKey;
        $timestampHeader 	= "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader= "Authorization: " . $hashValue;
        $phpVersion 		= phpversion();
        $fromEmail 			= $email;
        $jVersion 			= new JVersion;
		$jCmsVersion 		= $jVersion->getShortVersion();
		$moPluginVersion 	= MoOAuthServerUtility::GetPluginVersion();
        $subject            = "MiniOrange Joomla Oauth Server Request for Demo | PHP:" . $phpVersion ." | JCVersion: ". $jCmsVersion." | MOPluginVersion: ".$moPluginVersion;

        $content='<div>Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br><strong>Email :<a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a></strong><br><br><strong>Demo for plugin: '.$plan. '</strong><br><br><strong>Description: ' .$description. '</strong></div>';
        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
            'customerKey' 	=> $customerKey,
            'fromEmail' 	=> $fromEmail,                
            'fromName' 		=> 'miniOrange',
            'toEmail' 		=> 'joomlasupport@xecurify.com',
            'toName' 		=> 'joomlasupport@xecurify.com',
            'subject' 		=> $subject,
            'content' 		=> $content
            ),
        );
        $field_string = json_encode($fields);


        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
            $timestampHeader, $authorizationHeader));
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
        $content = curl_exec($ch);

        if(curl_errno($ch)){
            return json_encode(array("status"=>'ERROR','statusMessage'=>curl_error($ch)));
        }
        curl_close($ch);

        return ($content);
	}

	function get_customer_key($email,$password) {
		if(!MoOAuthServerUtility::is_curl_installed()) {
			return json_encode(array("apiKey"=>'CURL_ERROR','token'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		
		$hostname = MoOAuthServerUtility::getHostname();
		
		$url = $hostname. "/moas/rest/customer/key";
		$ch = curl_init($url);
		
		$fields = array(
			'email' => $email,
			'password' => $password
		);
		$field_string = json_encode($fields);
		
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'charset: UTF - 8',
			'Authorization: Basic'
			));
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec($ch);
		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   exit();
		}
		curl_close($ch);

		return $content;
	}
	
	public static function submit_feedback_form($email,$phone,$query)
	{
        $url =  'https://login.xecurify.com/moas/api/notify/send';
        $ch = curl_init($url);

        
        $customerKey = "16555";
		$apiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
	

        $currentTimeInMillis= round(microtime(true) * 1000);
        $stringToHash 		= $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue 			= hash("sha512", $stringToHash);
        $customerKeyHeader 	= "Customer-Key: " . $customerKey;
        $timestampHeader 	= "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader= "Authorization: " . $hashValue;
        $fromEmail 			= $email;
        $subject            = "MiniOrange Joomla Feedback for Oauth Server[Free]";
        


         $query1 =" MiniOrange joomla Server [Free] Oauth ";
         $content='<div >Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br>Phone Number :'.$phone.'<br><br><strong>Email :<a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a></strong><br><br><strong>Plugin Deactivated: '.$query1. '</strong><br><br><strong>Reason: ' .$query. '</strong></div>';


        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
                'customerKey' 	=> $customerKey,
                'fromEmail' 	=> $fromEmail,
                'fromName' 		=> 'miniOrange',
                'toEmail' 		=> 'joomlasupport@xecurify.com',
                'toName' 		=> 'joomlasupport@xecurify.com',
                'subject' 		=> $subject,
                'content' 		=> $content
            ),
        );
        $field_string = json_encode($fields);


        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
            $timestampHeader, $authorizationHeader));
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
        $content = curl_exec($ch);
        	
        if(curl_errno($ch)){
            return json_encode(array("status"=>'ERROR','statusMessage'=>curl_error($ch)));
        }
        curl_close($ch);

        return ($content);

	}
	

	function submit_contact_us( $q_email, $q_phone, $query ) {
		if(!MoOAuthServerUtility::is_curl_installed()) {
			return json_encode(array("status"=>'CURL_ERROR','statusMessage'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		$hostname = MoOAuthServerUtility::getHostname();
		$url = $hostname . "/moas/rest/customer/contact-us";
		$ch = curl_init($url);
		$current_user =  JFactory::getUser();
		$subject = "Joomla Oauth Server[Free]";
		$query = '[Joomla OAuth Server Free Plugin]: ' . $query;
		$fields = array(
			'firstName'			=> $current_user->username,
			'lastName'	 		=> '',
			'company' 			=> $_SERVER['SERVER_NAME'],
			'email' 			=> $q_email,
			'ccEmail'			=> 'joomlasupport@xecurify.com',
			'phone'				=> $q_phone,
			'subject' 			=> $subject,
			'query'				=> $query
		);
		$field_string = json_encode( $fields );
		
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'charset: UTF-8', 'Authorization: Basic' ) );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec( $ch );
		
		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   return false;
		}
		curl_close($ch);

		return true;
	}
	
	function send_otp_token($auth_type, $emailOrPhone){
		
		if(!MoOAuthServerUtility::is_curl_installed()) {
			return json_encode(array("status"=>'CURL_ERROR','statusMessage'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		
		$hostname = MoOAuthServerUtility::getHostname();
		$url = $hostname . '/moas/api/auth/challenge';
		$ch = curl_init($url);
		$customerKey =  $this->defaultCustomerKey;
		$apiKey =  $this->defaultApiKey;
		
		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round(microtime(true) * 1000);

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
		$hashValue = hash("sha512", $stringToHash);

		$customerKeyHeader = "Customer-Key: " . $customerKey;
		$timestampHeader = "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
		$authorizationHeader = "Authorization: " . $hashValue;
		if($auth_type=="EMAIL")
		{
			$fields = array(
				'customerKey' => $this->defaultCustomerKey,
				'email' => $emailOrPhone,
				'authType' => $auth_type,
				'transactionName' => 'Joomla OAuth Server'
			);
		}
		else{
			$fields = array(
				'customerKey' => $this->defaultCustomerKey,
				'phone' => $emailOrPhone,
				'authType' => $auth_type,
				'transactionName' => 'Joomla OAuth Server'
			);
		}
		
		$field_string = json_encode($fields);

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
											$timestampHeader, $authorizationHeader));
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec($ch);

		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   exit();
		}
		curl_close($ch);
		return $content;
	}

	function validate_otp_token($transactionId,$otpToken){
		if(!MoOAuthServerUtility::is_curl_installed()) {
			return json_encode(array("status"=>'CURL_ERROR','statusMessage'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		$hostname = MoOAuthServerUtility::getHostname();
		$url = $hostname . '/moas/api/auth/validate';
		$ch = curl_init($url);

		$customerKey =  $this->defaultCustomerKey;
		$apiKey =  $this->defaultApiKey;

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round(microtime(true) * 1000);

		/* Creating the Hash using SHA-512 algorithm */
		$stringToHash = $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
		$hashValue = hash("sha512", $stringToHash);

		$customerKeyHeader = "Customer-Key: " . $customerKey;
		$timestampHeader = "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
		$authorizationHeader = "Authorization: " . $hashValue;

		$fields = '';

			//*check for otp over sms/email
			$fields = array(
				'txId' => $transactionId,
				'token' => $otpToken,
			);

		$field_string = json_encode($fields);

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
											$timestampHeader, $authorizationHeader));
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec($ch);

		if(curl_errno($ch)){
			echo 'Request Error:' . curl_error($ch);
		   exit();
		}
		curl_close($ch);
		return $content;
	}
	
	function check_customer($email) {
		if(!MoOAuthServerUtility::is_curl_installed()) {
			return json_encode(array("status"=>'CURL_ERROR','statusMessage'=>'<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.'));
		}
		$hostname = MoOAuthServerUtility::getHostname();
		$url = $hostname . "/moas/rest/customer/check-if-exists";
		$ch 	= curl_init( $url );
		
		$fields = array(
			'email' 	=> $email,
		);
		$field_string = json_encode( $fields );

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'charset: UTF - 8', 'Authorization: Basic' ) );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		$content = curl_exec( $ch );
		if( curl_errno( $ch ) ){
			echo 'Request Error:' . curl_error( $ch );
			exit();
		}
		curl_close( $ch );

		return $content;
	}
}?>
