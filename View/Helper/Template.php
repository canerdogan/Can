<?php
/**
 *
 * @author canerdogan
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Template helper
 *
 * @uses viewHelper Can
 */
class Can_View_Helper_Template extends Zend_View_Helper_Abstract
{

	public static function template ($n, $string)
	{
		foreach ($n as $key => $val) {
			$string = preg_replace('/{{' . $key . '}}/i', $val, $string);
		}
		return $string;
	}
}
