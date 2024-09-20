<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  joomla-oauth2-server
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file 
defined('_JEXEC') or die('Restricted Access');
jimport('oauth2serverlib.utility.MoOAuthServerUtility');
JHtml::_('jquery.framework');
JHtml::_('stylesheet', JURI::base() .'components/com_oauth2server/assets/css/miniorange_oauth.css');
JHtml::_('script' ,JURI::base() . 'components/com_oauth2server/assets/js/OAuthServerScript.js');
JHtml::_('stylesheet',JURI::base() . 'components/com_oauth2server/assets/css/miniorange_boot.css');
if(MoOAuthServerUtility::is_curl_installed()==0)
{ ?>
	<p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
  <?php
}
$oauth_active_tab = 'configuration';
$active_tab = JFactory::getApplication()->input->get->getArray();
if(isset($active_tab['tab-panel']) && !empty($active_tab['tab-panel'])){
	$oauth_active_tab = $active_tab['tab-panel'];
}
$isUserEnabled = JPluginHelper::isEnabled('user','oauth2server');
$isSystemEnabled = JPluginHelper::isEnabled('system','oauth2serversystem');
if(!$isSystemEnabled || !$isUserEnabled)
{ 
	?>
	<div id="system-message-container">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <div class="alert alert-error">
            <h4 class="alert-heading">Warning!</h4>
            <div class="alert-message">     
                <h4>This component requires user and System plugin to be activated. Please activate the following 2 plugins to proceed further.</h4>
                <ol>
					<li><?php echo JText::_('PLG_USER_OAUTH2_SERVER'); ?></li>
                    <li><?php echo JText::_('PLG_SYSTEM_OAUTH2_SERVER'); ?></li>
				</ol>
				<br>
                <h4>Steps to activate the plugins.</h4>
                <ol>
					<li>In the top menu, click on Extensions and select Plugins.</li>
                   	<li>Search for OAuth2 in the search box and press 'Search' to display the plugins.</li>
                    <li>Now enable both User and System plugin.</li>
				</ol>
            </div>
        </div>
    </div>
	<?php
}
?>	
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<div class="mo_boot_container-fluid tab-content" id="myTabContent">
	<div class="mo_boot_row">
		<div class="mo_boot_col-lg-12 mo_boot_p-0 mo_boot_m-0" style="background-color :#001b4c; ">	
			<div class="nav-tab-wrapper mo_oauth_server_nav-tab-wrapper mo_boot_p-0 mo_boot_m-0"  id="myTabTabs">   
				<div class="mo_boot_row mo_boot_p-0 mo_boot_m-0">
					<a id="oauthserver_overview" href="#overview"  data-toggle="tab">
						<div onclick="add_css_tab('#oauthserver_overview');" class="mo_boot_col-sm-1 mo_nav-tab <?php echo $oauth_active_tab == 'overview' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_OAUTH2SERVER_TAB1_OVERVIEW'); ?>
						</div>
					</a>
					<a id="configu_id"  href="#configuration"  data-toggle="tab">
						<div onclick="add_css_tab('#configu_id');" class="mo_boot_col-sm-2 mo_nav-tab <?php echo $oauth_active_tab == 'configuration' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_OAUTH2SERVER_TAB1_CONFIGURE_OAUTH'); ?>
						</div>
					</a>
					
					<?php /* Hide tabs for advanced settings and mapping
					
					<a id="advance_settings_id"  href="#advancesettings" data-toggle="tab">
						<div onclick="add_css_tab('#advance_settings_id');" class="mo_boot_col-sm-1 mo_nav-tab <?php echo $oauth_active_tab == 'advancesettings' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_OAUTH2SERVER_TAB2_SETTINGS'); ?>
						</div>
					</a>
					<a id="advanceMapping"  href="#advancemappinng" data-toggle="tab">
						<div onclick="add_css_tab('#advanceMapping');" class="mo_boot_col-sm-2 mo_nav-tab <?php echo $oauth_active_tab == 'advancemapping' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_OAUTH2SERVER_TAB3_ADVANCED_MAPPING'); ?>
						</div>
					</a>
					*/ ?>
					
				</div>
			</div> 
		</div>
	</div>
	<div id="overview" class=" mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'overview' ? 'active' : ''; ?>"  >     
		<div class="mo_boot_col-sm-12">		
			<div class="mo_boot_row">		
				<div class="mo_boot_col-sm-12">
					<?php mo_oauth_server_overview();?>			
				</div>
			</div>				
		</div> 
	</div>
	<div class="mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>"  id="configuration"  >
		<div class="mo_boot_col-sm-12 mo_boot_p-2">
			<?php
				$get = JFactory::getApplication()->input->get->getArray();		
				if(isset($get['pa'])&&($get['pa']==1))
				{
					mo_oauth_server_add_client();
				}
				else if(isset($get['pa'])&&($get['pa']==2))
				{
					mo_oauth_client_list();
				}
				else if(isset($get['pa'])&&($get['pa']==3)&&isset($get['id']))
				{
					mo_oauth_update($get['id']);
				}
				elseif(isset($get['endpoints']) && ($get['endpoints'] =='true'))
				{
					mo_oauth_server_client_config();
				}
				else
				{
					mo_oauth_client_list();
				}
			?>
		</div>
	</div>
	<div id="clientconfig" class=" mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'clientconfig' ? 'active' : ''; ?>"  >     
		<div class="mo_boot_col-sm-12">		
			<div class="mo_boot_row">		
				<div class="mo_boot_col-sm-12">
					<?php mo_oauth_server_client_config();?>			
				</div>
			</div>				
		</div> 
	</div>
	
	<?php /* Hide advanced settings tab
	
	<div style="display:none;" id="advancesettings" class="mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'advancesettings' ? 'active' : ''; ?>"  >
		<div class="mo_boot_col-sm-12">
			<div  class="mo_boot_row" >
				<div class=" mo_boot_col-sm-12">
					<?php mo_oauth_server_advance_settings();?>
				</div>
			</div>	
		</div>
	</div>
	*/ ?>
	
	<?php /*	Hide advanced mapping tab
	
	<div style="display:none;" id="advancemapping" class="mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'advancemapping' ? 'active' : ''; ?>"  >
		<div class="mo_boot_col-sm-12">
			<div  class="mo_boot_row" >
				<div class=" mo_boot_col-sm-12">
					<?php mo_oauth_show_advance_mapping();?>
				</div>
			</div>	
		</div>
	</div>
	*/ ?>
	
