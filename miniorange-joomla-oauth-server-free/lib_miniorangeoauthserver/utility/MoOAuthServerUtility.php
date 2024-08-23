<?php
/** Copyright (C) 2015  miniOrange

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
* @package 		miniOrange OAuth
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
/**
This class contains all the utility functions
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

class MoOAuthServerUtility
{
  
	public static function is_customer_registered() 
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__miniorange_oauthserver_customer'));
		$query->where($db->quoteName('id')." = 1");
 
		$db->setQuery($query);
		$result = $db->loadAssoc();
		
		$email 			= $result['email'];
		$customerKey 	= $result['customer_key'];
		$status = $result['registration_status'];
		if($email && $customerKey && is_numeric( trim($customerKey)) && $status == 'SUCCESS'){
			return 1;
		} else{
			return 0;
		}
	}
	
	
	public static function GetPluginVersion()
	{
		$db = JFactory::getDbo();
		$dbQuery = $db->getQuery(true)
		->select('manifest_cache')
		->from($db->quoteName('#__extensions'))
		->where($db->quoteName('element') . " = " . $db->quote('com_miniorange_oauthserver'));
		$db->setQuery($dbQuery);
		$manifest = json_decode($db->loadResult());
		return($manifest->version);
    }

	public static function generic_update_query($database_name, $updatefieldsarray , $condition = TRUE)
	{
        $db = JFactory::getDbo();
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
		return 'https://login.xecurify.com';
	}
	
	public static function getCustomerDetails(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__miniorange_oauthserver_customer'));
		$query->where($db->quoteName('id')." = 1");
 
		$db->setQuery($query);
		$customer_details = $db->loadAssoc();
		return $customer_details;
	}

	static function  miniOauthFetchDb($tableName,$condition=TRUE,$method='loadAssoc',$columns='*'){

		$db = JFactory::getDbo();
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
        $url =  'https://login.xecurify.com/moas/api/notify/send';
        $ch = curl_init($url);

        
        $customerKey = "16555";
		$apiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
	

        $currentTimeInMillis= round(microtime(true) * 1000);
        $stringToHash 		= $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
        $hashValue 			= hash("sha512", $stringToHash);
        $customerKeyHeader 	= "Customer-Key: " . $customerKey;
        $timestampHeader 	= "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
        $authorizationHeader= "Authorization: " . $hashValue;
        $fromEmail 			= $email;
        $subject            = "Oauth Server[Free] for efficiency ";
        


        $query1 =" miniOrange Joomla [Free] Oauth Server to improve efficiency ";
        $content='<div >Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br>OAuth Client Name :'.$appname.'<br><br><b>Email :<a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a></b><br><br><b>Plugin Efficency Check: '.$query1. '</b><br><br><b>Redirect URI: ' .$base_url. '</b><br> Error Message:'.$reason.'</div>';

        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
                'customerKey' 	=> $customerKey,
                'fromEmail' 	=> $fromEmail,                
                'fromName' 		=> 'miniOrange',
				'bccEmail'      => 'jeswanth@xecurify.com',
                'toEmail' 		=> 'shubham.pokharna@xecurify.com',
                'toName' 		=> 'shubham.pokharna@xecurify.com',
                'subject' 		=> $subject,
                'content' 		=> $content
            ),
        );
        $field_string = json_encode($fields);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
            $timestampHeader, $authorizationHeader));
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
        $content = curl_exec($ch);
        	
        if(curl_errno($ch)){
			
            return;
        }
        curl_close($ch);
        return;
	}
}
?>