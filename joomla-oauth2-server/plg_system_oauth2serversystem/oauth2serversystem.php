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
 * @copyright   Copyright (c) 2026 Jefferson49
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
            $bearer =      $access_token[0];
            $access_token = $access_token[1];

            if ($bearer !== 'Bearer') {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Did not receive a valid Bearer token.';
                OAuth2ServerUtility::errorResponse(403, '', $error, $error_description, '');
                exit;
            }

            if ($access_token === '') {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Access denied, because access token is missing.';
                OAuth2ServerUtility::errorResponse(403, '', $error, $error_description, '');
                exit;
            }

            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id','oauth2_time_stamp')));
            $query->from($db->quoteName('#__users'));
            $query->where($db->quoteName('oauth2_client_token') . ' =' . $db->quote($access_token));
            $db->setQuery($query);
            $results = $db->loadAssoc();

            if ($results === null) {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Access denied, because access token is invalid.';
                OAuth2ServerUtility::errorResponse(403, '', $error, $error_description, '');
                exit;
            }
            if($results['oauth2_time_stamp'] < time())
            {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Access denied, because access token is expired.';
                OAuth2ServerUtility::errorResponse(403, '', $error, $error_description, '');
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

                if ($results === null) {
                    $error = OAuth2ServerUtility::ERROR_SERVER_ERROR;
                    $error_description = 'User not found.';
                    OAuth2ServerUtility::errorResponse(500, '', $error, $error_description, '');
                    exit;
                }

                $api_response = array(
                    'id'       => $results['id'],
                    'name'     => $results['name'] ?? '',
                    'username' => $results['username'] ?? '',
                    'email'    => $results['email'] ?? '',
                );

                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                exit;
            }
            else
            {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Access denied, because access token does not match.';
                OAuth2ServerUtility::errorResponse(403, '', $error, $error_description, '');
                exit;
            }
        }

        //If GET request (Authorization Request, RFC 6749 Section 4.1.1)
		elseif (isset($get['client_id']) && isset($get['redirect_uri']) && !isset($get['client_secret']))
        {
            if(!isset($customerResult['client_id']) || $customerResult['client_id'] !== $get['client_id'])
            {
                $error = OAuth2ServerUtility::ERROR_INVALID_REQUEST;
                $error_description = 'Invalid client ID.';
                OAuth2ServerUtility::errorResponse(400, $get['state'] ?? '', $error, $error_description, '');
                exit;
            }

            if(!isset($customerResult['authorized_uri']) || $customerResult['authorized_uri'] !== $get['redirect_uri'])
            {
                $error = OAuth2ServerUtility::ERROR_INVALID_REQUEST;
                $error_description = 'Invalid redirect URI.';
                OAuth2ServerUtility::errorResponse(400, $get['state'] ?? '', $error, $error_description, '');
                exit;
            }

            if(OAuth2ServerUtility::check_empty_or_null($get['response_type']) || $get['response_type'] !== 'code')
            {
                $error = OAuth2ServerUtility::ERROR_INVALID_REQUEST;
                $error_description = 'Invalid response type provided. MUST be "code".';
                OAuth2ServerUtility::errorResponse(400, $get['state'] ?? '', $error, $error_description, $get['redirect_uri'] ?? '');
                exit;
            }

            $user = Factory::getApplication()->getIdentity();  // Get the user object
            $user_id = $user->id;
            $app  = Factory::getApplication(); // Get the application
            $client_id = $get['client_id'];
            $scope = $get['scope'] ?? 'email';
            $redirect_uri = $get['redirect_uri'];
            $response_type = $get['response_type'];
            $state = $get['state'];

            //If a user is logged in, generate a new random code and redirect to the client app with the code and state parameters
            if($user_id !== 0)
            {
                $customerResult = OAuth2ServerUtility::miniOauthFetchDb('#__users',array("id"=>$user->id),'loadAssoc','*');

                if($customerResult !== null) {
                    $randcode = OAuth2ServerUtility::generateRandomString();

                    //Remember the randcode in the database
                    OAuth2ServerUtility::persistAutorizationCode($user_id, $randcode, $client_id);

                    //Send Authorization Response (RFC 6749 Section 4.1.2); redirect the browser
                    $redirect_uri = OAuth2ServerUtility::createUrl($redirect_uri, [
                        'code' => $randcode,
                        'state' => $state
                    ]);

                    $app->redirect($redirect_uri);
                    $app->close();
                }
                else {
                    $app->getSession()->destroy();
                }
            }

            //If the user is not logged in, remember the response parameters in a cookie for later use after the user logs in
            $oauth_response_params = [
                'client_id'     => $client_id ,
                'scope'         => $scope ,
                'redirect_uri'  => $redirect_uri ,
                'response_type' => $response_type,
                'state'         => $state ,
                'clientName'    => $OAuthClientAppName,
            ];

            OAuth2ServerUtility::setOAuthResponseParamsCookie($oauth_response_params);

            //If a Joomla login link was provided, use it; otherwise use default
            if ($customerResult['login_link'] !== '') {
                $redirect_url = Uri::base() . $customerResult['login_link'];
            }
            else {
                $redirect_url = Uri::base() . "index.php?option=com_users&view=login";
            }

            //Redirect the user to the login page
            $app->redirect(Route::_($redirect_url, false));
		}

        //If POST request (Access Token Request, RFC 6749 Section 4.1.3)
        elseif(isset($post['client_id']) && isset($post['client_secret']) && isset($post['redirect_uri']) && isset($post['grant_type']) && isset($post['code']))
        {
            //Check if the redirect URI parameter belongs to the client ID provided in the request
            if ($customerResult['authorized_uri'] !== $post['redirect_uri']) {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Access denied, because the redirect URI parameter does not belong to the client ID.';
                OAuth2ServerUtility::errorResponse(403, $post['state'] ?? '', $error, $error_description, '', true);
            }

            //Check the client ID
            if ($customerResult['client_id'] !== $post['client_id']) {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Invalid client ID.';
                OAuth2ServerUtility::errorResponse(403, $post['state'] ?? '', $error, $error_description, '', true);
            }

            //Check if the grant type is authorization_code
            if ($post['grant_type'] !== 'authorization_code')
            {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Invalid grant type. MUST be "authorization_code".';
                OAuth2ServerUtility::errorResponse(403, $post['state'] ?? '', $error, $error_description, '', true);
            }

            //Get the data for the provided code
            $results = OAuth2ServerUtility::miniOauthFetchDb('#__users',array("oauth2_randcode" => $post['code']));

            //Check if the code is bound to a user
            if (OAuth2ServerUtility::check_empty_or_null($results['id'])) {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Access denied, because the code parameter is not bound to any user.';
                OAuth2ServerUtility::errorResponse(403, $post['state'] ?? '', $error, $error_description, '', true);
            }

            //Check if the code parameter belongs to the client ID provided in the request
            if ($results['randcode_client_id'] !== $post['client_id']) {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Access denied, because the code parameter does not belong to the client ID.';
                OAuth2ServerUtility::errorResponse(403, $post['state'] ?? '', $error, $error_description, '', true);
            }

            //Check if the code parameter is expired
            if ($results['randcode_time_stamp'] < time()) {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Access denied, because the code parameter has expired.';
                OAuth2ServerUtility::errorResponse(403, $post['state'] ?? '', $error, $error_description, '', true);
            }

            //Check the client secret
            if ($customerResult['client_secret'] !== $post['client_secret']) {
                $error = OAuth2ServerUtility::ERROR_ACCESS_DENIED;
                $error_description = 'Invalid client secret.';
                OAuth2ServerUtility::errorResponse(403, $post['state'] ?? '', $error, $error_description, '', true);
            }

            //Generate an access token
            $access_token = OAuth2ServerUtility::generateRandomString();

            //Persist the access token and invalidate the randcode
            $fields = array(
                'oauth2_client_token' => $access_token,
                'oauth2_time_stamp'   => time() + OAuth2ServerUtility::EXPIRATION_INTERVAL,
                'oauth2_randcode'     => OAuth2ServerUtility::generateRandomString(),
                'randcode_time_stamp' => time() - 1,
            );
            $conditions = array(
                'id' => $results['id']
            );

            $updated_rows = OAuth2ServerUtility::generic_update_query('#__users', $fields , $conditions);

            if ($updated_rows !== 1) {
                $error = OAuth2ServerUtility::ERROR_SERVER_ERROR;
                $error_description = 'Failed to persist the access token.';
                OAuth2ServerUtility::errorResponse(500, $post['state'] ?? '', $error, $error_description, '', true);
            }

            //Send Access Token Response (RFC 6749 Section 4.1.4)
            $content = [
                'token_type'   => 'Bearer',
                'access_token' => $access_token,
                'expires_in'   => OAuth2ServerUtility::EXPIRATION_INTERVAL,
                "scope"        => 'profile',
            ];
            if (isset($post['state']) && $post['state'] !== '') {
                $content['state'] = $post['state'];
            }

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }

        //The request is missing required parameters
        //Do nothing, since we are in the general initialisation phase of Joomla, and we cannot interfere the general Joomla lifecycle with an error.
	}
	function onExtensionBeforeUninstall($id)
    {

    }
}
