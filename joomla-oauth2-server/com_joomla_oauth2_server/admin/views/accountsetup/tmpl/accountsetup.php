<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauthserver
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file 
defined('_JEXEC') or die('Restricted Access');
jimport('miniorangeoauthserver.utility.MoOAuthServerUtility');
JHtml::_('jquery.framework');
JHtml::_('stylesheet', JURI::base() .'components/com_miniorange_oauthserver/assets/css/miniorange_oauth.css');
JHtml::_('script' ,JURI::base() . 'components/com_miniorange_oauthserver/assets/js/OAuthServerScript.js');
JHtml::_('stylesheet',JURI::base() . 'components/com_miniorange_oauthserver/assets/css/miniorange_boot.css');
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
$isUserEnabled = JPluginHelper::isEnabled('user','miniorangeoauthserver');
$isSystemEnabled = JPluginHelper::isEnabled('system','mooauthserver');
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
					<li>plg_user_miniorangeoauthserver</li>
                    <li>System - miniOrange OAuth Server</li>
				</ol>
				<br>
                <h4>Steps to activate the plugins.</h4>
                <ol>
					<li>In the top menu, click on Extensions and select Plugins.</li>
                   	<li>Search for miniOrange in the search box and press 'Search' to display the plugins.</li>
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
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TAB1_OVERVIEW'); ?>
						</div>
					</a>
					<a id="configu_id"  href="#configuration"  data-toggle="tab">
						<div onclick="add_css_tab('#configu_id');" class="mo_boot_col-sm-2 mo_nav-tab <?php echo $oauth_active_tab == 'configuration' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TAB1_CONFIGURE_OAUTH'); ?>
						</div>
					</a>
					<a id="advance_settings_id"  href="#advancesettings" data-toggle="tab">
						<div onclick="add_css_tab('#advance_settings_id');" class="mo_boot_col-sm-1 mo_nav-tab <?php echo $oauth_active_tab == 'advancesettings' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TAB2_SETTINGS'); ?>
						</div>
					</a>
					<a id="advanceMapping"  href="#advancemappinng" data-toggle="tab">
						<div onclick="add_css_tab('#advanceMapping');" class="mo_boot_col-sm-2 mo_nav-tab <?php echo $oauth_active_tab == 'advancemapping' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TAB3_ADVANCED_MAPPING'); ?>
						</div>
					</a>
					<a id="licensing_planid"  href="#licensing-plans" data-toggle="tab">
						<div onclick="add_css_tab('#licensing_planid');" class="mo_boot_col-sm-2 mo_nav-tab <?php echo $oauth_active_tab == 'license' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TAB5_LICENSING_PLANS'); ?>
						</div>
					</a>
					<a id="account_setup"  href="#description"  data-toggle="tab">
						<div onclick="add_css_tab('#account_setup');" class="mo_boot_col-sm-2 mo_nav-tab <?php echo $oauth_active_tab == 'account' ? 'mo_nav_tab_active' : ''; ?>">
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TAB6_ACCOUNT_SETUP'); ?>
						</div>
					</a>
					<a href="<?php echo JURI::base()?>index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=requestdemo">
						<div class="mo_boot_col-sm-2  mo_boot_bg-success mo_nav-tab">
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT');?>
						</div>
					</a>
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
	<div class="mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'account' ? 'active' : ''; ?>" id="description"  >
		<div class="mo_boot_col-sm-12 ">
			<div class="mo_boot_row">
				<div class="mo_boot_col-sm-12">
					<?php
						$customer_details = MoOAuthServerUtility::getCustomerDetails();						
						$login_status = $customer_details['login_status'];
						$registration_status = $customer_details['registration_status'];
						if($login_status)
						{
							mo_oauth_login_page();
						}
						else if($registration_status == 'MO_OTP_DELIVERED_SUCCESS' || $registration_status == 'MO_OTP_VALIDATION_FAILURE' || $registration_status == 'MO_OTP_DELIVERED_FAILURE')
						{
							mo_otp_show_otp_verification();
						}
						else if(! MoOAuthServerUtility::is_customer_registered())
						{
							mo_oauth_registration_page();
						}
						else
						{
							mo_oauth_account_page();
						}
					?>
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
				else if(isset($get['pa'])&&($get['pa']==3))
				{
					mo_oauth_update();
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
	<div id="licensing-plans" class=" mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'license' ? 'active' : ''; ?>"  >
		<div class="mo_boot_col-sm-12">
			<?php  
				$customer_details = MoOAuthServerUtility::getCustomerDetails();						
				$email = $customer_details['email'];					
				$hostName = 'https://www.miniorange.com';
				$loginUrl = $hostName . '/contact';
				echo mo_oauth_server_licensing_plan();
			?>
		</div>
	</div>
	<div id="requestdemo" class="mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'requestdemo' ? 'active' : ''; ?>"  >
		<div class="mo_boot_col-sm-12">
			<div  class="mo_boot_row">				
				<div class=" mo_boot_col-sm-8">
					<?php mo_oauth_server_request_demo();?>			
				</div>
				<div  class="mo_boot_col-sm-4">
					<?php echo mo_oauth_server_support(); ?>
				</div>
			</div>				
		</div>
	</div>
	<div id="advancesettings" class="mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'advancesettings' ? 'active' : ''; ?>"  >
		<div class="mo_boot_col-sm-12">
			<div  class="mo_boot_row" >
				<div class=" mo_boot_col-sm-12">
					<?php mo_oauth_server_advance_settings();?>
				</div>
			</div>	
		</div>
	</div>
	<div id="advancemappinng" class="mo_boot_row mo_oauth_server_tabs tab-pane <?php echo $oauth_active_tab == 'advancemapping' ? 'active' : ''; ?>"  >
		<div class="mo_boot_col-sm-12">
			<div  class="mo_boot_row" >
				<div class=" mo_boot_col-sm-12">
					<?php mo_oauth_show_advance_mapping();?>
				</div>
			</div>	
		</div>
	</div>
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
							<em>miniOrange Joomla Single Sign-On - Joomla as OAuth Provider</em>	
						</h3>
						<hr class="mo_boot_bg-dark">
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-2">
					<div class="mo_boot_col-sm-7 mo_boot_p-5 mo_boot_text-center">
						<strong>The Joomla as OAuth Server plugin empowers Joomla-powered platforms with the capability to serve as OAuth Servers. This plugin revolutionizes user access management by allowing third-party applications to securely request and access specific user data without requiring sensitive login information. Acting as an intermediary, Joomla grants users the authority to grant controlled permissions to external apps. This seamless integration enhances user privacy, strengthens security, and streamlines access across interconnected services. With the OAuth Server plugin, Joomla becomes a versatile hub for controlled data sharing, creating a more secure and interconnected digital ecosystem.</strong>
						<br><br>
						<a class="mo_boot_btn mo_boot_btn-primary" target="_blank" href="https://plugins.miniorange.com/joomla-oauth-server"> Visit Site</a>
						<a class="mo_boot_btn mo_boot_btn-primary" href="<?php echo JURI::root().'administrator/index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license';?>"> Licensing plans</a>
						<a class="mo_boot_btn mo_boot_btn-primary" target="_blank" href="https://plugins.miniorange.com/joomla-oauth-server-guides"> Guides</a>
						<a class="mo_boot_btn mo_boot_btn-primary" target="_blank" href="https://faq.miniorange.com/kb/joomla/"> FAQs</a>
					</div>
					<div class="mo_boot_col-sm-5 ">
						<img class="mo_boot_img-fluid" src="<?php echo JURI::root().'administrator\components\com_miniorange_oauthserver\assets\images\joomla-oauth-server-sso.webp'?>" alt="Joomla Single sign on">
					</div>
				</div>
			</div>
		</div>
	<?php
}
function mo_oauth_login_page() 
{
	$customer_details = MoOAuthServerUtility::getCustomerDetails();						
	$admin_email = $customer_details['email'];
	?>	
	<div class="mo_boot_row mo_boot_m-1">
        <div class="mo_boot_container-fluid mo_boot_py-4">
            <div class="card no_boot_my-2" style="box-shadow: -2 3px 7px 2px hsl(214deg 17.95% 5.21%);">
                <div class="card-body">
                    <div class="mo_boot_col-sm-11 mo_boot_mt-3 mo_boot_text-center">
                        <h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_LOGIN_WITH_MINIORANGE');?></h3><hr style="1px solid rgb(116 107 107 / 72%)">   
                    </div>
                    <div class="mo_boot_col-sm-12 mo_boot_offset-2" style="width:62%">
						<form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.verifyCustomer');?>">   
                            <div class="mo_boot_row mo_boot_mt-3 mo_boot_mx-4">
                                <div class="mo_boot_col-sm-12" style="font-weight:500;">
									<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_EMAIL');?>:
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_mt-1">
								<input class="mo_boot_form-control oauth-textfield" type="email" name="email" id="email"
									required placeholder="person@example.com"
									value="<?php echo $admin_email; ?>" />
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_mt-3 mo_boot_mx-4" style="font-weight:500;">
                                <div class="mo_boot_col-sm-12 ">
									<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PASSWORD');?>:
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_mt-1">
								<input class="mo_boot_form-control oauth-textfield" required type="password"
									name="password" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_MINIORANGE_PASSWORD_PLACEHOLDER');?>" />
								
                                    <a class="mo_boot_forgot_phn mo_boot_mt-1" style="color:#737e7ce3;font-family: ui-sans-serif;float:left;cursor:pointer;" href="/moas/idp/resetpassword" target="_blank"><u>Reset password</u></a>
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_my-4">
                        
                                <div class="mo_boot_col-sm-12 mo_boot_text-center">
                                    <input type="submit" name="register_or_login" class="mo_boot_btn mo_boot_btn-primary" style="color:#fff;width: 60%;border-radius:21px" value="Login" />
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_text-center mo_boot_mt-2">
                                    <span class="mo_boot_forgot_phn " style="color:#403b3b;font-family: ui-sans-serif;" >Don't have an account yet</span><br>
                                    <a class="mo_boot_forgot_phn " style="color:#403b3b;font-family: ui-sans-serif;cursor:pointer;" href="https://www.miniorange.com/businessfreetrial" target='_blank' ><u> Create an account</u></a>     
                                </div>
                            </div> 
                        </form>    
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
}

