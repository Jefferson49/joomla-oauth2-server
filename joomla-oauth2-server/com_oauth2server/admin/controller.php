<?php
/**
 * @package     Joomla.Administrator
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
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\BaseController;
 
/**
 * General Controller of miniorange_oauth component
 *
 * @package     Joomla.Administrator
 * @subpackage  joomla-oauth2-server
 * @since       0.0.7
 */
class MiniorangeOAuthserverController extends BaseController
{
	/**
	 * The default view for the display method.
	 *
	 * @var string
	 * @since 12.2
	 */
	protected $default_view = 'accountsetup';
}