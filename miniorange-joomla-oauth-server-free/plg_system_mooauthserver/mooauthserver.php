<?php

/**
 * @package     Joomla.System
 * @subpackage  plg_system_mooauthserver
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.plugin.plugin' );
jimport('miniorangeoauthserver.utility.MoOAuthServerUtility');
class plgSystemMooauthserver extends JPlugin	
{

	public function onAfterInitialise()
	{ 
        $customerResult = MoOAuthServerUtility::miniOauthFetchDb('#__miniorange_oauthserver_config',array("id"=>'1'),'loadAssoc','*');
		$get = JFactory::getApplication()->input->get->getArray();
	    $post = JFactory::getApplication()->input->post->getArray();	
        $OAuthClientAppName = $customerResult['client_name'];	
		if(isset($get['client_id']) && !isset($get['client_secret']))
        {   
			if(isset($customerResult['client_id']) && $customerResult['client_id']===$get['client_id'] && isset($customerResult['authorized_uri']) && $customerResult['authorized_uri']===$get['redirect_uri'])
            {
				$session = JFactory::getSession(); #Get current session vars
				$user = JFactory::getUser();        // Get the user object
				$app  = JFactory::getApplication(); // Get the application
				$client_id = $get['client_id'];
				$scope = $get['scope'] ?? 'email';
				$redirect_uri = $get['redirect_uri'];
				$response_type = $get['response_type'];
				$state = $get['state'];
			    if( $user->id!='') 
                {
				    $user = JFactory::getUser();
                    $customerResult = MoOAuthServerUtility::miniOauthFetchDb('#__users',array("id"=>$user->id),'loadAssoc','*');

                    $redirecturi = $redirect_uri;
                    $randcode = $this->generateRandomString();		
                    $user_id = $user->id;		
                    $fields = array(
                        'rancode' =>$randcode
                    );
                    $conditions = array(
                        'id' => $user_id
                    );
                    MoOAuthServerUtility::generic_update_query('#__users', $fields, $conditions);
                
                    $state = $get['state']; 
                    $redirecturi = $redirecturi."?code=".$randcode."&state=".$state;	
                    header('Location: ' . $redirecturi);
                    MoOAuthServerUtility::plugin_efficiency_check('', $OAuthClientAppName, $redirect_uri);
                    exit;
			    }
    			$oauth_response_params = array('client_id' => $client_id , "scope" => $scope , "redirect_uri" => $redirect_uri , "response_type" => $response_type, "state" => $state , "clientName" => $OAuthClientAppName);
	    		$msg="Only admins will have complete SSO (auto login) in free version. Inorder to auto login for normal users please upgrade to premium";
		    	setcookie("response_params",json_encode($oauth_response_params), time() + 300, '/');
			    $redirect_url = JURI::base() . "index.php?option=com_users&view=login";
                $app->enqueueMessage($msg, 'notice');
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
                $results = MoOAuthServerUtility::miniOauthFetchDb('#__users',array("rancode"=>$code),'loadAssoc','id');
			    if($results['id']!='')
                {
    				$t=time()+300;
	    			$time=300;	
        			// inserting the accessToken	
				    $fields = array(
					   'client_token'=>$randcode,
					    'time_stamp'=>$t
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
				$api_response= array('error' => 'Some Error at Token Endpoint URL,please contact your administrator');
				echo(json_encode($api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				exit;
			}	
		}		
		if(isset($post['mojsp_feedback']))
        {
            $radio=$post['deactivate_plugin'];
            $data=$post['query_feedback'];
			$feedback_email = isset($post['feedback_email']) ? $post['feedback_email'] : '';
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            // Fields to update.
            $fields = array(
                $db->quoteName('uninstall_feedback') . ' = ' . $db->quote(1)
            );
            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );
            $query->update($db->quoteName('#__miniorange_oauthserver_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            $current_user =  JFactory::getUser();
            //$result = Utilities::getCustomerDetails();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select(array('*'));
            $query->from($db->quoteName('#__miniorange_oauthserver_customer'));
            $query->where($db->quoteName('id')." = 1");
            $db->setQuery($query);
            $customerResult = $db->loadAssoc();
            $admin_email = (isset($customerResult['email']) && !empty($customerResult['email'])) ? $customerResult['email'] : $feedback_email;
            $admin_phone = $customerResult['admin_phone'];
            $data1 = $radio.' : '.$data;
            require_once JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_oauthserver' . DIRECTORY_SEPARATOR . 'helpers' .DIRECTORY_SEPARATOR . 'mo_customer_setup.php';
            MoOauthServerCustomer::submit_feedback_form($admin_email,$admin_phone,$data1);
            require_once JPATH_SITE . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Installer' .DIRECTORY_SEPARATOR . 'Installer.php';
			foreach ($post['result'] as $fbkey) 
            {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('type');
                $query->from('#__extensions');
                $query->where($db->quoteName('extension_id') . " = " . $db->quote($fbkey));
                $db->setQuery($query);
                $result = $db->loadColumn();
                $identifier=$fbkey;
			    $type=0;
                foreach ($result as $results)
                {        
                    $type=$results;
                }
                if($type)
                {
                    $installer = new JInstaller();
                    $installer->uninstall ($type,$identifier);
                }
    		}
        }
	}
	function onExtensionBeforeUninstall($id)
    {
	    $post = JFactory::getApplication()->input->post->getArray();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('extension_id');
        $query->from('#__extensions');
        $query->where($db->quoteName('name') . " = " . $db->quote('COM_MINIORANGE_OAUTHSERVER' ));
        $db->setQuery($query);
        $result = $db->loadColumn();
        $tables = JFactory::getDbo()->getTableList();
        $tab=0;
        foreach ($tables as $table) 
        {
            if(strpos($table,"miniorange_oauthserver_customer"))
                $tab=$table;
        }
        if($tab) 
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('uninstall_feedback');
            $query->from('#__miniorange_oauthserver_customer');
            $query->where($db->quoteName('id') . " = " . $db->quote(1));
            $db->setQuery($query);
            $fid = $db->loadColumn();
            $tpostData = $post;
            foreach ($fid as $value) 
            {
                if ($value == 0) 
                {
                    foreach ($result as $results) 
                    {
                        if ($results == $id) 
                        {
                            ?>
                            <div class="form-style-6 ">
                                <!-- <span class="mojsp_close">&times;</span> -->
                                <h1> Feedback Form for Oauth Server</h1>
                                <h3>What Happened? </h3>
                                <form name="f" method="post" action="" id="mojsp_feedback">
                                    <input type="hidden" name="mojsp_feedback" value="mojsp_feedback"/>
                                    <div>
                                        <p style="margin-left:2%">
                                            <?php
                                            $deactivate_reasons = array(
                                                "Facing issues During Registration",
                                                "Does not have the features I'm looking for",
                                                "Not able to Configure",
                                                "Other Reasons:"
                                            );


                                            foreach ($deactivate_reasons

                                            as $deactivate_reasons) { ?>

                                        <div class=" radio " style="padding:1px;margin-left:2%">
                                            <label style="font-weight:normal;font-size:14.6px"
                                                   for="<?php echo $deactivate_reasons; ?>">
                                                <input type="radio" name="deactivate_plugin"
                                                       value="<?php echo $deactivate_reasons; ?>" required>
                                                <?php echo $deactivate_reasons; ?></label>
                                        </div>

                                        <?php } ?>
                                        <br>

                                        <textarea id="query_feedback" name="query_feedback" rows="4" style="margin-left:2%"
                                                  cols="50" placeholder="Write your query here"></textarea><br><br><br>

										<tr>
                                            <td width="20%"><strong>Email<span style="color: #ff0000;">*</span>:</strong></td>
                                            <td><input type="email" name="feedback_email" required placeholder="Enter email to contact." style="width:55%"/></td>
                                       </tr>
                                        <?php
                                            foreach($tpostData['cid']  as $key){ ?>
                                            <input type="hidden" name="result[]" value=<?php echo $key ?>>

                                        <?php   } ?>

                                        <br><br>
                                        <div class="mojsp_modal-footer">
                                            <input type="submit" name="miniorange_feedback_submit"
                                                   class="button button-primary button-large" value="Submit"/>
                                        </div>
                                    </div>
                                </form>
                                <form name="f" method="post" action="" id="mojsp_feedback_form_close">
                                    <input type="hidden" name="option" value="mojsp_skip_feedback"/>
                                </form>

                            </div>
                            <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
                            <script>


                                jQuery('input:radio[name="deactivate_plugin"]').click(function () {
                                    var reason = jQuery(this).val();
                                    jQuery('#query_feedback').removeAttr('required')

                                    if (reason == 'Facing issues During Registration') {
                                        jQuery('#query_feedback').attr("placeholder", "Can you please describe the issue in detail?");
                                    } else if (reason == "Does not have the features I'm looking for") {
                                        jQuery('#query_feedback').attr("placeholder", "Let us know what feature are you looking for");
                                    } else if (reason == "Other Reasons:") {
                                        jQuery('#query_feedback').attr("placeholder", "Can you let us know the reason for deactivation");
                                        jQuery('#query_feedback').prop('required', true);
                                    } else if (reason == "Not able to Configure") {
                                        jQuery('#query_feedback').attr("placeholder", "Not able to Configure? let us know so that we can improve the interface");
                                    }
                                });


                                // When the user clicks on <span> (x), mojsp_close the mojsp_modal
                                var span = document.getElementsByClassName("mojsp_close")[0];
                                span.onclick = function () {
                                    mojsp_modal.style.display = "none";
                                    jQuery('#mojsp_feedback_form_close').submit();

                                }


                            </script>
                            <style type="text/css">
                                .form-style-6{
                                    font: 95% Arial, Helvetica, sans-serif;
                                    max-width: 400px;
                                    margin: 10px auto;
                                    padding: 16px;
                                    background: #F7F7F7;
                                }
                                .form-style-6 h1{
                                    background: #43D1AF;
                                    padding: 20px 0;
                                    font-size: 140%;
                                    font-weight: 300;
                                    text-align: center;
                                    color: #fff;
                                    margin: -16px -16px 16px -16px;
                                }
                                .form-style-6 input[type="text"],
                                .form-style-6 input[type="date"],
                                .form-style-6 input[type="datetime"],
                                .form-style-6 input[type="email"],
                                .form-style-6 input[type="number"],
                                .form-style-6 input[type="search"],
                                .form-style-6 input[type="time"],
                                .form-style-6 input[type="url"],
                                .form-style-6 textarea,
                                .form-style-6 select 
                                {
                                    -webkit-transition: all 0.30s ease-in-out;
                                    -moz-transition: all 0.30s ease-in-out;
                                    -ms-transition: all 0.30s ease-in-out;
                                    -o-transition: all 0.30s ease-in-out;
                                    outline: none;
                                    box-sizing: border-box;
                                    -webkit-box-sizing: border-box;
                                    -moz-box-sizing: border-box;
                                    width: 100%;
                                    background: #fff;
                                    margin-bottom: 4%;
                                    border: 1px solid #ccc;
                                    padding: 3%;
                                    color: #555;
                                    font: 95% Arial, Helvetica, sans-serif;
                                }
                                .form-style-6 input[type="text"]:focus,
                                .form-style-6 input[type="date"]:focus,
                                .form-style-6 input[type="datetime"]:focus,
                                .form-style-6 input[type="email"]:focus,
                                .form-style-6 input[type="number"]:focus,
                                .form-style-6 input[type="search"]:focus,
                                .form-style-6 input[type="time"]:focus,
                                .form-style-6 input[type="url"]:focus,
                                .form-style-6 textarea:focus,
                                .form-style-6 select:focus
                                {
                                    box-shadow: 0 0 5px #43D1AF;
                                    padding: 3%;
                                    border: 1px solid #43D1AF;
                                }

                                .form-style-6 input[type="submit"],
                                .form-style-6 input[type="button"]{
                                    box-sizing: border-box;
                                    -webkit-box-sizing: border-box;
                                    -moz-box-sizing: border-box;
                                    width: 100%;
                                    padding: 3%;
                                    background: #43D1AF;
                                    border-bottom: 2px solid #30C29E;
                                    border-top-style: none;
                                    border-right-style: none;
                                    border-left-style: none;    
                                    color: #fff;
                                }

                                .form-style-6 input[type="submit"]:hover,
                                .form-style-6 input[type="button"]:hover{
                                    background: #2EBC99;
                            }
                            </style>
                            <?php
                            exit;
                        }
                    }
                }
            }

        }
    }
	
    function generateRandomString() 
    {  
        $tokenLength = MoOAuthServerUtility::miniOauthFetchDb('#__miniorange_oauthserver_config',array("id"=>'1'),'loadResult','token_length');
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