/* Show otp verification page*/
function mo_otp_show_otp_verification(){
?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
		<form name="f" method="post" style="width:100%;" id="otp_form" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.validateOtp');?>">
			<div class="mo_boot_col-sm-12 mo_boot_mt-2">
				<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_VERIFY_YOUR_EMAIL');?></h3>
				<hr>
			</div>
			<div class="mo_boot_col-sm-12 mo_boot_mt-2">
				<div class="mo_boot_row mo_boot_my-4">
					<div class="mo_boot_col-sm-2">
						<strong><span style="color:#FF0000">*</span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENTER_OTP');?>:</strong>
					</div>
					<div class="mo_boot_col-sm-7">
						<input class="mo_boot_form-control"  autofocus="true" type="text" name="otp_token" required placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENTER_OTP_PLACEHOLDER');?>" />
					</div>
				</div>
				<div class="mo_boot_row mo_boot_my-3">
					<div class="mo_boot_col-sm-12 mo_boot_text-center">
						
						<input type="submit" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_VALIDATE_OTP');?>" class="mo_boot_btn mo_boot_btn-primary" />
						<a href="#mo_otp_resend_otp_email" class="mo_boot_btn mo_boot_btn-primary" onclick="resend_otp()" ><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_RESEND_OTP_OVER_EMAIL');?></a>
						<i id="back_btn" onclick="back_btn()" class="mo_boot_btn mo_boot_btn-danger"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_OTP_BACK_BUTTON');?></i>
					</div> 
				</div>
				<hr>
			</div>
		</form>
		<div class="mo_boot_col-sm-12 mo_boot_mt-3">
			<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_DID_NOT_RECIEVE_OTP');?></h3>
		</div>
		<div class="mo_boot_col-sm-12 mo_boot_mt-2">
			<form id="phone_verification" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.phoneVerification');?>">
				<div class="mo_boot_row">
					<div class="mo_boot_col-sm-12">
						<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_DID_NOT_RECIEVE_OTP_NOTE1');?></p>
						<p class="mo_boot_mt-4">
							<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENTER_VALID_PHONE_NUMBER');?></strong>
						</p>
					</div>
					<div class="mo_boot_col-sm-12 mo_boot_my-3">
						<div class="mo_boot_row">
							<div class="mo_boot_col-sm-6">
								<input class="mo_boot_form-control" required="true" pattern="[\+]\d{1,3}\d{10}" autofocus="true" type="text" name="phone_number" id="phone_number" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENTER_VALID_PHONE_NUMBER_PLACEHOLDER');?>"  title="Enter phone number without any space or dashes with country code."/>
							</div>
							<div class="mo_boot_col-sm-6">
								<input type="submit" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SEND_OTP');?>" class="mo_boot_btn mo_boot_btn-primary" />
							</div>
						</div>	
					</div>
				</div>	
			</form>
		</div>
	</div>
	<form method="post" id="mo_otp_cancel_form" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.cancelform');?>" >
	</form> 
	<form name="f" id="resend_otp_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.resendOtp');?>">
	</form>
<?php
}