</div>
<!-- 
	*End Of Tabs for accountsetup view. 
	*Below are the UI for various sections of Account Creation.
-->

<?php
function mo_oauth_server_overview()
{	
	?>
		<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
			<div class="mo_boot_col-sm-12">
				<div class="mo_boot_row">
					<div class="mo_boot_col-sm-12 mo_boot_mt-4">
						<h3>
							<em>Joomla Single Sign-On - Joomla as OAuth 2.0 Provider</em>	
						</h3>
						<hr class="mo_boot_bg-dark">
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-2">
					<div class="mo_boot_col-sm-7 mo_boot_p-5 mo_boot_text-center">The Joomla OAuth 2.0 Server extension empowers Joomla to serve as OAuth 2.0 server. Other applications can act as OAuth clients and request authorization from the Joomla OAuth 2.0 Server. This allows other applications to implement a Single Sign-On with the Joomla user account.
						<br><br>
						Developed by: <a target="_blank" href="https://plugins.miniorange.com">miniorange</a>. Copyright (c) 2016-2024.
						<br>
						Modified by: <a target="_blank" href="https://github.com/Jefferson49">Jefferson49</a>. Copyright (c) 2024.
					</div>
					<div class="mo_boot_col-sm-5 ">
						<img class="mo_boot_img-fluid" src="<?php echo JURI::root().'administrator\components\com_oauth2server\assets\images\joomla-oauth-server-sso.webp'?>" alt="Joomla Single sign on">
					</div>
				</div>
			</div>
		</div>
	<?php
}


