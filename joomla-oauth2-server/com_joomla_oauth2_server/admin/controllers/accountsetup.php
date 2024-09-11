<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauthserver
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */ 
/**
 * AccountSetup Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauthserver
 * @since       0.0.9
 */
defined('_JEXEC') or die('Restricted access');
jimport('miniorangeoauthserver.utility.MoOAuthServerUtility');

class miniorangeoauthserverControllerAccountSetup extends JControllerForm
{
	function __construct()
	{
		$this->view_list = 'accountsetup';
		parent::__construct();
	}
	function customerLoginForm(){
	
		 // Fields to update.
		$fields = array(
			'login_status'=> 1,
			'password' => '',
			'email_count' => 0,
			'sms_count' => 0,
		);
		 
		// Conditions for which records should be updated.
		$conditions = array(
			'id'=> 1
		);
		 
		
		MoOAuthServerUtility::generic_update_query('#__miniorange_oauthserver_customer', $fields,$conditions);
		
		$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account');
	}

	function updateToken()
	{
		$post=	JFactory::getApplication()->input->post->getArray();
		if(isset($post['mo_server_token_length']))
		{			
		 	// Fields to update.
			$tokenLength=$post['mo_server_token_length'];
			$fields = array(
				'token_length' => $tokenLength,
			);
			// Conditions for which records should be updated.
			$conditions = array(
				'id' => 1
			);
			MoOAuthServerUtility::generic_update_query('#__miniorange_oauthserver_config', $fields,$conditions);
		}
		$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=advancesettings','Setting saved successfully');
	}


	 function verifyCustomer()
	{
	$post=	JFactory::getApplication()->input->post->getArray(); 
		
		$email = '';
		$password = '';
		
		if( MoOAuthServerUtility::check_empty_or_null( $post['email'] ) ||MoOAuthServerUtility::check_empty_or_null( $post['password'] ) ) {
			JFactory::getApplication()->enqueueMessage( 4711, 'All the fields are required. Please enter valid entries.' );
			return;
		} else{
			$email =$post['email'];
			$password =  $post['password'] ;
		}
		
		$customer = new MoOauthServerCustomer();
		$content = $customer->get_customer_key($email,$password);
		
		$customerKey = json_decode( $content, true );
		if( strcasecmp( $customerKey['apiKey'], 'CURL_ERROR') == 0) {
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account',$customerKey['token'],'error');
		} else if( json_last_error() == JSON_ERROR_NONE ) {
			if(isset($customerKey['id']) && isset($customerKey['apiKey']) && !empty($customerKey['id']) && !empty($customerKey['apiKey'])){
				$this->save_customer_configurations($email,$customerKey['id'], $customerKey['apiKey'], $customerKey['token'],$customerKey['phone']);
				$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license','Your account has been retrieved successfully.');
			}else{
				$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup','There was an error in fetching your details. Please try again.','error');
			}
		} else {
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account','Invalid username or password. Please try again.','error');
		}		
	}

 function moOAuthRequestForDemoPlan()
    {
        $post=	JFactory::getApplication()->input->post->getArray();
        
        if(count($post)==0){
            $this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup');
            return;
        }
        $email          = $post['email'];
        $plan           = $post['plan'];
        $description    = $post['description'];
        $customer       = new MoOauthServerCustomer();

 
        $response = json_decode($customer->mo_oauth_request_for_demo($email, $plan, $description));

        if($response->status != 'ERROR')
            $this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=requestdemo', 'Thanks for showing intrest in Demo / Trial. Someone from our company will contact you shortly.');
        else
            $this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=requestdemo', 'An error occured, please try again.', 'error');
    }
 