/* Create Customer function */
function mo_oauth_registration_page(){
	$current_user = JFactory::getUser();
	?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
		<div class="mo_boot_col-sm-12">
			<form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.registerCustomer');?>">
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-9">
						<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REGISTER_WITH_MINIORANGE');?></h3>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-12">
						<hr>
						<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REGISTER_WITH_MINIORANGE_DETAILS1');?></p>
						<p style="color: #fa2727">
						<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REGISTER_WITH_MINIORANGE_DETAILS2');?> 
							<a href="https://www.miniorange.com/businessfreetrial" target="_blank"><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REGISTER_WITH_MINIORANGE_DETAILS3');?></strong></a> 
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REGISTER_WITH_MINIORANGE_DETAILS4');?></br>
						</p>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-3">
						<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_EMAIL');?>:</strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input id="email" class="mo_boot_form-control mo_OAuth_textbox_border" type="email" name="email"
							required placeholder="person@example.com"
							value="<?php echo $current_user->email;?>" />
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-3">
						<strong><span class="mo_oauth_highlight" style="padding-left: 3px;"> </span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PHONE_NUMBER');?>:</strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input id="rg_phone" class="mo_boot_form-control mo_OAuth_textbox_border" type="tel" id="phone"
							pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" name="phone"
							title="Phone with country code eg. +1xxxxxxxxxx"
							placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PHONE_NUMBER_PLACEHOLDER');?>"
							/>
						<em><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PHONE_NUMBER_NOTE');?></em>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-3">
						<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PASSWORD');?>:</strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input id="rg_passwd" class="mo_boot_form-control mo_OAuth_textbox_border" required type="password"
							name="password" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PASSWORD_PLACEHOLDER');?>" />
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-3">
						<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CONFIRM_PASSWORD');?>:</strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input  id="rg_repasswd" class="mo_boot_form-control mo_OAuth_textbox_border" required type="password"
							name="confirmPassword" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CONFIRM_PASSWORD_PLACEHOLDER');?>" />
					</div>
				</div>
				<div class="mo_boot_row mo_boot_text-center mo_boot_my-5">
					<div class="mo_boot_col-sm-12">
						<input type="submit" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REGISTER');?>" class="mo_boot_btn mo_boot_btn-primary" />
						<a href="#oauth_account_exist" onclick="oauth_account_exist()" class="mo_boot_btn mo_boot_btn-primary"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ALREADY_REGISTERED_WITH_MINIORANGE');?></a>
					</div>
				</div>
			</form>
		</div>
	</div>
	<form name="f" id="resend_otp_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.customerLoginForm');?> ">
	</form>
	<?php
}