function mo_oauth_client_list() 
{
	$attributes=MoOAuthServerUtility::miniOauthFetchDb('#__oauth2_server_config', TRUE, 'loadAssocList','*');
	
	if ($attributes !== null)
	{
		?>
		<div class="mo_boot_row  mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
			<div class="mo_boot_col-sm-12">
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-6">
						<h3>
							<?php echo JText::_('COM_OAUTH2SERVER_LIST_OF_OAUTH_CLIENTS');?>
						</h3>
					</div>
					<div class="mo_boot_col-sm-6">
						<form name="oauth_mapping_form" method="post" action="<?php echo JRoute::_('index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration&pa=1');?>">
							<input type="submit" id="add_client" name="send_query" id="send_query" value="<?php echo JText::_('COM_OAUTH2SERVER_ADD_CLIENT');?>" class="mo_boot_btn mo_boot_btn-primary mo_boot_float-right" />
							<a onclick="add_css_tab('#configu_id');" href="<?php echo JURI::base().'index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration&endpoints=true';?>"  class="mo_boot_btn mo_boot_btn-primary mo_boot_float-right mo_boot_mx-1" ><?php echo JText::_('COM_OAUTH2SERVER_ENDPOINT_URL');?></a>
						</form>
					</div>
				</div>
				<hr>
				<div class="mo_boot_row mo_boot_mt-2">
					<div class="mo_boot_col-sm-12 mo_boot_table-responsive">
						
						<table class="mo_boot_table mo_boot_table-bordered mo_boot_my-4">
							<tr>
								<th>
									<strong><?php echo JText::_('COM_OAUTH2SERVER_CLIENT_NAME');?></strong>
								</th>
								<th><?php echo JText::_('COM_OAUTH2SERVER_CLIENT_ID');?></th>
								<th><?php echo JText::_('COM_OAUTH2SERVER_CLIENT_SECRET_KEY');?></th>
								<th colspan="2" id="li_client_options"><?php echo JText::_('COM_OAUTH2SERVER_OPTIONS');?></th>
							</tr>

							<?php foreach ($attributes as $attribute) : ?>
								<tr>
									<th id="li_client_name">
										<strong>
											<?php
												echo $attribute['client_name'];
											?>
										</strong>
									</th>
									<th id="li_client_id">
										<span id="client_idkey">
											<?php
												echo $attribute['client_id'];
											?>
										</span>
									</th>
									<th id="li_client_secretkey">
										<span id="client_secretkey">
											<?php echo $attribute['client_secret'];?>
										</span>
									</th>
									<th>
										<?php $route = 'index.php?option=com_oauth2server&view=accountsetup&task=accountsetup.deleteclient&id='.$attribute['id'] ?>
										<form name="f" method="post" action="<?php echo JRoute::_($route) ;?> ">
											<input type="submit" id="li_delete" name="Delete" value="<?php echo JText::_('COM_OAUTH2SERVER_DELETE_CLIENT');?>" class="mo_boot_btn mo_boot_btn-danger" />
										</form>
									</th>
									<th>
										<?php $route = 'index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration&pa=3&id='.$attribute['id'] ?>
										<form name="f" method="post" action="<?php echo JRoute::_($route);?> ">
											<input type="submit" id="li_update" name="upd" value="<?php echo JText::_('COM_OAUTH2SERVER_UPDATE_CLIENT');?>" class="mo_boot_btn mo_boot_btn-primary" />
										</form>
									</th>
								<?php endforeach ?>	
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	   <?php	
	}
	else
	{ 
		?>
		<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
			<div class="mo_boot_col-sm-12">
				<form name="oauth_mapping_form" method="post" action="<?php echo JRoute::_('index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration&pa=1');?>">
					<div class="mo_boot_row mo_boot_mt-3">
						<div class="mo_boot_col-sm-6">
							<h3><?php echo JText::_('COM_OAUTH2SERVER_LIST_OF_OAUTH_CLIENTS');?></h3>
						</div>
						<div class="mo_boot_col-sm-6">
							<input id ="add_client" type="submit" name="send_query"  value="<?php echo JText::_('COM_OAUTH2SERVER_ADD');?>" class="mo_boot_btn mo_boot_btn-success mo_boot_float-right" />
							<a href="<?php echo JURI::base().'index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration&endpoints=true';?>"  class="mo_boot_btn mo_boot_btn-primary mo_boot_float-right mo_boot_mr-1" onclick="add_css_tab('#configu_id');"><?php echo JText::_('COM_OAUTH2SERVER_ENDPOINT_URL');?></a>
						</div>
					</div>
					<div class="mo_boot_row mo_boot_mt-3">
						<div class="mo_boot_col-sm-12 mo_boot_table-responsive">
							<table class="mo_boot_table mo_boot_table-bordered">
								<tr>
									<th>
										<strong><?php echo JText::_('COM_OAUTH2SERVER_CLIENT_NAME');?></strong>
									</th>
									<th>
										<strong><?php echo JText::_('COM_OAUTH2SERVER_CLIENT_ID');?></strong>
									</th>
									<th>
										<strong><?php echo JText::_('COM_OAUTH2SERVER_CLIENT_SECRET_KEY');?></strong>
									</th>
									<th>
										<strong><?php echo JText::_('COM_OAUTH2SERVER_OPTIONS');?></strong>
									</th>
								</tr>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php 
	}
}

