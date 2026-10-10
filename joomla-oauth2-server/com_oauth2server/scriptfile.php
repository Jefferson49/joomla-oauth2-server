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
 * @copyright   Copyright (c) 2026 Jefferson49
 * @license     GNU/GPL v3.0
 */

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;


// no direct access
defined('_JEXEC') or die('Restircted access');

class com_oauth2serverInstallerScript {

    /**
     * method to install the extension
     *
     * @return void
     */
    function install($parent) {
        return;
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {

        //we do not need to uninstall the component, because it is handled by Joomla already
        return;
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        return;
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @param $parent   is the class calling this method
     * @param $type     is the type of change (install, update or discover_install)
     *
     * @return void
     */
    function preflight($type, $parent) {
        return;
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {

        if ($type === 'uninstall') {
            return;
        }

        $db = Factory::getContainer()->get(DatabaseInterface::class);

        //Update database table "users" if required
        $query = "SELECT * FROM `#__users` LIMIT 1";
        $db->setQuery($query);
        $columns_data = $db->LoadAssoc();

        if ($columns_data !== null) {
            $columns_titles = array_keys($columns_data);

            //Add additional columns
            if (is_array($columns_titles)) {
                if (!in_array('randcode_client_id', $columns_titles)) {
                    $query_update = "ALTER TABLE  `#__users`
                            ADD `randcode_client_id` varchar(255) DEFAULT '0',
                            ADD `randcode_time_stamp` int(11) unsigned DEFAULT '0'";

                    $db->setQuery($query_update);
                    $db->execute();
                }
            }
        }
    }
}