function mo_oauth_account_page() 
{	
	$result = MoOAuthServerUtility::getCustomerDetails();	
	$email = $result['email'];
	$customer_key = $result['customer_key'];
	$api_key = $result['api_key'];
	$customer_token = $result['customer_token'];

	
	$moPluginVersion = MoOAuthServerUtility::GetPluginVersion();
	$j_version = new JVersion;
	$jcms_version = $j_version->getShortVersion();
	$php_version = phpversion();
	?>
	<div class="mo_boot_row  mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
		<div class="mo_boot_col-sm-12 mo_boot_mt-3">
			<div class="mo_oauthserver_welcome_message"><h4><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_THANK_YOU_FOR_REGISTERING');?></h4></div><br>
		</div>
		<div class="mo_boot_col-sm-12 mo_boot_mt-3">
			<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_USER_PROFILE');?></h3><hr><br>
		</div>
		<div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_table-responsive">
			<table	class="mo_boot_table mo_boot_table-striped mo_boot_table-hover mo_boot_table-bordered">
				<tr>
					<td ><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_USER_USERNAME_EMAIL');?></strong></td>
					<td ><?php echo $email?></td>
				</tr>
				<tr>
					<td ><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOMER_ID');?></strong></td>
					<td ><?php echo $customer_key?></td>
				</tr>
				<tr>
					<td ><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PLUGIN_VERSION');?></strong></td>
					<td ><?php echo $moPluginVersion?></td>
				</tr>
				<tr>
					<td ><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_JOOMLA_VERSION');?></strong></td>
					<td ><?php echo $jcms_version?></td>
				</tr>
				<tr>
					<td ><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PHP_VERSION');?></strong></td>
					<td ><?php echo $php_version?></td>
				</tr>
			</table>
		</div>
		<div class="mo_boot_col-sm-12 mo_boot_my-3 mo_boot_text-center">
			<form action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&task=accountsetup.ResetAccount'); ?>" name="reset_useraccount" method="post"> 
				<input type="button" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REMOVE_ACCOUNT');?>" onclick='submit();' class="mo_boot_btn mo_boot_btn-danger"/>
			</form>
		</div>
	</div>
	
	<form id="otp_forgot_password_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.forgotpassword');?>">
		<input type="hidden" name="current_admin_email" id="current_admin_email" value="" />
	</form>

	<?php
}
function mo_oauth_client_list() 
{
	$attribute=MoOAuthServerUtility::miniOauthFetchDb('#__miniorange_oauthserver_config',array("id"=>1),'loadAssoc','*');
	
	if($attribute['client_count']>0)
	{ 
		?>
		<div class="mo_boot_row  mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
			<div class="mo_boot_col-sm-12">
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-6">
						<h3>
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_LIST_OF_OAUTH_CLIENTS');?>
						</h3>
					</div>
					<div class="mo_boot_col-sm-6">
						<input type="submit" id="dis_btn" name="send_query" id="send_query" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ADD_CLIENT');?>" class="mo_boot_btn mo_boot_btn-primary mo_boot_float-right" disabled />
						<a  onclick="add_css_tab('#configu_id');" href="<?php echo JURI::base().'index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=configuration&endpoints=true';?>"  class="mo_boot_btn mo_boot_btn-primary mo_boot_float-right mo_boot_mx-1" ><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENDPOINT_URL');?></a>
					</div>
				</div>
				<hr>
				<div class="mo_boot_row mo_boot_mt-2">
					<div class="mo_boot_col-sm-12">
						<span color="red"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ONLY_ONE_CLIENT1');?> <a href="index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license" rel="noopener noreferrer"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ONLY_ONE_CLIENT2');?></a> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ONLY_ONE_CLIENT3');?></span>	
					</div>
					<div class="mo_boot_col-sm-12 mo_boot_table-responsive">
						
						<table class="mo_boot_table mo_boot_table-bordered mo_boot_my-4">
							<tr>
								<th>
									<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_NAME');?></strong>
								</th>
								<th><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_ID');?></th>
								<th><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_SECRET_KEY');?></th>
								<th colspan="2" id="li_client_options"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_OPTIONS');?></th>
							</tr>
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
									<form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.deleteclient') ;?> ">
										<input type="submit" id="li_delete" name="Delete" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_DELETE_CLIENT');?>" class="mo_boot_btn mo_boot_btn-danger" />
									</form>
								</th>
								<th>
									<form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=configuration&pa=3');?> ">
										<input type="submit" id="li_update" name="upd" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_UPDATE_CLIENT');?>" class="mo_boot_btn mo_boot_btn-primary" />
									</form>
								</th>
							</tr>
						</table>
					</div>
					<div class="mo_boot_col-sm-12">
						<p class='alert alert-info'>
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ONLY_ONE_CLIENT_NOTE1');?>
							<a href="index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license" rel="noopener noreferrer" style="color:#00008B;"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ONLY_ONE_CLIENT_NOTE2');?> </a>
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ONLY_ONE_CLIENT_NOTE3');?>
						</p>
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
				<form name="oauth_mapping_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=configuration&pa=1');?>">
					<div class="mo_boot_row mo_boot_mt-3">
						<div class="mo_boot_col-sm-6">
							<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_LIST_OF_OAUTH_CLIENTS');?></h3>
						</div>
						<div class="mo_boot_col-sm-6">
							<input id ="add_client" type="submit" name="send_query"  value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ADD');?>" class="mo_boot_btn mo_boot_btn-success mo_boot_float-right" />
							<a href="<?php echo JURI::base().'index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=configuration&endpoints=true';?>"  class="mo_boot_btn mo_boot_btn-primary mo_boot_float-right mo_boot_mr-1" onclick="add_css_tab('#configu_id');"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENDPOINT_URL');?></a>
						</div>
					</div>
					<div class="mo_boot_row mo_boot_mt-3">
						<div class="mo_boot_col-sm-12 mo_boot_table-responsive">
							<table class="mo_boot_table mo_boot_table-bordered">
								<tr>
									<th>
										<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_NAME');?></strong>
									</th>
									<th>
										<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_ID');?></strong>
									</th>
									<th>
										<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_SECRET_KEY');?></strong>
									</th>
									<th>
										<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_OPTIONS');?></strong>
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
					<div class="mo_boot_row mo_boot_my-4">
						<div class="mo_boot_col-sm-12">
							<p class='alert alert-info'>
								<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_NOTE');?>
							</p>
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
					<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CONFIGURE_OAUTH_CLIENT');?></h3>
				</div>
			</div>
			<hr>
			<form  method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.addclient');?>">
				<div class="mo_boot_row mo_boot_mt-3" > 
					<div class="mo_boot_col-sm-3">
						<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_NAME');?>:</strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" type="text" id="mo_oauth_custom_client_name" name="mo_oauth_custom_client_name" value="" placeholder= "<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_NAME_PLACEHOLDER');?>">
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_AUTHORIZED_REDIRECT_URI');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" required="" type="text" name="mo_oauth_client_redirect_url" value="" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_AUTHORIZED_REDIRECT_URI_PLACEHOLDER');?>">
					</div>
				</div>
				<div class="mo_boot_col-sm-11" style="padding-left:0%!important; padding-right:0%!important; padding-top:1rem!important;">
				<details>
					
                 <summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PREMIUM_FEATURES');?></summary>

				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_GRANT_TYPE');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<select name="mo_oauth_grant_type" readonly class="mo_boot_form-control" id="mo_oauth_grant_type">
							<option value="" selected> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_AUTHORIZATION_GRANT_TYPE');?></option>
							<option value="" disabled> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_IMPLICIT_GRANT_TYPE');?></option>
							<option value="" disabled> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PASSWORD_GRANT_TYPE');?></option>
							<option value="" disabled> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REFRESH_TOKEN_GRANT_TYPE');?></option>
							<option value="" disabled> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_CREDENTIALS_GRANT_TYPE');?></option>
						</select>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_PKCE');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input type="radio" name="mo_oauth_enable_pkce" disabled value="1"> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_PKCE_YES');?>
						<input type="radio" name="mo_oauth_enable_pkce" disabled value="0"> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_PKCE_NO');?>
						
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TOKEN_EXPIRY');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" required="" type="text" disabled name="mo_oauth_token_expiry" value="3600">
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3" >
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TOKEN_LENGTH');?></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" required="" type="text" disabled name="mo_oauth_token_length" value="64">
					</div>
				</div>
                </details>
				</div>

				<div class="mo_boot_row mo_boot_mt-3">
                    <div class="mo_boot_col-sm-12">
						<div class="mo_boot_col-sm-11" style="padding-left:0%!important; padding-right:0%!important">
                        	<details>
                            	<summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_JWT_PREMIUM_FEATURE');?></summary>
                            	<div class="mo_boot_row mo_boot_my-3 mo_boot_mx-1">
                                	<div class="mo_boot_col-sm-12 mo_boot_mt-3">
                                    	<input type="checkbox" style="cursor: not-allowed;" disabled id="enablejwt" value="1" name="enablejwt" /> 
                                    	<span style="color: #000000;"><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_JWT');?></strong></span>
                                    	<small><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_JWT_DESCRIPTION');?></small>
                                	</div>
                                	<div class="mo_boot_col-sm-12 mo_boot_mt-3">
                                    	[<a target="_blank" href="https://developers.miniorange.com/docs/oauth/wordpress/server/jwt-support"><b>Click here</b></a><span style="color: #000000;"> to know how this is useful]</span>
                                    	<br><br>
                                   		<p>
										   <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_JWT_NOTE');?>
                                    	</p>
                                    	<hr>
                                	</div>
                                	<div class="mo_boot_col-sm-12">
                                    	<h4 style="color: #000000;"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SIGNING_ALGORITHMS');?></h4> 
                                	</div>
                                	<div class="mo_boot_col-sm-12 mo_boot_my-3">
                                    	<table>
                                        	<tr>
                                            	<td>
                                                	<input type="radio" disabled id="hsa" name="mo_server_jwt_encryption" style="cursor: not-allowed;" value="HSA" />&nbsp;HSA&nbsp;&nbsp;
                                                	<input disabled id="rsa" type="radio" name="mo_server_jwt_encryption" style="cursor: not-allowed;" value="RSA"  /> RSA&nbsp;&nbsp;<br><br>
                                                	<input type="button" disabled class="mo_boot_btn mo_boot_btn-primary" style="cursor: not-allowed;" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_DOWNLOAD_CERTIFICAE');?>"> <br><br>
                                            	</td>
                                        	</tr>
                                    	</table>
                                	</div>
                            	</div> 
                        	</details>
						</div>
                    </div>
                </div> 
				<div class="mo_boot_row mo_boot_text-center mo_boot_my-4">
					<div class="mo_boot_col-sm-12">
						<input type="submit" name="submit" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SAVE_CLIENT');?>" class="mo_boot_btn mo_boot_btn-primary" />
						<a href="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=configuration'); ?>" class="mo_boot_btn mo_boot_btn-danger" ><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_GO_BACK');?></a>
					</div>
				</div> 
			</form>
		</div>
	</div>
	<?php 
}