function mo_oauth_server_add_client()
{
	?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
		<div class="mo_boot_col-sm-12">
			<div class="mo_boot_row mo_boot_mt-3">
				<div class="mo_boot_col-sm-9">
					<h3><?php echo JText::_('COM_OAUTH2SERVER_CONFIGURE_OAUTH_CLIENT');?></h3>
				</div>
			</div>
			<hr>
			<form  method="post" action="<?php echo JRoute::_('index.php?option=com_oauth2server&view=accountsetup&task=accountsetup.addclient');?>">
				<div class="mo_boot_row mo_boot_mt-3" > 
					<div class="mo_boot_col-sm-3">
						<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_OAUTH2SERVER_CLIENT_NAME');?>:</strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" type="text" id="mo_oauth_custom_client_name" name="mo_oauth_custom_client_name" value="" placeholder= "<?php echo JText::_('COM_OAUTH2SERVER_CLIENT_NAME_PLACEHOLDER');?>">
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_OAUTH2SERVER_AUTHORIZED_REDIRECT_URI');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" required="" type="text" name="mo_oauth_client_redirect_url" value="" placeholder="<?php echo JText::_('COM_OAUTH2SERVER_AUTHORIZED_REDIRECT_URI_PLACEHOLDER');?>">
					</div>
				</div>
				
				<?php /* Dont show advanced features
				<div class="mo_boot_col-sm-11" style="padding-left:0%!important; padding-right:0%!important; padding-top:1rem!important;">
				<details>
					
                 <summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_OAUTH2SERVER_ADVANCED_FEATURES');?></summary>

				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_OAUTH2SERVER_GRANT_TYPE');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<select name="mo_oauth_grant_type" readonly class="mo_boot_form-control" id="mo_oauth_grant_type">
							<option value="" selected> <?php echo JText::_('COM_OAUTH2SERVER_AUTHORIZATION_GRANT_TYPE');?></option>
							<option value=""> <?php echo JText::_('COM_OAUTH2SERVER_IMPLICIT_GRANT_TYPE');?></option>
							<option value=""> <?php echo JText::_('COM_OAUTH2SERVER_PASSWORD_GRANT_TYPE');?></option>
							<option value=""> <?php echo JText::_('COM_OAUTH2SERVER_REFRESH_TOKEN_GRANT_TYPE');?></option>
							<option value=""> <?php echo JText::_('COM_OAUTH2SERVER_CLIENT_CREDENTIALS_GRANT_TYPE');?></option>
						</select>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_PKCE');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input type="radio" name="mo_oauth_enable_pkce" value="1"> <?php echo JText::_('COM_OAUTH2SERVER_ENABLE_PKCE_YES');?>
						<input type="radio" name="mo_oauth_enable_pkce" value="0"> <?php echo JText::_('COM_OAUTH2SERVER_ENABLE_PKCE_NO');?>
						
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_OAUTH2SERVER_TOKEN_EXPIRY');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" required="" type="text" name="mo_oauth_token_expiry" value="3600">
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_OAUTH2SERVER_TOKEN_LENGTH');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" required="" type="text" name="mo_oauth_token_length" value="64">
					</div>
				</div>
                </details>
				</div>

				<div class="mo_boot_row mo_boot_mt-3">
                    <div class="mo_boot_col-sm-12">
						<div class="mo_boot_col-sm-11" style="padding-left:0%!important; padding-right:0%!important">
                        	<details>
                            	<summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_JWT');?></summary>
                            	<div class="mo_boot_row mo_boot_my-3 mo_boot_mx-1">
                                	<div class="mo_boot_col-sm-12 mo_boot_mt-3">
                                    	<input type="checkbox" style="cursor: not-allowed;" id="enablejwt" value="1" name="enablejwt" /> 
                                    	<span style="color: #000000;"><strong><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_JWT');?></strong></span>
                                    	<small><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_JWT_DESCRIPTION');?></small>
                                	</div>
                                	<div class="mo_boot_col-sm-12 mo_boot_mt-3">
                                    	[<a target="_blank" href="https://developers.miniorange.com/docs/oauth/wordpress/server/jwt-support"><b>Click here</b></a><span style="color: #000000;"> to know how this is useful]</span>
                                    	<br><br>
                                   		<p>
										   <?php echo JText::_('COM_OAUTH2SERVER_ENABLE_JWT_NOTE');?>
                                    	</p>
                                    	<hr>
                                	</div>
                                	<div class="mo_boot_col-sm-12">
                                    	<h4 style="color: #000000;"><?php echo JText::_('COM_OAUTH2SERVER_SIGNING_ALGORITHMS');?></h4> 
                                	</div>
                                	<div class="mo_boot_col-sm-12 mo_boot_my-3">
                                    	<table>
                                        	<tr>
                                            	<td>
                                                	<input type="radio" id="hsa" name="mo_server_jwt_encryption" style="cursor: not-allowed;" value="HSA" />&nbsp;HSA&nbsp;&nbsp;
                                                	<input id="rsa" type="radio" name="mo_server_jwt_encryption" style="cursor: not-allowed;" value="RSA"  /> RSA&nbsp;&nbsp;<br><br>
                                                	<input type="button" class="mo_boot_btn mo_boot_btn-primary" style="cursor: not-allowed;" value="<?php echo JText::_('COM_OAUTH2SERVER_DOWNLOAD_CERTIFICAE');?>"> <br><br>
                                            	</td>
                                        	</tr>
                                    	</table>
                                	</div>
                            	</div> 
                        	</details>
						</div>
                    </div>
                </div> 
				*/ ?>				
				
				<div class="mo_boot_row mo_boot_text-center mo_boot_my-4">
					<div class="mo_boot_col-sm-12">
						<input type="submit" name="submit" value="<?php echo JText::_('COM_OAUTH2SERVER_SAVE_CLIENT');?>" class="mo_boot_btn mo_boot_btn-primary" />
						<a href="<?php echo JRoute::_('index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration'); ?>" class="mo_boot_btn mo_boot_btn-danger" ><?php echo JText::_('COM_OAUTH2SERVER_GO_BACK');?></a>
					</div>
				</div> 
			</form>
		</div>
	</div>
	<?php 
}

