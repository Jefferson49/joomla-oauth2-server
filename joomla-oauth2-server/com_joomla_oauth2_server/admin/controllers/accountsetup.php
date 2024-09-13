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
}