    function ResetAccount()
    {
        $database_name = '#__miniorange_oauthserver_customer';
        $updatefieldsarray = array(
            'customer_key'        => '',
            'api_key'             => '',
            'customer_token'      => '',
            'admin_phone'         => '',
            'login_status'        => 0,
            'registration_status' => NULL,
            'email' => '',
        );
		$condition = array(
			'id'        => 1,
		);

        MoOAuthServerUtility::generic_update_query($database_name, $updatefieldsarray,$condition);
        $this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'Successfully Account is removed from the plugin.');
        return;
		
    }


	function save_customer_configurations($email, $id, $apiKey, $token, $phone) {
		 // Fields to update.
		$fields = array(
			'email' => $email,
			'customer_key' => $id,
			'api_key' => $apiKey,
			'customer_token' => $token,
			'admin_phone' => $phone,
			'login_status' =>0,
			'registration_status' =>'SUCCESS',
			'password' =>'',
			'email_count' => 0,
			'sms_count'=> 0,
		);
		 
		// Conditions for which records should be updated.
		$conditions = array(
			'id'=> 1
		);
		
		MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
		
	}
	
	
	function addclient()
	{
		$post=	JFactory::getApplication()->input->post->getArray();
		$client_id = miniorangeoauthserverControllerAccountSetup::generateRandomString(30);
		$client_secret= miniorangeoauthserverControllerAccountSetup::generateRandomString(30);
		$authorized_uri=trim($post['mo_oauth_client_redirect_url']," ");
		// Fields to update.
		$fields = array(
			'client_name' => $post['mo_oauth_custom_client_name'],
			'client_id'=>$client_id,
			'client_secret' => $client_secret,
			'authorized_uri' => $authorized_uri,
			'client_count' =>1
		);
			 
		// Conditions for which records should be updated.
		$conditions = array(
			'id' => 1
		);

		MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_config", $fields,$conditions);
		
		$this->setRedirect('index.php?option=com_miniorange_oauthserver&tab-panel=configuration&pa=2', 'Client  has been added successfully.');	
	}
	
	function deleteclient(){
			
			 // Fields to update.
			$fields = array(
				'client_name' =>NULL,
				'client_id' =>NULL,
				'client_secret' =>NULL,
				'authorized_uri' =>NULL,
				'client_count' =>0,
				
			);
			 
			// Conditions for which records should be updated.
			$conditions = array(
				'id'=> 1
			);
			MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_config", $fields,$conditions);
			
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&tab-panel=configuration');
		
	}
		
		
		function generateRandomString($length=30) {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	function updateclient()
	{
		$post=	JFactory::getApplication()->input->post->getArray();		
		$authorized_uri=trim($post['mo_oauth_client_redirect_url']," ");
	    // Fields to update.
		$fields = array(
			'authorized_uri'=>$authorized_uri,
		);
		// Conditions for which records should be updated.
		$conditions = array(
			'id' => 1
		);
		MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_config", $fields,$conditions);

		$this->setRedirect('index.php?option=com_miniorange_oauthserver&tab-panel=configuration&pa=2', 'Client has been updated successfully.');
	}
	
	function registerCustomer(){
		//validate and sanitize
		$email = '';
		$phone = '';
		$password = '';
		$confirmPassword = '';
		$password = (JFactory::getApplication()->input->post->getArray()["password"]); 
		$confirmPassword = (JFactory::getApplication()->input->post->getArray()["confirmPassword"]); 
		$email=(JFactory::getApplication()->input->post->getArray()["email"]); 
		if( MoOAuthServerUtility::check_empty_or_null( $email ) || MoOAuthServerUtility::check_empty_or_null($password ) || MoOAuthServerUtility::check_empty_or_null($confirmPassword ) ) {
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account',  'All the fields are required. Please enter valid entries.','error');
			return;
		} else if( strlen( $password ) < 6 || strlen( $confirmPassword ) < 6){	//check password is of minimum length 6
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account',  'Choose a password with minimum length 6.','error');
			return;
		} else{			
			$email = JFactory::getApplication()->input->post->getArray()["email"];
			$email = strtolower($email);
			$phone = JFactory::getApplication()->input->post->getArray()["phone"];
			$password =JFactory::getApplication()->input->post->getArray()["password"];
			$confirmPassword = JFactory::getApplication()->input->post->getArray()["confirmPassword"];
		}	
		if( strcmp( $password, $confirmPassword) == 0 ) 
		{	
			 // Fields to update.
			$fields = array(
				'email' =>$email,
				'admin_phone' => $phone,
				'password' => $password,		
			);
			// Conditions for which records should be updated.
			$conditions = array(
				'id' => 1
			);
			MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
			$customer = new MoOauthServerCustomer();
			$content = json_decode($customer->check_customer($email), true);
			if( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND') == 0 )
			{
				$auth_type = 'EMAIL';
				$content = json_decode($customer->send_otp_token($auth_type, $email), true);
				if(strcasecmp($content['status'], 'SUCCESS') == 0) 
				{
					$fields = array(
						'email_count' => 1,
						'transaction_id' => $content['txId'],
						'login_status' => 0,
						'registration_status' => 'MO_OTP_DELIVERED_SUCCESS'
					);
					// Conditions for which records should be updated.
					$conditions = array(
						'id'=> 1
					);
					MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'A One Time Passcode has been sent to <strong>' . $email . '</strong>. Please enter the OTP below to verify your email. ');
				} else {
					
					$fields = array(
						'login_status' => 0,
						'registration_status' => 'MO_OTP_DELIVERED_FAILURE'
					);
					// Conditions for which records should be updated.
					$conditions = array(
						'id'=> 1
					);
					 
					
					MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'There was an error in sending email. Please click on Resend OTP to try again. ','error');
					
					
				}
			} else if( strcasecmp( $content['status'], 'CURL_ERROR') == 0 ){
				
				$fields = array(
					'login_status' =>0,
					'registration_status' => 'MO_OTP_DELIVERED_FAILURE'
				);
				// Conditions for which records should be updated.
				$conditions = array(
					'id' => 1
				);
				 
				MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
				$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', $content['statusMessage'],'error');
				
			} else{
				$content = $customer->get_customer_key($email,$password);
				$customerKey = json_decode($content, true);
				if(json_last_error() == JSON_ERROR_NONE) {
					$this->save_customer_configurations($email,$customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['phone']);
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license', 'Your account has been retrieved successfully.');
				} else {
				
					$fields = array(
						'login_status' =>1,
						'registration_status' => ''
					);
					// Conditions for which records should be updated.
					$conditions = array(
						'id'=> 1
					);
					 
				
					MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
					
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'You already have an account with miniOrange. Please enter a valid password. ','error');
					
				}
			}

		} else {
				$fields = array(
					'login_status' => 0
				);
				// Conditions for which records should be updated.
				$conditions = array(
					'id' => 1
				);

				MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
				$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'Password and Confirm password do not match.','error');
		}
	}
	
	function validateOtp(){


		$otp_token = trim(JFactory::getApplication()->input->post->getArray()["otp_token"]);
		
	
		if( MoOAuthServerUtility::check_empty_or_null( $otp_token) ) {
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'Please enter a valid OTP.','error');
			return;
		}
		$transaction_id = MoOAuthServerUtility::miniOauthFetchDb('#__miniorange_oauthserver_customer',array("id"=>1),'loadResult','transaction_id');
		
		$customer = new MoOauthServerCustomer();
		$content = json_decode($customer->validate_otp_token($transaction_id, $otp_token ),true);
		if(strcasecmp($content['status'], 'SUCCESS') == 0) {
			$customerKey = json_decode($customer->create_customer(), true);
			
			$fields = array(
				'email_count' => 0,
				'sms_count' => 0
			);
			// Conditions for which records should be updated.
			$conditions = array(
				'id' => 1
			);
			
			MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			

			
			
			if(strcasecmp($customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0) {	//admin already exists in miniOrange
				$content = $customer->get_customer_key($email,$password);
				$customerKey = json_decode($content, true);
				if(json_last_error() == JSON_ERROR_NONE) {
					$this->save_customer_configurations($customerKey['email'], $customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['phone']);
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license','Your account has been retrieved successfully.');
				} else {
					$fields = array(
						'login_status' => 1,
						'password'=> '',
					);
					// Conditions for which records should be updated.
					$conditions = array(
						'id'=> 1
					);
					MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'You already have an account with miniOrange. Please enter a valid password.','error');
					
				}
			} else if(strcasecmp($customerKey['status'], 'SUCCESS') == 0) { 
				
				//registration successful
				$this->save_customer_configurations($customerKey['email'], $customerKey['id'], $customerKey['apiKey'], $customerKey['token'],$customerKey['phone']);
				$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license','Your account has been created successfully.');
			}else if(strcasecmp($customerKey['status'],'INVALID_EMAIL_QUICK_EMAIL')==0){
			
							$fields = array(
								'registration_status' => '',
								'email' => '',
								'password' => '',
								'transaction_id' => '',
							);
							// Conditions for which records should be updated.
							$conditions = array(
								'id' => 1
							);
							 
							MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
							$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account','There was an error creating an account for you. You may have entered an invalid Email-Id. <br><strong>(We discourage the use of disposable emails)</strong><br>
												Please try again with a valid email.','error');
							
				}
			//update_option('mo_saml_local_password', '');
		} else if( strcasecmp( $content['status'], 'CURL_ERROR') == 0) {
			
			$fields = array(
				'registration_status' => 'MO_OTP_VALIDATION_FAILURE'
			);
			// Conditions for which records should be updated.
			$conditions = array(
				'id' => 1
			);

			MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', $content['statusMessage'],'error');
				
		} else {
			$fields = array(
				'registration_status' => 'MO_OTP_VALIDATION_FAILURE'
			);
			// Conditions for which records should be updated.
			$conditions = array(
				'id' => 1
			);
			 
			MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account','Invalid one time passcode. Please enter a valid OTP.','error');
			
		}
	} 
	
	function resendOtp(){
		$customer = new MoOauthServerCustomer();
		$auth_type = 'EMAIL';
		
		$email = MoOAuthServerUtility::miniOauthFetchDb('#__miniorange_oauthserver_customer',array("id"=>1),'loadResult','email');
		
		$content = json_decode($customer->send_otp_token($auth_type, $email), true);
		if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				
				$customer_details = MoOAuthServerUtility::getCustomerDetails();
				$email_count = $customer_details['email_count'];
				$admin_email = $customer_details['email'];
				
				if($email_count != '' && $email_count >= 1){
					$email_count = $email_count + 1; 
					
					$fields = array(
						'email_count' => $email_count,
						'transaction_id' => $content['txId'],
						'registration_status' => 'MO_OTP_DELIVERED_SUCCESS'
					);
					// Conditions for which records should be updated.
					$conditions = array(
						'id' => 1
					);
					MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
					
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'Another One Time Passcode has been sent <strong>( ' .$email_count .' )</strong> to <strong>' . ( $admin_email) . '</strong>. Please enter the OTP below to verify your email.');
					
				}else{
					$fields = array(
						'email_count' => 1,
						'transaction_id' => $content['txId'],
						'registration_status' => 'MO_OTP_DELIVERED_SUCCESS'
					);
					// Conditions for which records should be updated.
					$conditions = array(
						'id' => 1
					);
					 
					MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account',  'An OTP has been sent to <strong>' . ($admin_email) . '</strong>. Please enter the OTP below to verify your email.');
					
				}

		} else if( strcasecmp( $content['status'], 'CURL_ERROR') == 0) {
			
			$fields = array(
				'registration_status' => 'MO_OTP_DELIVERED_FAILURE'
			);
			// Conditions for which records should be updated.
			$conditions = array(
				'id' => 1
			);
			 
			MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account',  $content['statusMessage'],'error');
			
		} else{
			$fields = array(
				'registration_status' => 'MO_OTP_DELIVERED_FAILURE'
			);
			// Conditions for which records should be updated.
			$conditions = array(
				'id' => 1
			);
			 
			MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account',  'There was an error in sending email. Please click on Resend OTP to try again.','error');
				
		}
	}

	function cancelform(){
		$fields = array(
			'email' => '',
			'password' => '',
			'customer_key' => '',
			'admin_phone' => '',
			'customer_token' => '',
			'api_key' => '',
			'registration_status' =>'', 
			'login_status' => 0,
			'transaction_id' => '',
			'email_count' => 0,
			'sms_count' => 0,
		);
		// Conditions for which records should be updated.
		$conditions = array(
			'id' => 1
		);
		 
		MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
			
		$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account');
		
	}
	
	function phoneVerification(){
		$phone = JFactory::getApplication()->input->post->getArray()['phone_number'];
		$phone = str_replace(' ', '', $phone);
		
		$pattern = "/[\+][0-9]{1,3}[0-9]{10}/";					
		
		if(preg_match($pattern, $phone, $matches, PREG_OFFSET_CAPTURE)){
			$auth_type = 'SMS';
			$customer = new MoOauthServerCustomer();
			$send_otp_response = json_decode($customer->send_otp_token($auth_type, $phone));
			if($send_otp_response->status == 'SUCCESS'){
				
				$sms_count = $email = MoOAuthServerUtility::miniOauthFetchDb('#__miniorange_oauthserver_customer',array("id"=>1),'loadResult','sms_count');
		
				
				if($sms_count != '' && $sms_count >= 1){
					$sms_count = $sms_count + 1; 
					
					$fields = array(
						'sms_count' => $sms_count,
						'transaction_id' => $send_otp_response->txId
					);
					// Conditions for which records should be updated.
					$conditions = array(
						'id'=> 1
					);
					 
					MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
		
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'Another One Time Passcode has been sent <strong>(' . $sms_count . ')</strong> for verification to ' . $phone);
					
					
				} else{
					$fields = array(
						'sms_count' => 1,
						'transaction_id' => $send_otp_response->txId
					);
					// Conditions for which records should be updated.
					$conditions = array(
						'id' => 1
					);
					 
					MoOAuthServerUtility::generic_update_query("#__miniorange_oauthserver_customer", $fields,$conditions);
		
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'A One Time Passcode has been sent for verification to ' . $phone);
				}
				
			} else{
				$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'An error occurred while sending OTP to phone. Please try again.');
			}
		}else{
			
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=account', 'Please enter the phone number in the following format: <strong>+##country code## ##phone number##</strong>','error');
		}
	}
	
	
	function moOAuthContactUs() {

		$post = JFactory::getApplication()->input->post->getArray();
		
		$query_email=$post['query_email'];
		$query=$post['query'];
		
		if( MoOAuthServerUtility::check_empty_or_null( $query_email ) || MoOAuthServerUtility::check_empty_or_null( $query) ) {
			$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup', 'Please submit your query with email.', 'error');
			return;
		} else{
			
				if(isset($post['mo_oauthserver_select_plan'])){
				
				$query = " <br> [mo_oauthserver_select_plan]:  ".$post['mo_oauthserver_select_plan']." <br> [number_of_users]:  ".$post['number_of_users']." <br> [Query]: ".$post['query'];
				
				}
			
			$phone = $post['query_phone'];
			$contact_us = new MoOauthServerCustomer();
			$submited = json_decode($contact_us->submit_contact_us($query_email, $phone, $query),true);
			if(json_last_error() == JSON_ERROR_NONE) {
				if(is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR'){
					$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup', $submited['message'],'error');
				}else{
					if ( $submited == false ) {
						$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup', 'Your query could not be submitted. Please try again.','error');
					} else {
						
						if(isset($post['mo_oauthserver_select_plan'])){
						
							$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=requestdemo', 'Thanks for getting in touch! We shall send you quotation shortly.');
						
						}else{
							
							$this->setRedirect('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=requestdemo', 'Thanks for getting in touch! We shall get back to you shortly.');
							
						}
					
					}
				}
			}

		}
	}
}