function mo_oauth_update(int $id){

	$attribute=MoOAuthServerUtility::miniOauthFetchDb('#__oauth2_server_config',array("id" => $id),'loadAssoc','*');
	?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
		<div class="mo_boot_col-sm-12">
			<form name="f" method="post" action=" <?php echo JRoute::_('index.php?option=com_oauth2server&view=accountsetup&task=accountsetup.updateclient');?> ">
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-12">
						<h3><?php echo JText::_('COM_OAUTH2SERVER_CONFIGURE_OAUTH_CLIENT');?></h3>
						<hr>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_OAUTH2SERVER_CLIENT_NAME');?><span class="mo_oauth_highlight">*</span> :</strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<?php echo $attribute['client_name'];?>
						<input class="mo_table_textbox" type="hidden" id="mo_oauth_custom_client_name" name="mo_oauth_custom_client_name" value="<?php echo $attribute['client_name'];?>">
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_OAUTH2SERVER_AUTHORIZED_REDIRECT_URI');?><span class="mo_oauth_highlight">*</span></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" required type="text" name="mo_oauth_client_redirect_url" value="<?php echo $attribute['authorized_uri'];?>" placeholder="<?php echo JText::_('COM_OAUTH2SERVER_AUTHORIZED_REDIRECT_URI_PLACEHOLDER');?>">
					</div>
				</div>
				
				<?php /* Dont show advanced features				
				<div class="mo_boot_col-sm-11" style="padding-left:0%!important; padding-right:0%!important; padding-top:1rem!important">
                    <details>
                        <summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_OAUTH2SERVER_ADVANCED_FEATURES');?></summary>
						<div class="mo_boot_row mo_boot_mt-3" >
							<div class="mo_boot_col-sm-3">
								<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_OAUTH2SERVER_GRANT_TYPE');?></strong>
							</div>
							<div class="mo_boot_col-sm-8">
								<select name="mo_oauth_grant_type" readonly class="mo_boot_form-control" id="mo_oauth_grant_type">
									<option value="" selected> <?php echo JText::_('COM_OAUTH2SERVER_AUTHORIZATION_GRANT_TYPE');?></option>
									<option value=""> <?php echo JText::_('COM_OAUTH2SERVER_IMPLICIT_GRANT_TYPE');?></option>
									<option value=""> <?php echo JText::_('COM_OAUTH2SERVER_PASSWORD_GRANT_TYPE');?></option>
									<option value=""> <?php echo JText::_('COM_OAUTH2SERVER_REFRESH_TOKEN_GRANT_TYPE');?><</option>
									<option value=""> <?php echo JText::_('COM_OAUTH2SERVER_CLIENT_CREDENTIALS_GRANT_TYPE');?></option>
								</select>
							</div>
						</div>
						<div class="mo_boot_row mo_boot_mt-3" >
							<div class="mo_boot_col-sm-3">
								<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_PKCE');?></strong>
							</div>
							<div class="mo_boot_col-sm-8">
								<input type="radio" name="mo_oauth_enable_pkce" value="1"> <?php echo JText::_('COM_OAUTH2SERVER_ENABLE_PKCE_YES');?>
								<input type="radio" name="mo_oauth_enable_pkce" value="0"> <?php echo JText::_('COM_OAUTH2SERVER_ENABLE_PKCE_NO');?>
							</div>
						</div>
						<div class="mo_boot_row mo_boot_mt-3" >
							<div class="mo_boot_col-sm-3">
								<strong><?php echo JText::_('COM_OAUTH2SERVER_TOKEN_EXPIRY');?></strong>
							</div>
							<div class="mo_boot_col-sm-8">
								<input class="mo_boot_form-control" required="" type="text" name="mo_oauth_token_expiry" value="3600">
							</div>
						</div>
						<div class="mo_boot_row mo_boot_mt-3" >
							<div class="mo_boot_col-sm-3">
								<strong><?php echo JText::_('COM_OAUTH2SERVER_TOKEN_LENGTH');?></strong>
							</div>
							<div class="mo_boot_col-sm-8">
								<input class="mo_boot_form-control" required="" type="text" name="mo_oauth_token_length" value="64">
							</div>
						</div>
					</details>
				</div>
				
				<div class="mo_boot_row mo_boot_mt-3">
                    <div class="mo_boot_col-sm-12">
					<div class="mo_boot_col-sm-11" style="padding-left:0%!important; padding-right:0%!important">
                        <details>
						<summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_JWT');?></summary>
                            <div class="mo_boot_row mo_boot_my-3 mo_boot_mx-1" style="background:white;border:1px solid #226a8b;border-radius:5px;">
                                <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                                    <input type="checkbox" style="cursor: not-allowed;" id="enablejwt" value="1" name="enablejwt" /> 
                                    <span style="color: #000000;"><strong><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_JWT');?></strong></span>
                                    <small><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_JWT_DESCRIPTION');?></small>
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                                    [<a target="_blank" href="https://developers.miniorange.com/docs/oauth/wordpress/server/jwt-support"><b>Click here</b></a><span style="color: #000000;"> to know how this is useful]</span>
                                    <br><br>
                                    <p>
										<?php echo JText::_('COM_OAUTH2SERVER_ENABLE_JWT_NOTE');?>
                                    </p>
                                    <hr>
                                </div>
                                <div class="mo_boot_col-sm-12">
                                    <h4 style="color: #000000;"><?php echo JText::_('COM_OAUTH2SERVER_SIGNING_ALGORITHMS');?></h4>
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_my-3">
                                    <table>
                                        <tr>
                                            <td>
                                                <input type="radio" id="hsa" name="mo_server_jwt_encryption" style="cursor: not-allowed;" value="HSA" />&nbsp;HSA&nbsp;&nbsp;
                                                <input id="rsa" type="radio" name="mo_server_jwt_encryption" style="cursor: not-allowed;" value="RSA"  /> RSA&nbsp;&nbsp;<br><br>
                                                <input type="button" class="mo_boot_btn mo_boot_btn-primary" style="cursor: not-allowed;" value="<?php echo JText::_('COM_OAUTH2SERVER_DOWNLOAD_CERTIFICAE');?>"> <br><br>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div> 
                        </details>
					</div>
                    </div>
                </div> 
				*/ ?>				
				
				<div class="mo_boot_row mo_boot_my-4 mo_boot_text-center">
					<div class="mo_boot_col-sm-12">
						<input type="hidden" name="id"  value="<?php echo $id ?>"/>
						<button name="upd" type="submit" class="mo_boot_btn mo_boot_btn-primary"><?php echo JText::_('COM_OAUTH2SERVER_UPDATE_CLIENT');?></button>
						<button class="mo_boot_btn mo_boot_btn-danger" onclick="cancel_update()" ><?php echo JText::_('COM_OAUTH2SERVER_GO_BACK');?></button>
					</div>
				</div>
			</form>
			<form name="f" id="cancelUpdate" method="post" action="<?php echo JRoute::_('index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration'); ?>">
				<input type="hidden" name="bck"  value="Back"/>
			</form>
		</div>
	</div>
	<?php
}


