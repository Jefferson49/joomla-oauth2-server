<?php
/**
 * @package     Joomla.Administrator
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * Extended by:
 * @subpackage  joomla-oauth2-server
 * @link        https://github.com/Jefferson49/Joomla-OAuth2-Server
 * @author      Jefferson49
 * @copyright   Copyright (c) 2024 Jefferson49
 * @license     GNU/GPL v3.0
 */

/**
 * AccountSetup Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  joomla-oauth2-server
 * @since       0.0.9
 */
defined('_JEXEC') or die('Restricted access');
jimport('oauth2serverlib.utility.OAuth2ServerUtility');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

class miniorangeoauthserverControllerAccountSetup extends FormController
{
	function __construct()
	{
		$this->view_list = 'accountsetup';
		parent::__construct();
	}

	function updateToken()
	{
		$post=	Factory::getApplication()->input->post->getArray();
		if(isset($post['mo_server_token_length']))
		{			
		 	// Fields to update.
			$tokenLength=$post['mo_server_token_length'];
			$fields = array(
				'token_length' => $tokenLength,
			);
			// Conditions for which records should be updated.

			//ToDo: Should be for all ids?
			$conditions = array(
				'id' => 1
			);
			OAuth2ServerUtility::generic_update_query('#__oauth2_server_config', $fields,$conditions);
		}
		$this->setRedirect('index.php?option=com_oauth2server&view=accountsetup&tab-panel=advancesettings','Setting saved successfully');
	}	
	
	function addclient()
	{
		$post=	Factory::getApplication()->input->post->getArray();
		$client_id = OAuth2ServerUtility::generateRandomString(30);
		$client_secret = OAuth2ServerUtility::generateRandomString(30);
		$authorized_uri = trim($post['mo_oauth_client_redirect_url']," ");
		$login_link = trim($post['mo_oauth_client_login_link']," ");
		// Fields to update.
		$fields = array(
			'client_name'    => $post['mo_oauth_custom_client_name'],
			'client_id'      => $client_id,
			'client_secret'  => $client_secret,
			'authorized_uri' => $authorized_uri,
			'login_link'     => $login_link,
			'client_count'   => 0,
		);
			 
		OAuth2ServerUtility::generic_insert_query("#__oauth2_server_config", $fields);
		
		$this->setRedirect('index.php?option=com_oauth2server&tab-panel=configuration&pa=2', 'Client  has been added successfully.');	
	}
	
	function deleteclient(){
			
		$get = Factory::getApplication()->input->get->getArray();

		$selection = array(
			'id' => $get['id'],
		);

		OAuth2ServerUtility::generic_delete_query("#__oauth2_server_config", $selection);
		
		$this->setRedirect('index.php?option=com_oauth2server&tab-panel=configuration');
	}
		
	function updateclient()
	{
		$post=	Factory::getApplication()->input->post->getArray();		
		$authorized_uri=trim($post['mo_oauth_client_redirect_url']," ");
		$login_link=trim($post['mo_oauth_client_login_link']," ");
	    // Fields to update.
		$fields = array(
			'authorized_uri'=>$authorized_uri,
			'login_link'    =>$login_link,
		);
		// Conditions for which records should be updated.
		$conditions = array(
			'id' => $post['id'],
		);

		OAuth2ServerUtility::generic_update_query("#__oauth2_server_config", $fields,$conditions);

		$this->setRedirect('index.php?option=com_oauth2server&tab-panel=configuration&pa=2', 'Client has been updated successfully.');
	}
}