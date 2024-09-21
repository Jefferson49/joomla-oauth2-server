<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  joomla-oauth2-server
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

require_once JPATH_COMPONENT . '/helpers/mo_customer_setup.php';

// Access check.
if (!Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_oauth2server'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');
 
JLoader::registerPrefix('oauth2server', JPATH_COMPONENT_ADMINISTRATOR);
 
// Get an instance of the controller prefixed by JoomlaIdp
$controller = BaseController::getInstance('MiniorangeOauthserver');
 
// Perform the Request task
$controller->execute(Factory::getApplication()->input->get('task'));
 
// Redirect if set by the controller
$controller->redirect();