<?php
/**
 * @package     Joomla.System
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

jimport( 'joomla.plugin.plugin' );
jimport('oauth2serverlib.utility.OAuth2ServerUtility');
class plgSystemOauth2serversystem extends CMSPlugin
{
    private const EXPIRATION_INTERVAL = 300; // 5 minutes

	public function onAfterInitialise()
	{
		$get = Factory::getApplication()->input->get->getArray();
	    $post = Factory::getApplication()->input->post->getArray();
        $clientId = $get['client_id'] ?? ($post['client_id'] ?? '');
        $customerResult = OAuth2ServerUtility::miniOauthFetchDb('#__oauth2_server_config',array("client_id" => $clientId),'loadAssoc','*');
        $headers = getallheaders();
        $OAuthClientAppName = $customerResult['client_name'] ?? '';

        //If authorized request for user data
        if (($headers['Authorization'] ?? '') !== '') {

            $access_token = $headers['Authorization'];
            $access_token = explode(" ", $access_token, 2);
            $access_token =$access_token[1];
            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id','oauth2_time_stamp')));
            $query->from($db->quoteName('#__users'));
            $query->where($db->quoteName('oauth2_client_token') . ' =' . $db->quote($access_token));
            $db->setQuery($query);
            $results = $db->loadAssoc();
            if($results['oauth2_time_stamp']<time())
            {
                $api_response= array('error' => 'Access denied, because access token is expired.');
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
                $api_response= array('error' => 'Access denied, because access token does not match.');
                echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                exit;
            }
        }

        //If GET request
		elseif(isset($get['client_id']) && isset($get['redirect_uri']) && !isset($get['client_secret']))
        {
			if(isset($customerResult['client_id']) && $customerResult['client_id']===$get['client_id'] && isset($customerResult['authorized_uri']) && $customerResult['authorized_uri']===$get['redirect_uri'])
            {
				$user = Factory::getApplication()->getIdentity();  // Get the user object
                $user_id = $user->id;
				$app  = Factory::getApplication(); // Get the application
				$client_id = $get['client_id'];
				$scope = $get['scope'] ?? 'email';
				$redirect_uri = $get['redirect_uri'];
				$response_type = $get['response_type'];
				$state = $get['state'];
			    if($user_id !== 0)
                {
                    $customerResult = OAuth2ServerUtility::miniOauthFetchDb('#__users',array("id"=>$user->id),'loadAssoc','*');

                    if($customerResult !== null) {
                        $redirecturi = $redirect_uri;
                        $randcode = OAuth2ServerUtility::generateRandomString();
                        $user_id = $user->id;

                        //Remember the randcode in the database
                        $fields = array(
                            'oauth2_randcode'     => $randcode,
                            'randcode_client_id'  => $client_id,
    					    'randcode_time_stamp' => time() + self::EXPIRATION_INTERVAL
                        );
                        $conditions = array(
                            'id' => $user_id
                        );
                        OAuth2ServerUtility::generic_update_query('#__users', $fields, $conditions);

                        $state = $get['state'];

						if (strpos($redirecturi, "?") === false) {
                            $query_separator = "?";
                        }
                        else {
                            $query_separator = "&";
                        }

                        $redirecturi = $redirecturi.$query_separator."code=".$randcode."&state=".$state;
                        header('Location: ' . $redirecturi);
                        OAuth2ServerUtility::plugin_efficiency_check('', $OAuthClientAppName, $redirect_uri);
                        exit;
                    }
                    else {
			            Factory::getApplication()->getSession()->destroy();
    				}
			    }
    			$oauth_response_params = array('client_id' => $client_id , "scope" => $scope , "redirect_uri" => $redirect_uri , "response_type" => $response_type, "state" => $state , "clientName" => $OAuthClientAppName);
		    	setcookie("response_params",json_encode($oauth_response_params), time() + self::EXPIRATION_INTERVAL, '/');

                //If a Joomla login link was provided, use it; otherwise use default
                if ($customerResult['login_link'] !== '') {
                    $redirect_url = Uri::base() . $customerResult['login_link'];
                }
                else {
                    $redirect_url = Uri::base() . "index.php?option=com_users&view=login";
                }
                $app->redirect(Route::_($redirect_url, false));
			}
            else
            {
				//send back error for authorization
                $redirect_uri = isset($get['redirect_uri'])?$get['redirect_uri']:NULL;
                OAuth2ServerUtility::plugin_efficiency_check('', $OAuthClientAppName, $redirect_uri,"Invalid Redirect URI (or) Invalid Client ID");
                $api_response= array('error' => 'Invalid Redirect URI (or) Invalid Client ID');
                echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				exit;
			}
		}

        //If POST request
        else if(isset($post['client_id']) && isset($post['client_secret']))
        {
    		if($customerResult['client_id']===$post['client_id']  && $customerResult['authorized_uri']===$post['redirect_uri'] && $customerResult['client_secret']===$post['client_secret'])
            {
                //Check if the grant type is authorization_code
                if($post['grant_type']!='authorization_code')
                {
				    $api_response= array('error' => 'Access denied, because grant type does not match.');
				    echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				    exit;
				}

                //Check if the code parameter is present in the request
                if (!isset($post['code']) || empty($post['code'])) {
                    $api_response= array('error' => 'Access denied, because code parameter is missing.');
                    echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    exit;
                }

                //Get the data for the provided code
                $results = OAuth2ServerUtility::miniOauthFetchDb('#__users',array("oauth2_randcode" => $post['code']));

                //Check if the code parameter belongs to the client ID provided in the request
                if ($results['randcode_client_id'] !== $post['client_id']) {
                    $api_response= array('error' => 'Access denied, because the code parameter does not belong to the client ID.');
                    echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    exit;
                }

                //Check if the code parameter is expired
                if ($results['randcode_time_stamp'] < time()) {
                    $api_response= array('error' => 'Access denied, because the code parameter has expired.');
                    echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    exit;
                }

                if($results['id']!='')
                {
                    //Generate an access token
                    $access_token = OAuth2ServerUtility::generateRandomString();

        			//Insert the access token and invalidate the randcode
				    $fields = array(
					    'oauth2_client_token' => $access_token,
					    'oauth2_time_stamp'   => time() + self::EXPIRATION_INTERVAL,
                        'oauth2_randcode'     => OAuth2ServerUtility::generateRandomString(),
                        'randcode_time_stamp' => time() - 1,
				    );
				    $conditions = array(
					    'id' => $results['id']
				    );
                    OAuth2ServerUtility::generic_update_query('#__users', $fields , $conditions);
        			$scope="profile";
		        	$token_type="Bearer";
			        $api_response = array('access_token' => $access_token, 'expires_in' => self::EXPIRATION_INTERVAL, "scope"=> $scope, 'token_type' => $token_type);
        			echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		        	exit;
    			}
                else
                {
				    $api_response= array('error' => 'Access denied, because wrong code received.');
				    echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				    exit;
    			}
			}
            else
            {
				$api_response= array('error' => 'Access denied, because invalid client credentials or redirect URI provided.');
				echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				exit;
			}
        }
	}
	function onExtensionBeforeUninstall($id)
    {

    }
}

