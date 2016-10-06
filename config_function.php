<?php

/*function config($name = '')
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
}*/

function config($path = "")
{
	$config = include(dirname(__FILE__) . '/database_config.php');
	if(strpos($path, ".") !== false)
		$path = explode(".", $path);

	if(is_array($path) && count($path))
	{
		foreach ($path as $setting)
		{
			if (isset($config[$setting]))
			{
				$config = $config[$setting];
			}
		}

		return $config;
	}
	else
	{
		if(isset($config[$path]))
			return $config[$path];

		$configValue = isset($config['connections'][$config['default']][$path]) ?
			$config['connections'][$config['default']][$path] : null;
		if($path)
		{
			if(!is_null($configValue))
			{
				return $configValue;
			}
		}
	}

	return $config['default'];
}