function mo_oauth_server_advance_settings()
{
	?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box"> 
		<div class="mo_boot_col-sm-12 mo_boot_mt-3">
			<div class="mo_boot_row">
				<div class="mo_boot_col-sm-12">
					<h3><?php echo JText::_('COM_OAUTH2SERVER_ADVANCE_SETTINGS');?></h3>
					<hr>
				</div>
			</div>
			<div class="mo_boot_row">
				<div class="mo_boot_col-sm-12">
					<details open>
						<summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_OAUTH2SERVER_STATE_PARAMETER');?></summary>
						<hr>
						<div class="mo_boot_row mo_boot_my-4">
							<div class="mo_boot_col-sm-12">
								<input type="checkbox" name="mo_oauth_auto_redirect"  id="mo_oauth_auto_redirect" value="1" style="float:left; margin-top:5px" /><label style="float:left; margin-left:5px" for="mo_oauth_auto_redirect">&nbsp;<?php echo JText::_('COM_OAUTH2SERVER_ENABLE_AUTHORIZE');?></label>
								<p style="padding-top:2rem ;"> <?php echo JText::_('COM_OAUTH2SERVER_STATE_PARAMTER_DESCRIPTION');?></p>
							</div>
						</div>
					</details>

					<details open>
						<summary class="mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_OAUTH2SERVER_PROTECT_ADMIN_LOGIN_PAGE_URL');?></summary><hr>
						<div class="mo_boot_col-sm-12 mo_boot_mt-4">
							<p><?php echo JText::_('COM_OAUTH2SERVER_PROTECT_ADMIN_LOGIN_PAGE_URL_DETAILS');?></p>
						</div>
						<div class="mo_boot_col-sm-12">
							<div class="mo_boot_row  mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_OAUTH2SERVER_ENABLE_CUSTOM_LOGIN_PAGE_URL');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<input type="checkbox"/>
								</div>
							</div>
							<div class="mo_boot_row  mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_OAUTH2SERVER_ACCESS_KEY_FOR_YOUR_ADMIN_LOGIN_URL');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<input class="mo_boot_form-control" type="text" placeholder="<?php echo JText::_('COM_OAUTH2SERVER_ACCESS_KEY_FOR_YOUR_ADMIN_LOGIN_URL_PLACEHOLDER');?>"/>
								</div>
							</div>
							<div class="mo_boot_row  mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_OAUTH2SERVER_CURRENT_ADMIN_LOGIN_URL');?></p>
								</div>
								<div class="mo_boot_col-sm-8 mo_boot_text-wrap">
									<input type="text" class="mo_boot_form-control" name=""  placeholder="<?php echo JURI::base();?>">
								</div>
							</div>
							<div class="mo_boot_row  mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ADMIN_LOGIN_URL');?></p>
								</div>
								<div class="mo_boot_col-sm-8 mo_boot_text-wrap">
									<input type="text" class="mo_boot_form-control" name=""  placeholder="<?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ADMIN_LOGIN_URL_PLACEHOLDER');?>">
								</div>
							</div>
							<div class="mo_boot_row mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_OAUTH2SERVER_REDIRECT_AFTER_FAILED_RESPONSE');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<select class="mo_boot_form-control" id="failure_response" >
										<option><?php echo JText::_('COM_OAUTH2SERVER_REDIRECT_TO_HOMEPAGE');?></option>
										<option><?php echo JText::_('COM_OAUTH2SERVER_REDIRECT_TO_CUSTOM_404_MESSAGE');?></option>
										<option><?php echo JText::_('COM_OAUTH2SERVER_REDIRECT_TO_CUSTOM_REDIRECT_URL');?></option>
									</select>
								</div>
							</div>
							<div class="mo_boot_row mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_REDIRECT_URL_AFTER_FAILURE');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<input class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_REDIRECT_URL_AFTER_FAILURE_PLACEHOLDER');?>" type="text"/>
								</div>
							</div>
							<div class="mo_boot_row  mo_boot_mt-3" id="custom_message">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ERROR_MESSAGE_AFTER_FAILURE');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<textarea style="height:100% !important" class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ERROR_MESSAGE_AFTER_FAILURE_PLACEHOLDER');?>"></textarea>
								</div>
							</div>
						</div>
						<div class="mo_boot_col-sm-12  mo_boot_mt-4  mo_boot_text-center">
							<input type="submit" class="mo_boot_btn mo_boot_btn-primary" value="<?php echo JText::_('COM_OAUTH2SERVER_SAVE_ADMIN_LOGIN_PAGE_URL_SETTINGS');?>"/>
						</div>
					</details>
				</div>
			</div>
		</div>
	</div>

 <?php
}


