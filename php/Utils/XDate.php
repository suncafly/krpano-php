<?php
date_default_timezone_set('PRC');

class XDate
{
	protected function __construct()
	{
		
	}
	
	protected function __clone()
	{
		
	}
	
	public static function getTime()
	{
		list($usec, $sec) = explode(" ", microtime());
        $msec=round($usec*1000);
		
		return date('Y-m-d H:i:s', $sec). '.' .str_pad($msec, 6, '0', STR_PAD_LEFT);
	}
}