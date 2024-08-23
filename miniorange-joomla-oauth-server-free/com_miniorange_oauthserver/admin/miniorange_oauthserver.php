<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT . '/helpers/mo_customer_setup.php';

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_miniorange_oauthserver'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');
 
JLoader::registerPrefix('miniorange_oauthserver', JPATH_COMPONENT_ADMINISTRATOR);
 
// Get an instance of the controller prefixed by JoomlaIdp
$controller = JControllerLegacy::getInstance('MiniorangeOauthserver');
 
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));
 
// Redirect if set by the controller
$controller->redirect();