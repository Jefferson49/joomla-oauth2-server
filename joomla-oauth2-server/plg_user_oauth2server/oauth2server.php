<?php
/**
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  http://miniorange.com/usecases/miniOrange_User_Agreement.pdf
 *
 * Extended by:
 * @subpackage joomla-oauth2-server
 * @link       https://github.com/Jefferson49/Joomla-OAuth2-Server
 * @author     Jefferson49
 * @copyright  Copyright (c) 2024 Jefferson49
 * @license    GNU/GPL v3.0
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

jimport( 'joomla.plugin.plugin' );
jimport('oauth2serverlib.utility.OAuth2ServerUtility');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class plgUserOauth2server extends CMSPlugin
{
	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 *
	 * @access    public
	 * @param     array     $options        Array of extra options
	 * @return    boolean
	 */

	public function onUserAfterLogin($options) {

		$cookie = Factory::getApplication()->input->cookie->getArray();

		if(isset($cookie['response_params'])){
			$response_params = json_decode(stripslashes($cookie['response_params']),true);
			$client_id    = $response_params['client_id'];
			$redirect_uri = $response_params['redirect_uri'];
			$state        = $response_params['state'];

			$user = Factory::getApplication()->getIdentity();
			$user_id = $user->get('id');

			//Generate a new random code
			$randcode = OAuth2ServerUtility::generateRandomString();

			//Remember the  new random code in the database
			OAuth2ServerUtility::persistAutorizationCode($user_id, $randcode, $client_id);

			//Clear the cookie that was set to remember the response parameters
			OAuth2ServerUtility::clearOAuthResponseParamsCookie();

			if (strpos($redirect_uri, "?") === false) {
				$query_separator = "?";
			}
			else {
				$query_separator = "&";
			}

			$redirect_uri = $redirect_uri.$query_separator."code=".$randcode."&state=".$state;

			//Send Authorization Response (RFC 6749 Section 4.1.2); redirect the browser
			header('Location: ' . $redirect_uri);
			exit;
		}
	}
}