function mo_oauth_update(){
	$attribute=MoOAuthServerUtility::miniOauthFetchDb('#__miniorange_oauthserver_config',array("id"=>1),'loadAssoc','*');
	?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
		<div class="mo_boot_col-sm-12">
			<form name="f" method="post" action=" <?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.updateclient');?> ">
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-12">
						<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CONFIGURE_OAUTH_CLIENT');?></h3>
						<hr>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_NAME');?><span class="mo_oauth_highlight">*</span> :</strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<?php echo $attribute['client_name'];?>
						<input class="mo_table_textbox" type="hidden" id="mo_oauth_custom_client_name" name="mo_oauth_custom_client_name" value="<?php echo $attribute['client_name'];?>">
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-3">
						<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_AUTHORIZED_REDIRECT_URI');?><span class="mo_oauth_highlight">*</span></strong>
					</div>
					<div class="mo_boot_col-sm-8">
						<input class="mo_boot_form-control" required type="text" name="mo_oauth_client_redirect_url" value="<?php echo $attribute['authorized_uri'];?>" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_AUTHORIZED_REDIRECT_URI_PLACEHOLDER');?>">
					</div>
				</div>
				<div class="mo_boot_col-sm-11" style="padding-left:0%!important; padding-right:0%!important; padding-top:1rem!important">
                    <details>
                        <summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PREMIUM_FEATURES');?></summary>
						<div class="mo_boot_row mo_boot_mt-3" >
							<div class="mo_boot_col-sm-3">
								<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_GRANT_TYPE');?></strong>
							</div>
							<div class="mo_boot_col-sm-8">
								<select name="mo_oauth_grant_type" readonly class="mo_boot_form-control" id="mo_oauth_grant_type">
									<option value="" selected> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_AUTHORIZATION_GRANT_TYPE');?></option>
									<option value="" disabled> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_IMPLICIT_GRANT_TYPE');?></option>
									<option value="" disabled> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PASSWORD_GRANT_TYPE');?></option>
									<option value="" disabled> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REFRESH_TOKEN_GRANT_TYPE');?><</option>
									<option value="" disabled> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CLIENT_CREDENTIALS_GRANT_TYPE');?></option>
								</select>
							</div>
						</div>
						<div class="mo_boot_row mo_boot_mt-3" >
							<div class="mo_boot_col-sm-3">
								<strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_PKCE');?></strong>
							</div>
							<div class="mo_boot_col-sm-8">
								<input type="radio" name="mo_oauth_enable_pkce" disabled value="1"> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_PKCE_YES');?>
								<input type="radio" name="mo_oauth_enable_pkce" disabled value="0"> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_PKCE_NO');?>
							</div>
						</div>
						<div class="mo_boot_row mo_boot_mt-3" >
							<div class="mo_boot_col-sm-3">
								<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TOKEN_EXPIRY');?></strong>
							</div>
							<div class="mo_boot_col-sm-8">
								<input class="mo_boot_form-control" required="" type="text" disabled name="mo_oauth_token_expiry" value="3600">
							</div>
						</div>
						<div class="mo_boot_row mo_boot_mt-3" >
							<div class="mo_boot_col-sm-3">
								<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_TOKEN_LENGTH');?></strong>
							</div>
							<div class="mo_boot_col-sm-8">
								<input class="mo_boot_form-control" required="" type="text" disabled name="mo_oauth_token_length" value="64">
							</div>
						</div>
					</details>
				</div>
				
				<div class="mo_boot_row mo_boot_mt-3">
                    <div class="mo_boot_col-sm-12">
					<div class="mo_boot_col-sm-11" style="padding-left:0%!important; padding-right:0%!important">
                        <details>
						<summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_JWT_PREMIUM_FEATURE');?></summary>
                            <div class="mo_boot_row mo_boot_my-3 mo_boot_mx-1" style="background:white;border:1px solid #226a8b;border-radius:5px;">
                                <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                                    <input type="checkbox" style="cursor: not-allowed;" disabled id="enablejwt" value="1" name="enablejwt" /> 
                                    <span style="color: #000000;"><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_JWT');?></strong></span>
                                    <small><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_JWT_DESCRIPTION');?></small>
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                                    [<a target="_blank" href="https://developers.miniorange.com/docs/oauth/wordpress/server/jwt-support"><b>Click here</b></a><span style="color: #000000;"> to know how this is useful]</span>
                                    <br><br>
                                    <p>
										<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_JWT_NOTE');?>
                                    </p>
                                    <hr>
                                </div>
                                <div class="mo_boot_col-sm-12">
                                    <h4 style="color: #000000;"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SIGNING_ALGORITHMS');?></h4>
                                </div>
                                <div class="mo_boot_col-sm-12 mo_boot_my-3">
                                    <table>
                                        <tr>
                                            <td>
                                                <input type="radio" disabled id="hsa" name="mo_server_jwt_encryption" style="cursor: not-allowed;" value="HSA" />&nbsp;HSA&nbsp;&nbsp;
                                                <input disabled id="rsa" type="radio" name="mo_server_jwt_encryption" style="cursor: not-allowed;" value="RSA"  /> RSA&nbsp;&nbsp;<br><br>
                                                <input type="button" disabled class="mo_boot_btn mo_boot_btn-primary" style="cursor: not-allowed;" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_DOWNLOAD_CERTIFICAE');?>"> <br><br>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div> 
                        </details>
					</div>
                    </div>
                </div> 
				<div class="mo_boot_row mo_boot_my-4 mo_boot_text-center">
					<div class="mo_boot_col-sm-12">
						<button name="upd" type="submit" class="mo_boot_btn mo_boot_btn-primary"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_UPDATE_CLIENT');?></button>
						<button class="mo_boot_btn mo_boot_btn-danger" onclick="cancel_update()" ><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_GO_BACK');?></button>
					</div>
				</div>
			</form>
			<form name="f" id="cancelUpdate" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=configuration'); ?>">
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
					<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ADVANCE_SETTINGS');?></h3>
					<hr>
					<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ADVANCE_SETTINGS_NOTE');?><a href="sendto:joomlasupport@xecurify.com">joomlasupport@xecurify.com</a> or <a href="sendto:info@xecurify.com">info@xecurify.com</a></p>
					<br>
				</div>
			</div>
			<div class="mo_boot_row">
				<div class="mo_boot_col-sm-12">
					<details open>
						<summary class="mo_oauth_server_main_summary mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_STATE_PARAMETER');?><sup><small class="mo_oauth_highlight"> <a href="index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license" rel="noopener noreferrer"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PREMIUM');?></a></small></sup></summary>
						<hr>
						<p>[<a target="_blank" href="https://developers.miniorange.com/docs/oauth/wordpress/server/enforce-state-parameters">Click here</a> <span >to know how this is useful]</span></p>
						<div class="mo_boot_row mo_boot_my-4">
							<div class="mo_boot_col-sm-12">
								<input type="checkbox" name="mo_oauth_auto_redirect"  id="mo_oauth_auto_redirect" value="1" style="float:left; margin-top:5px" disabled /><label style="float:left; margin-left:5px" for="mo_oauth_auto_redirect">&nbsp;<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_AUTHORIZE');?></label>
								<p style="padding-top:2rem ;"> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_STATE_PARAMTER_DESCRIPTION');?></p>
							</div>
						</div>
					</details>

					<details open>
						<summary class="mo_boot_text-dark" style="font-weight:bold"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PROTECT_ADMIN_LOGIN_PAGE_URL');?><sup><small class="mo_oauth_highlight"> <a href="index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=license" rel="noopener noreferrer"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PREMIUM');?></a></small></sup></summary><hr>
						<div class="mo_boot_col-sm-12 mo_boot_mt-4">
							<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PROTECT_ADMIN_LOGIN_PAGE_URL_DETAILS');?></p>
						</div>
						<div class="mo_boot_col-sm-12">
							<div class="mo_boot_row  mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENABLE_CUSTOM_LOGIN_PAGE_URL');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<input type="checkbox" disabled/>
								</div>
							</div>
							<div class="mo_boot_row  mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ACCESS_KEY_FOR_YOUR_ADMIN_LOGIN_URL');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<input class="mo_boot_form-control" type="text" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ACCESS_KEY_FOR_YOUR_ADMIN_LOGIN_URL_PLACEHOLDER');?>" disabled="disable"/>
								</div>
							</div>
							<div class="mo_boot_row  mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CURRENT_ADMIN_LOGIN_URL');?></p>
								</div>
								<div class="mo_boot_col-sm-8 mo_boot_text-wrap">
									<input type="text" class="mo_boot_form-control" name="" disabled  placeholder="<?php echo JURI::base();?>">
								</div>
							</div>
							<div class="mo_boot_row  mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ADMIN_LOGIN_URL');?></p>
								</div>
								<div class="mo_boot_col-sm-8 mo_boot_text-wrap">
									<input type="text" class="mo_boot_form-control" name="" disabled  placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ADMIN_LOGIN_URL_PLACEHOLDER');?>">
								</div>
							</div>
							<div class="mo_boot_row mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REDIRECT_AFTER_FAILED_RESPONSE');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<select class="mo_boot_form-control" id="failure_response" disabled >
										<option><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REDIRECT_TO_HOMEPAGE');?></option>
										<option><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REDIRECT_TO_CUSTOM_404_MESSAGE');?></option>
										<option><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REDIRECT_TO_CUSTOM_REDIRECT_URL');?></option>
									</select>
								</div>
							</div>
							<div class="mo_boot_row mo_boot_mt-3">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_REDIRECT_URL_AFTER_FAILURE');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<input class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_REDIRECT_URL_AFTER_FAILURE_PLACEHOLDER');?>" disabled type="text"/>
								</div>
							</div>
							<div class="mo_boot_row  mo_boot_mt-3" id="custom_message">
								<div class="mo_boot_col-sm-4">
									<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ERROR_MESSAGE_AFTER_FAILURE');?></p>
								</div>
								<div class="mo_boot_col-sm-8">
									<textarea style="height:100% !important" class="mo_boot_form-control" disabled placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ERROR_MESSAGE_AFTER_FAILURE_PLACEHOLDER');?>"></textarea>
								</div>
							</div>
						</div>
						<div class="mo_boot_col-sm-12  mo_boot_mt-4  mo_boot_text-center">
							<input type="submit" class="mo_boot_btn mo_boot_btn-primary" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SAVE_ADMIN_LOGIN_PAGE_URL_SETTINGS');?>" disabled/>
						</div>
					</details>
				</div>
			</div>
		</div>
	</div>

 <?php
}

