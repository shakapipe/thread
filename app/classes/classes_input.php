<?php

class Classes_Input {

	public static function isPost()
	{
		if(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')
		{
			return true;
		}
		return false;
	}

	public static function postAll()
	{
		return $_POST;
	}

	public static function post($name, $default = null)
	{
		if(isset($_POST[$name])){
			return $_POST[$name];
		}
		return $default;
	}

	public static function requestUri()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public static function host()
	{
		if(!empty($_SERVER['HTTP_HOST'])){
			return $_SERVER['HTTP_HOST'];
		}
		return $_SERVER['SERVER_NAME'];
	}


	public static function escape(array $array = array())
	{
		$escaped = null;

		foreach($array as $key => $escape)
		{
			if(is_array($escape)){
				$escaped[$key] = self::escape($escape);
			}else{

				$escaped[$key] = htmlspecialchars($escape, ENT_QUOTES);
			}
		}

		return $escaped;
	}

/**
 * Ajaxによるリクエストかどうか 
 *
 * @return boolean True or False 
 */
	public static function isAjax()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;  
		}
		return false;  
	}
}