function mo_oauth_server_client_config() 
{
	?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
		<div class="mo_boot_col-sm-12">
			<div class="mo_boot_row mo_boot_mt-3">
				<div class="mo_boot_col-sm-11">
					<h2><?php echo JText::_('COM_OAUTH2SERVER_ENDPOINT_URI');?></h2>
				</div>
				<div class="mo_boot_col-sm-1">
				<a href="<?php echo JRoute::_('index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration'); ?>" class="mo_boot_btn mo_boot_btn-danger" ><?php echo JText::_('COM_OAUTH2SERVER_ENDPOINT_BACK');?></a>
				</div>
				<div class="mo_boot_col-sm-12">
					<p><?php echo JText::_('COM_OAUTH2SERVER_ENDPOINT_DESCRIPTION');?><p>
					<hr>
				</div>
			</div>
			
			<div class="mo_boot_col-sm-12 ">
				<table class="mo_boot_table mo_boot_table-bordered">
					<tr>
						<th>
							<strong><?php echo JText::_('COM_OAUTH2SERVER_AUTHORIZE_ENDPOINT');?> </strong> :
						</th>
						<td>	
							<span id="auth_endpoint" ><?php echo JURI::root()."index.php" ?></span> 
							<em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip"; onclick="copyToClipboard('#auth_endpoint');" style="color:red; cursor: pointer;";  ><span class="copytooltiptext">Copied!</span> </em> 
						</td>
								
					</tr>
					<tr>
						<th>
							<strong><?php echo JText::_('COM_OAUTH2SERVER_ACCESS_TOKEN_ENDPOINT');?> </strong> :
						</th>
						<td>
							<span id="acc_token_enpoint"><?php echo JURI::root()."index.php" ?></span>
							<em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip"; onclick="copyToClipboard('#acc_token_enpoint');" style="color:red; cursor: pointer;";  >
							<span class="copytooltiptext">Copied!</span> </em>
					
						</td>			
					</tr>
					<tr>
						<th >
							<strong><?php echo JText::_('COM_OAUTH2SERVER_GET_USER_INFO_ENDPOINT');?> </strong> :
						</th>
						<td>
							<span id="user_info_endpoint"><?php echo JURI::root()."index.php"; ?></span>
							<em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip"; onclick="copyToClipboard('#user_info_endpoint');" style="color:red; cursor: pointer;";  >
							<span class="copytooltiptext">Copied!</span> </em>
						</td>		
					</tr>
					<tr>
						<th>
							<strong><?php echo JText::_('COM_OAUTH2SERVER_SCOPE');?> </strong> : 
						</th>
						<td>
							<?php echo JText::_('COM_OAUTH2SERVER_SCOPE_EMAIL');?>
						</td>		
					</tr>
				</table>
			</div>
			
		</div>
	</div>
	<?php
}