function mo_oauth_server_request_demo(){
	?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box">
		<div class="mo_boot_col-sm-12 mo_boot_mt-3">
			<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REQUEST_DEMO');?></h3>
			<hr>
		</div>
		<div class="mo_boot_col-sm-12">
			<div style="background-color: #e2e6ea; padding: 10px; color: #000000;"><p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REQUEST_DEMO_DETAILS1');?></p>
			<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REQUEST_DEMO_DETAILS2');?>
			</div>
		</div>
		<div class="mo_boot_col-sm-12">
			<form id="demo_request" name="demo_request" method="post" action='<?php echo JRoute::_("index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.moOAuthRequestForDemoPlan");?>' >
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-4 mo_boot_mx-2">
						<strong><span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_EMAIL');?>:</span><span class="mo_oauth_highlight">*</span></strong>
					</div>
					<div class="mo_boot_col-sm-7">
						<input type="email" class="mo_boot_form-control mo_OAuth_textbox_border" onblur="validateEmail(this)" name="email" placeholder="person@example.com" value='<?php echo JFactory::getUser()->email ;?>' />
						<p style="display: none;color:red" id="email_error">Invalid Email</p>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-4 mo_boot_mx-2">
						<strong><span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_REQUEST_DEMO_TRIAL');?></span><span class="mo_oauth_highlight">*</span></strong>
					</div>
					<div class="mo_boot_col-sm-7">
						<select required class="mo_boot_form-control mo_OAuth_textbox_border" name="plan" id="rfd_id">
							<option value="" class="mo_boot_text-center">-------------- Select -----------------</option>
							<option value="Joomla OAuth Server Premium Plugin"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_JOOMLA_OAUTH_SERVER_STANDARD_PLUGIN');?></option>
							<option value="Joomla OAuth Server Premium Plugin"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_JOOMLA_OAUTH_SERVER_PREMIUM_PLUGIN');?></option>
							<option value="Not Sure"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_NOT_SURE');?></option>
						</select>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-4 mo_boot_mx-2">
						<strong><span><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_DESCRIPTION');?>:</span><span class="mo_oauth_highlight">*</span></strong>
					</div>
					<div class="mo_boot_col-sm-7">
						<textarea class="mo_OAuth_textbox_border mo_boot_px-2"
								  required type="text" name="description"
                                  style=" width:100%;" rows="4"
                                  placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_DESCRIPTION_PLACEHOLDER');?>"
                                  value=""></textarea>
					</div>
				</div>
				<div class="mo_boot_row mo_boot_text-center mo_boot_my-4">
					<div class="mo_boot_col-sm-12">
						<input type="submit" name="submit" value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_SUBMIT');?>" class="mo_boot_btn mo_boot_btn-primary"/>
					</div>
				</div>		
			</form>
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
					<h2><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENDPOINT_URI');?></h2>
				</div>
				<div class="mo_boot_col-sm-1">
				<a href="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&tab-panel=configuration'); ?>" class="mo_boot_btn mo_boot_btn-danger" ><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENDPOINT_BACK');?></a>
				</div>
				<div class="mo_boot_col-sm-12">
					<p><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ENDPOINT_DESCRIPTION');?><p>
					<hr>
				</div>
			</div>
			
			<div class="mo_boot_col-sm-12 ">
				<table class="mo_boot_table mo_boot_table-bordered">
					<tr>
						<th>
							<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_AUTHORIZE_ENDPOINT');?> </strong> :
						</th>
						<td>	
							<span id="auth_endpoint" ><?php echo JURI::root()."index.php" ?></span> 
							<em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip"; onclick="copyToClipboard('#auth_endpoint');" style="color:red; cursor: pointer;";  ><span class="copytooltiptext">Copied!</span> </em> 
						</td>
								
					</tr>
					<tr>
						<th>
							<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_ACCESS_TOKEN_ENDPOINT');?> </strong> :
						</th>
						<td>
							<span id="acc_token_enpoint"><?php echo JURI::root()."index.php" ?></span>
							<em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip"; onclick="copyToClipboard('#acc_token_enpoint');" style="color:red; cursor: pointer;";  >
							<span class="copytooltiptext">Copied!</span> </em>
					
						</td>			
					</tr>
					<tr>
						<th >
							<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_GET_USER_INFO_ENDPOINT');?> </strong> :
						</th>
						<td>
							<span id="user_info_endpoint"><?php echo JURI::root()."index.php"; ?></span>
							<em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip"; onclick="copyToClipboard('#user_info_endpoint');" style="color:red; cursor: pointer;";  >
							<span class="copytooltiptext">Copied!</span> </em>
						</td>		
					</tr>
					<tr>
						<th>
							<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SCOPE');?> </strong> : 
						</th>
						<td>
							<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SCOPE_EMAIL');?>
						</td>		
					</tr>
				</table>
			</div>
			
		</div>
	</div>
	<?php
}

