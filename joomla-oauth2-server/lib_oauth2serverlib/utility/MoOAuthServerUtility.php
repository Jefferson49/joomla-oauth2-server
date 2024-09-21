<?php
/**
 * Copyright (C) 2015  miniOrange
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 * 
 * @package     miniOrange OAuth
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * 
 * Extended by:
 * @subpackage  joomla-oauth2-server
 * @link        https://github.com/Jefferson49/Joomla-OAuth2-Server
 * @author      Jefferson49
 * @copyright   Copyright (c) 2024 Jefferson49
 * @license     GNU/GPL v3.0
 */

/**
* This class contains all the utility functions
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Factory;

class MoOAuthServerUtility
{
  	
	public static function GetPluginVersion()
	{
		$db = Factory::getDbo();
		$dbQuery = $db->getQuery(true)
		->select('manifest_cache')
		->from($db->quoteName('#__extensions'))
		->where($db->quoteName('element') . " = " . $db->quote('com_oauth2server'));
		$db->setQuery($dbQuery);
		$manifest = json_decode($db->loadResult());
		return($manifest->version);
    }

	public static function generic_update_query($database_name, $updatefieldsarray , $condition = TRUE)
	{
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        foreach ($updatefieldsarray as $key => $value)
          $database_fileds[] = $db->quoteName($key) . ' = ' . $db->quote($value);
        $query->update($db->quoteName($database_name))->set($database_fileds);
		if($condition!==TRUE)
        {
            foreach ($condition as $key=>$value)
                $query->where($db->quoteName($key) . " = " . $db->quote($value));
        }
        $db->setQuery($query);
        $db->execute();
    }

	public static function generic_insert_query(string $table_name, array $insertfieldssarray)
	{
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
		$keys = array_keys($insertfieldssarray);
		foreach ($insertfieldssarray as $key => $value) {
			$database_fileds[] = $db->quoteName($key) . ' = ' . $db->quote($value);
		}
		$query->insert($table_name)->set($database_fileds);
        $db->setQuery($query);
        $db->execute();
    }


	public static function generic_delete_query(string $table_name, array $selection)
	{
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

		foreach ($selection as $key => $value) {
			$where[] = $db->quoteName($key) . ' = ' . $db->quote($value);
		}
		
		$query->delete($table_name)->where($where);
        $db->setQuery($query);
        $db->execute();
    }	

	public static function check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}
	
	public static function is_curl_installed() {
		if  (in_array  ('curl', get_loaded_extensions())) {
			return 1;
		} else 
			return 0;
	}
	
	public static function getHostname(){
		return '';
	}

	static function  miniOauthFetchDb($tableName,$condition=TRUE,$method='loadAssoc',$columns='*'){

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$columns = is_array($columns)?$db->quoteName($columns):$columns;
		$query->select($columns);
		$query->from($db->quoteName($tableName));
        if($condition!==TRUE)
        {
            foreach ($condition as $key=>$value)
                $query->where($db->quoteName($key) . " = " . $db->quote($value));
        }

		$db->setQuery($query);
		if ($method=='loadColumn')
			return $db->loadColumn();
		else if($method == 'loadObjectList')
			return $db->loadObjectList();
        else if($method == 'loadObject')
            return $db->loadObject();
		else if($method== 'loadResult')
			return $db->loadResult();
		else if($method == 'loadRow')
			return $db->loadRow();
        else if($method == 'loadRowList')
            return $db->loadRowList();
        else if($method == 'loadAssocList')
            return $db->loadAssocList();
		else
			return $db->loadAssoc();
	}
	
	public static function plugin_efficiency_check($email,$appname,$base_url, $reason ="null")
	{
        return;
	}
}
?>