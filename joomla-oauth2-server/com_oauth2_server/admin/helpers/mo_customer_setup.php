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
jimport('oauth2_server.utility.MoOAuthServerUtility');

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
		return '';
	}

	function mo_oauth_request_for_demo($email, $plan, $description)
	{
        return '';
	}

	function get_customer_key($email,$password) 
	{
		return '';
	}
	
	public static function submit_feedback_form($email,$phone,$query)
	{
        return '';
	}
	

	function submit_contact_us( $q_email, $q_phone, $query )
	{
		return false;
	}
	
	function send_otp_token($auth_type, $emailOrPhone)
	{
		return '';
	}

	function validate_otp_token($transactionId,$otpToken){
		return '';
	}
	
	function check_customer($email) {
		return '';
	}
}