function mo_oauth_server_support()
{
	$result = MoOAuthServerUtility::getCustomerDetails();	
	$current_user =  JFactory::getUser();
	$admin_email = $result['email'];
	$admin_phone = $result['admin_phone'];
	if($admin_email == '')
		$admin_email = $current_user->email;
	?>
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_OAuth_box" >
		<div class="mo_boot_col-sm-12">
			<form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauthserver&view=accountsetup&task=accountsetup.moOAuthContactUs');?>">
				<div class="mo_boot_row mo_boot_mt-3">
					<div class="mo_boot_col-sm-12 mo_boot_text-center">
						<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT');?><hr></h3>
					</div>
					<div class="mo_boot_col-sm-12">
						<p class="oauth-table"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_NOTE');?></p>
					</div>
				</div>
				<div class="mo_boot_row ">
					<div class="mo_boot_col-sm-12 mo_boot_mt-2">
						<input type="email" class="mo_boot_form-control mo_OAuth_textbox_border"  name="query_email" value="<?php echo $admin_email; ?>" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_EMAIL_PLACEHOLDER');?>" required />
					</div>
					<div class="mo_boot_col-sm-12 mo_boot_mt-2">
						<input type="number" class="mo_boot_form-control mo_OAuth_textbox_border" name="query_phone" value="<?php echo $admin_phone; ?>" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_PHONE_PLACEHOLDER');?>"/>
					</div>
					<div class="mo_boot_col-sm-12 mo_boot_mt-2">
						<textarea  name="query" class="mo_OAuth_textbox_border mo_boot_px-2" cols="52" rows="6" style="width:100%;" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_QUERY_PLACEHOLDER');?>" required></textarea>
					</div>
					<div class="mo_boot_col-sm-12 mo_boot_my-3 mo_boot_text-center">
						<input type="submit" name="send_query"  value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SUPPORT_SUBMIT_QUERY');?>" class="mo_boot_btn mo_boot_btn-primary" />
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php
}