function mo_oauth_show_advance_mapping()
{
	?>
	<div class="mo_boot_row mo_boot_my-3 mo_boot_mx-1 mo_OAuth_box" style="border-radius:5px;">
		<div class="mo_boot_col-sm-12 mo_boot_mt-3">
		<h3><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTE_MAPPING');?></h3><hr>
		</div>
		<div class="mo_boot_col-sm-12 mo_boot_m-3 mo_boot_p-2" style="background:#e0e0e0">
			<p><strong><?php echo JText::_('COM_OAUTH2SERVER_JOOMLA_USER_FIELD_ATTRIBUTE_NAME');?></strong><?php echo JText::_('COM_OAUTH2SERVER_JOOMLA_USER_FIELD_ATTRIBUTE_NAME_DESCRIPTION');?></p>
			<p><strong><?php echo JText::_('COM_OAUTH2SERVER_JOOMLA_USER_FIELD_ATTRIBUTE_VALUE');?></strong><?php echo JText::_('COM_OAUTH2SERVER_JOOMLA_USER_FIELD_ATTRIBUTE_VALUE_DESCRIPTION');?></p>
		</div>
		<div class="mo_boot_col-sm-12 mo_boot_mt-5 mo_boot_mx-4">
			<div class="mo_boot_row">
				<?php
					for($icnt = 1; $icnt <= 5; $icnt++)
					{
						?>
						<div class="mo_boot_col-sm-6">
								<div class="mo_boot_row mo_boot_mx-2">
									<div class="mo_boot_col-sm-4">
										<strong><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTE');?> <?php echo $icnt ?> <?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTE_NAME');?></strong>
									</div>
									<div class="mo_boot_col-sm-7">
										<input type="text" class="mo_oauth_server_textfield mo_boot_form-control" placeholder="<?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTE_PLACEHOLDER');?>"/>
									</div>
								</div>
						</div>
						<div class="mo_boot_col-sm-6">
							<div class="mo_boot_row mo_boot_mx-4">
								<div class="mo_boot_col-sm-4">
									<strong><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTE');?> <?php echo $icnt;?> <?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTE_VALUE');?></strong>
								</div>
								<div class="mo_boot_col-sm-7">
									<select class="mo_oauth_server_textfield mo_boot_form-control">
										<option value=""><?php echo JText::_('COM_OAUTH2SERVER_SELECT_CUSTOM_ATTRIBUTE');?></option>
										<option value="emailAddress"><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTES_EMAIL_ADDRESS');?></option>
										<option value="username"><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTES_USERNAME');?></option>
										<option value="name"><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTES_NAME');?></option>
										<option value="firstname"><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTES_FIRST_NAME');?></option>
										<option value="lastname"><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTES_LAST_NAME');?></option>
										<option value="groups"><?php echo JText::_('COM_OAUTH2SERVER_CUSTOM_ATTRIBUTES_GROUPS');?></option>
									</select>
								</div>
							</div><br>
						</div>
						<?php
					}
				?>
			</div>
		</div>
		<div class="mo_boot_col-sm-12 mo_boot_mt-5 mo_boot_mb-3 mo_boot_text-center">
			<input type="submit" class="mo_boot_btn mo_boot_btn-primary " value="<?php echo JText::_('COM_OAUTH2SERVER_SAVE_ADDITIONAL_USER_ATTRIBUTE_MAPPING');?>"/>
		</div><br><br>
	</div>
	<?php
}

?>