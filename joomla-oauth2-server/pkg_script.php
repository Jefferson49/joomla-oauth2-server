<?php
/**
 * Script file of miniorange_dirsync_system_plugin.
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * Extended by:
 * @subpackage  joomla-oauth2-server
 * @link        https://github.com/Jefferson49/Joomla-OAuth2-Server
 * @author      Jefferson49
 * @copyright   Copyright (c) 2024 Jefferson49
 * @license     GNU/GPL v3.0  
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class pkg_OAUTHSERVERInstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent) 
    {

            
    }

    /**
     * This method is called after a component is uninstalled.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent) 
    {

    }

    /**
     * This method is called after a component is updated.
     *
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent) 
    {

    }

    /**
     * Runs just before any installation action is performed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param  string    $type   - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($type, $parent) 
    {
  '</p>';
    }

    /**
     * Runs right after any installation action is performed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return bool
     */
    function postflight($type, $parent): bool 
    {
        if ($type == 'uninstall') {
            return true;
        }

        $this->showInstallMessage('');
        return true;
    }

    protected function showInstallMessage($messages=array()) {
        ?>
        <style>
        
	.mo-row {
		width: 100%;
		display: block;
		margin-bottom: 2%;
        padding:20px;
        background : white;
        border : 1px solid #001b4c;
	}

	.mo-row:after {
		clear: both;
		display: block;
		content: "";
	}

	.mo-column-2 {
		width: 19%;
		margin-right: 1%;
		float: left;
	}

	.mo-column-10 {
		width: 80%;
		float: left;
	}
    </style>
    	<div class="mo-row">
            <p>
                <h2>miniOrange Joomla OAuth Server Free Plugin</h2>
                <hr>
                <p>
                    <strong>
                        Joomla OAuth Server plugin allows you to perform Single Sign-On with any OAuth 2.0 compliant client application . 
                        It enables users to authenticate into your client application using their Joomla credentials, allowing Joomla to 
                        act as an OAuth Provider. You can also access all OAuth APIs using the Joomla OAuth Server SSO plugin.
                    </strong>
                    <h4>Steps to use the OAuth Server plugin.</h4>
                    <ul>
                        <li>Click on <b>Components</b></li>
                        <li>Click on <b>Joomla OAuth 2.0 Server</b> and select <b>Configure OAuth</b> tab</li>
                        <li>You can start configuring.</li>
                    </ul>
                </p>
            </p>
            <a class="btn btn-secondary" style="background-color: #001b4c; color : white"  href="index.php?option=com_oauth2server&view=accountsetup&tab-panel=configuration">Start Using Joomla OAuth 2.0 Server plugin</a>
        </div>
        <?php
    }  
}