function mo_oauth_server_licensing_plan()
{
	?>
<div class="mo_boot_row" style="background-color:#e0e0d8 ">
	<div class="mo_boot_row mo_boot_m-1 mo_boot_my-4 mo_OAuth_box">
	<div class="mo_boot_col-sm-12 mo_boot_my-4">
 
 		<div id="mo_oauth_liciensing_table_wrapper" class="mo_boot_my-4" style="background: #132f53;">
			<h2 class="mo_oauth_feature_comparision mo_boot_text-light mo_boot_py-2" id="mo_oauth_target_license"><?php echo JText::_('COM_MINIORANGE_LICENSING_PLAN');?></h2>
		</div>
    	<br>
		<div class="mo_oauth_pricing_wrapper">
           <div class="mo_oauth_pricing_table">
        	    <div class="mo_oauth_pricing_table_head">
                    <h4 class="mo_oauth_pricing_table_title"><?php echo JText::_('COM_MINIORANGE_FEATURE_COMPARISION_FREE_PLAN');?></h4>
                </div>
                <div class="mo_oauth_pricing_table_content">
                    <div class="mo_oauth_pricing_table_price mo_boot_text-light" style="background-color: #132f53">
                        <h1 class="mo_boot_my-3 mo_boot_py-2" ><?php echo JText::_('COM_MINIORANGE_FREE');?></h1>
                    </div>
                    <ul><?php echo JText::_('COM_MINIORANGE_FEATURE_COMPARISION_FREE_PLAN_FEATURES');?></ul>
                    <div class="mo_oauth_sign-up">
						<input style="background-color: #132f53" type="button" onclick= "window.open('https://www.miniorange.com/contact')" target="_blank" value="<?php echo JText::_('COM_MINIORANGE_CONTACT_US');?>"  class="btn bordered radius mo_boot_text-light" />
                    </div>
                </div>
            </div>
 
           <div class="mo_oauth_pricing_table">
               <div class="mo_oauth_pricing_table_head" >
                    <h4 class="mo_oauth_pricing_table_title"><?php echo JText::_('COM_MINIORANGE_FEATURE_COMPARISION_BASIC_PLAN');?></h4>
               </div>
               <div class="mo_oauth_pricing_table_content">
                   <div class="mo_oauth_pricing_table_price" style="background-color: #132f53">
                       <h1 class="mo_boot_my-3 mo_boot_py-2" style="font-size:14px"><input type="button" onclick= "window.open('https://www.miniorange.com/contact')" target="_blank" style="font-weight:bold" value="<?php echo JText::_('COM_MINIORANGE_FEATURE_COMPARISION_BASIC_PLAN_COST');?>"  class="mo_boot_btn mo_boot_px-1 mo_boot_text-light" /></h1>
                   </div>
                   <ul> <?php echo JText::_('COM_MINIORANGE_FEATURE_COMPARISION_BASIC_PLAN_FEATURES');?></ul>
                   <div class="mo_oauth_sign-up">
				   <input style="background-color: #132f53" type="button" onclick="window.open('https://www.miniorange.com/contact')" target="_blank" value="<?php echo JText::_('COM_MINIORANGE_CONTACT_US');?>"  class="btn bordered radius mo_boot_text-light" />
                   </div>
               </div>
           </div>
 
 
            <div class="mo_oauth_pricing_table">            
                <div class="mo_oauth_pricing_table_head">
                    <h4 class="mo_oauth_pricing_table_title"><?php echo JText::_('COM_MINIORANGE_FEATURE_COMPARISION_PREMIUM_PLAN');?></h4>
                </div>
                <div class="mo_oauth_pricing_table_content" >
                    <div class="mo_oauth_pricing_table_price" style="background-color: #132f53">
                        <h1 class="mo_boot_my-3 mo_boot_py-2" style="font-size:14px"><input type="button" onclick= "window.open('https://www.miniorange.com/contact')" target="_blank" value="<?php echo JText::_('COM_MINIORANGE_FEATURE_COMPARISION_BASIC_PLAN_COST');?>" style="font-weight:bold" class="mo_boot_btn mo_boot_px-1 mo_boot_text-light" /></h1>
                    </div>
                    <ul> <?php echo JText::_('COM_MINIORANGE_FEATURE_COMPARISION_PREMIUM_PLAN_FEATURES');?> </ul>
                    <div class="mo_oauth_sign-up">
					<input  style="background-color: #132f53" type="button" onclick= "window.open('https://www.miniorange.com/contact')" target="_blank" value="<?php echo JText::_('COM_MINIORANGE_CONTACT_US');?>"  class="btn bordered radius mo_boot_text-light" />
                    </div>
                </div>
            </div>        
       	</div>
		<br><br>
		<div class="mo_oauth-plan-title" id="mo_oauth_instance">
			<?php echo JText::_('COM_MINIORANGE_INSTANCE');?>
		</div>
		<div id="mo_oauth_payment_methods"></div>
		<div class="mo_boot_my-4" style="background:white;"  >
			<h2 style="text-align:center; font-family: 'Comfortaa', sans-serif;"><?php echo JText::_('COM_MINIORANGE_PAYMENT_METHODS');?></h2><hr>
			<div class="mo_boot_row" style="margin-left:8%">							
				<div class="mo_oauth_payment_options mo_boot_col-sm-5">
            		<div class="mo_oauth_adv_table-header" style="color:black;font-size:40px;" >
                		
                		<em class="mo_boot_mx-2 fa fa-cc-visa" aria-hidden="true"></em>
						<em class="fa fa-cc-mastercard" aria-hidden="true"></em>
						<em class="fa fa-cc-amex" aria-hidden="true"></em>
					</div>
					<p class="mo_boot_pt-4"><?php echo JText::_('COM_MINIORANGE_PAYMENT_METHODS_CARD_DETAILS');?></p>
        		</div>

				<div class="mo_boot_mx-4 mo_oauth_payment_options mo_boot_col-sm-5">
            		<div class="mo_oauth_adv_table-header" >
						<img class="mo_oauth_payment-images card-image" src="" alt=""> 
            			<em style="font-size:30px;color:black;" class="fa fa-university" aria-hidden="true"><span style="font-size: 20px;font-weight:500;">&nbsp;&nbsp;	<?php echo JText::_('COM_MINIORANGE_PAYMENT_METHODS_BANK_TRANSFER');?></span></em>                             
					</div>
					<p class="mo_boot_py-4"><?php echo JText::_('COM_MINIORANGE_PAYMENT_METHODS_BANK_TRANSFER_DETAILS');?></p>
            		<br><br>
        		</div>
			</div>
		</div> 
			
		
		<div class="mo_oauth-plan-title ">	
			<h3><?php echo JText::_('COM_MINIORANGE_END_TO_END_INTEGRATION');?></h3><hr>
            <?php echo JText::_('COM_MINIORANGE_SET_UP_CALL');?>	
		</div>

		<div class="mo_oauth-plan-title ">
			<h3><?php echo JText::_('COM_MINIORANGE_RETURN_POLICY');?></h3>
            <hr>
            <section class="return-policy">
                <p style="font-size:16px;">	<?php echo JText::_('COM_MINIORANGE_RETURN_POLICY_DETAILS');?></p>    
            </section>
		</div>
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
		<h3><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTE_MAPPING');?><sup> <small class="mo_oauth_highlight"><a href="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license" rel="noopener noreferrer"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_PREMIUM');?></a></small></sup></h3><hr>
		</div>
		<div class="mo_boot_col-sm-12 mo_boot_m-3 mo_boot_p-2" style="background:#e0e0e0">
			<p><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_JOOMLA_USER_FIELD_ATTRIBUTE_NAME');?></strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_JOOMLA_USER_FIELD_ATTRIBUTE_NAME_DESCRIPTION');?></p>
			<p><strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_JOOMLA_USER_FIELD_ATTRIBUTE_VALUE');?></strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_JOOMLA_USER_FIELD_ATTRIBUTE_VALUE_DESCRIPTION');?></p>
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
										<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTE');?> <?php echo $icnt ?> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTE_NAME');?></strong>
									</div>
									<div class="mo_boot_col-sm-7">
										<input type="text" class="mo_oauth_server_textfield mo_boot_form-control" disabled="disabled" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTE_PLACEHOLDER');?>"/>
									</div>
								</div>
						</div>
						<div class="mo_boot_col-sm-6">
							<div class="mo_boot_row mo_boot_mx-4">
								<div class="mo_boot_col-sm-4">
									<strong><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTE');?> <?php echo $icnt;?> <?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTE_VALUE');?></strong>
								</div>
								<div class="mo_boot_col-sm-7">
									<select class="mo_oauth_server_textfield mo_boot_form-control" disabled="disabled">
										<option value=""><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SELECT_CUSTOM_ATTRIBUTE');?></option>
										<option value="emailAddress"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTES_EMAIL_ADDRESS');?></option>
										<option value="username"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTES_USERNAME');?></option>
										<option value="name"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTES_NAME');?></option>
										<option value="firstname"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTES_FIRST_NAME');?></option>
										<option value="lastname"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTES_LAST_NAME');?></option>
										<option value="groups"><?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_CUSTOM_ATTRIBUTES_GROUPS');?></option>
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
			<input type="submit" class="mo_boot_btn mo_boot_btn-primary " value="<?php echo JText::_('COM_MINIORANGE_OAUTHSERVER_SAVE_ADDITIONAL_USER_ATTRIBUTE_MAPPING');?>" disabled/>
		</div><br><br>
	</div>
	<?php
}

?>