<?php
require_once("Config/Lite.php");
$config = new Config_Lite("../config.ini", LOCK_EX);

// Usage: 	mixed getConfigValue("section", "key");
//			mixed getConfigValue(null, "key"); <- Takes from global scope
//		
//			void setConfigValue("section","key","value");
//			void setConfigValue(null, "key","value"); <- puts in global scope

function getConfigValue($section, $key)
{
	if(is_null($section)
	{
		return $config[$key];
	}
	return $config[$section][$key];
}
function setConfigValue($section, $key, $value)
{
	if(is_null($section))
	{
		$config[$key] = $value;
	}
	else
	{
		$config[$section] = array($key => $value;
	}
	$config->save();
}
?>