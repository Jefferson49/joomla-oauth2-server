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
	 * @param     array     $credentials    Array holding the user credentials ('username' and 'password')
	 * @param     array     $options        Array of extra options
	 * @param     object    $response       Authentication response object
	 * @return    boolean
	 */
	
	public function onUserAfterLogin($options) {
		
		$cookie = Factory::getApplication()->input->cookie->getArray();
		
		if(isset($cookie['response_params'])){
			$response_params =  json_decode(stripslashes($cookie['response_params']),true);
		
			$user = Factory::getApplication()->getIdentity();
			
			$user_id = $user->get('id');
			
			$randcode =OAuth2ServerUtility::generateRandomString();
			
			$db = Factory::getDbo();
            $query = $db->getQuery(true);
            // Fields to update.
            $fields = array(
                //db->quoteName('clientstate') . ' = ' . $db->quote($response_params['state']),
				//$db->quoteName('randcodetok') . ' = ' . $db->quote(),
				$db->quoteName('oauth2_randcode'). ' = ' . $db->quote($randcode)
            );

            // Conditions for which records should be updated.
            $conditions = array(
				$db->quoteName('id') . ' = '. $db->quote($user_id)
            );

            $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute(); 
			
			
			$redirecturi=$response_params['redirect_uri'];
			$state =$response_params['state'];	
			
			$redirecturi = $redirecturi."&code=".$randcode."&state=".$state;

				
			header('Location: ' . $redirecturi);
			OAuth2ServerUtility::plugin_efficiency_check($user->get('email'), $response_params['clientName'], $redirecturi);
			exit;
		}
		
		return false;
	}
}