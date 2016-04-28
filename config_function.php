<?php

function config($name = '')
{
	$config = include(dirname(__FILE__) . '/database_config.php');

	if(isset($config[$name]))
		return $config[$name];

	$configValue = isset($config['connections'][$config['default']][$name]) ? 
			$config['connections'][$config['default']][$name] : null;
	if($name)
	{
		if(!is_null($configValue))
		{
			return $configValue;
		}
	}

	return $config['default'];
}