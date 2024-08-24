<?php
/**
 * @package     Joomla.System
 * @subpackage  plg_system_mooauthserver
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
//defined( '_JEXEC' ) or die( 'Restricted access' );
define('_JEXEC', 1);
define('JPATH_BASE','../../..' );
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';
jimport( 'joomla.plugin.plugin' );
foreach (getallheaders() as $name => $value) 
{				
	if($name=='Authorization')
	{
		$access_token=$value;
		break;
	}
}
$access_token = explode(" ", $access_token, 2);
$access_token =$access_token[1];
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select($db->quoteName(array('id','time_stamp')));
$query->from($db->quoteName('#__users'));
$query->where($db->quoteName('client_token') . ' =' . $db->quote($access_token));
$db->setQuery($query);
$results = $db->loadAssoc();
if($results['time_stamp']<time())
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

if($results['id']!='' && $flag)
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