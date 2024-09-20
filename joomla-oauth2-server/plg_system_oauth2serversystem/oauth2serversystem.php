<?php

/**
 * @package     Joomla.System
 * @subpackage  joomla-oauth2-server
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.plugin.plugin' );
jimport('oauth2serverlib.utility.MoOAuthServerUtility');
class plgSystemOauth2serversystem extends JPlugin	
{

	public function onAfterInitialise()
	{ 
		$get = JFactory::getApplication()->input->get->getArray();
	    $post = JFactory::getApplication()->input->post->getArray();	
        $clientId = $get['client_id'] ?? ($post['client_id'] ?? '');
        $customerResult = MoOAuthServerUtility::miniOauthFetchDb('#__oauth2_server_config',array("client_id" => $clientId),'loadAssoc','*');
        $headers = getallheaders();
        $OAuthClientAppName = $customerResult['client_name'];

        //If authorized request for user data
        if (($headers['Authorization'] ?? '') !== '') {
            
            $access_token = $headers['Authorization'];
            $access_token = explode(" ", $access_token, 2);
            $access_token =$access_token[1];
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id','oauth2_time_stamp')));
            $query->from($db->quoteName('#__users'));
            $query->where($db->quoteName('oauth2_client_token') . ' =' . $db->quote($access_token));
            $db->setQuery($query);
            $results = $db->loadAssoc();
            if($results['oauth2_time_stamp']<time())
            {		
                $api_response= array('error' => 'Access token got expire,please contact your administrator');
                echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                exit;					
            }
            $query = $db->getQuery(true);
            $query->select($db->quoteName('group_id'));
            $query->from($db->quoteName('#__user_usergroup_map'));
            $query->where($db->quoteName('user_id') . ' =' . $db->quote($results['id']));
            $db->setQuery($query);
            
            if($results['id']!='')
            {
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from($db->quoteName('#__users'));
                $query->where($db->quoteName('id') . ' =' . $db->quote($results['id']));
                $db->setQuery($query);
                $results = $db->loadAssoc();
            
                $api_response = array(		
                    'id'       => $results['id'],
                    'name'     => $results['name'] ?? '',
                    'username' => $results['username'] ?? '',
                    'email'    => $results['email'] ?? '',
                );
            
                echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                exit;
            }
            else
            {
                $api_response= array('error' => 'Access token dosent match,please contact your administrator');
                echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                exit;				
            }
        }

        //If GET request
		elseif(isset($get['client_id']) && isset($get['redirect_uri']) && !isset($get['client_secret']))
        {   
			if(isset($customerResult['client_id']) && $customerResult['client_id']===$get['client_id'] && isset($customerResult['authorized_uri']) && $customerResult['authorized_uri']===$get['redirect_uri'])
            {
				$user = JFactory::getApplication()->getIdentity();  // Get the user object
                $user_id = $user->id;
				$app  = JFactory::getApplication(); // Get the application
				$client_id = $get['client_id'];
				$scope = $get['scope'] ?? 'email';
				$redirect_uri = $get['redirect_uri'];
				$response_type = $get['response_type'];
				$state = $get['state'];
			    if($user_id !== 0)
                {
                    $customerResult = MoOAuthServerUtility::miniOauthFetchDb('#__users',array("id"=>$user->id),'loadAssoc','*');

                    if($customerResult !== null) {
                        $redirecturi = $redirect_uri;
                        $randcode = $this->generateRandomString();		
                        $user_id = $user->id;		
                        $fields = array(
                            'oauth2_randcode' =>$randcode
                        );
                        $conditions = array(
                            'id' => $user_id
                        );
                        MoOAuthServerUtility::generic_update_query('#__users', $fields, $conditions);
                    
                        $state = $get['state']; 
                        $redirecturi = $redirecturi."&code=".$randcode."&state=".$state;	
                        header('Location: ' . $redirecturi);
                        MoOAuthServerUtility::plugin_efficiency_check('', $OAuthClientAppName, $redirect_uri);
                        exit;
                    }
                    else {
			            JFactory::getApplication()->getSession()->destroy();		
    				}	
			    }
    			$oauth_response_params = array('client_id' => $client_id , "scope" => $scope , "redirect_uri" => $redirect_uri , "response_type" => $response_type, "state" => $state , "clientName" => $OAuthClientAppName);
		    	setcookie("response_params",json_encode($oauth_response_params), time() + 300, '/');
			    $redirect_url = JURI::base() . "index.php?option=com_users&view=login";
                $app->redirect(JRoute::_($redirect_url, false));	
			}
            else
            {	
				//send back error for authorization
                $redirect_uri = isset($get['redirect_uri'])?$get['redirect_uri']:NULL;
                MoOAuthServerUtility::plugin_efficiency_check('', $OAuthClientAppName, $redirect_uri,"Invalid Redirect Uri (or) Invalid Client ID");
                $api_response= array('error' => 'Invalid Redirect Uri (or) Invalid Client ID');
                echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				exit;	
			}
		}

        //If POST request
        else if(isset($post['client_id']) && isset($post['client_secret']))
        {
    		if($customerResult['client_id']===$post['client_id']  && $customerResult['authorized_uri']===$post['redirect_uri'] && $customerResult['client_secret']===$post['client_secret'])
            {
				if($post['grant_type']!='authorization_code')
                {	
				    $api_response= array('error' => 'grantTypes mismatch or limited,please contact your administrator');
				    echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				    exit;
				}		
				$randcode = $this->generateRandomString();
				$code = $post['code'];
    			//Getting the user details using code parameter	
                $results = MoOAuthServerUtility::miniOauthFetchDb('#__users',array("oauth2_randcode"=>$code),'loadAssoc','id');
			    if($results['id']!='')
                {
    				$t=time()+300;
	    			$time=300;	
        			// inserting the accessToken	
				    $fields = array(
					   'oauth2_client_token'=>$randcode,
					   'oauth2_time_stamp'=>$t
				    );
				    $conditions = array(
					    'id'=>$results['id']
				    ); 	
                    MoOAuthServerUtility::generic_update_query('#__users', $fields , $conditions);
        			$scope="profile";
		        	$token_type="Bearer";
			        $api_response = array('access_token' => $randcode, 'expires_in' => $time, "scope"=> $scope, 'token_type' => $token_type);	
        			echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		        	exit;
    			}
                else
                {
				    $api_response= array('error' => 'Some Error with code recived,please contact your administrator');
				    echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				    exit;
    			}		
			}
            else
            {		
				$api_response= array('error' => 'Some Error at Token Endpoint URL, please contact your administrator');
				echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				exit;
			}	
        }
	}
	function onExtensionBeforeUninstall($id)
    {
		
    }
	
    function generateRandomString() 
    {  
		//ToDo: Do we need to genralize "id"=>'1' ?
        $tokenLength = MoOAuthServerUtility::miniOauthFetchDb('#__oauth2_server_config',array("id"=>'1'),'loadResult','token_length');
        $tokenLength=intval($tokenLength);
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $tokenLength; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
		
}

