<?php
/**
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  http://miniorange.com/usecases/miniOrange_User_Agreement.pdf
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
jimport('oauth2serverlib.utility.MoOAuthServerUtility');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class plgUserOauth2server extends JPlugin
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
		
		$cookie = JFactory::getApplication()->input->cookie->getArray();
		
		if(isset($cookie['response_params'])){
			$response_params =  json_decode(stripslashes($cookie['response_params']),true);
		
			$user = JFactory::getUser();
			
			$user_id = $user->get('id');
			
			$randcode = $this->generateRandomString();
			
			$db = JFactory::getDbo();
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
			MoOAuthServerUtility::plugin_efficiency_check($user->get('email'), $response_params['clientName'], $redirecturi);
			exit;
		}
		
		return false;
	}
	
	
	 function generateRandomString() {
		 
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 30; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	
}