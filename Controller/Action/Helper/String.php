<?php
/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 * @category Can
 * @package Can_Controller
 * @subpackage Can_Controller_Action_Helper
 * @copyright Copyright (c) 2012 Can Erdogan <can@canerdogan.net>
 * @version
 *
 *
 */
class Can_Controller_Action_Helper_String extends Zend_Controller_Action_Helper_Abstract
{

	public function slugify ($phrase, $maxLength = 100, $replacement = "-")
	{
		$phrase = strtr($phrase, array(
				"ç" => 'c',
				"ş" => 's',
				"ğ" => 'g',
				"ı" => 'i',
				"ö" => 'o',
				"ü" => 'u',
				"Ç" => 'C',
				"Ş" => 'S',
				"Ğ" => 'G',
				"İ" => 'I',
				"Ö" => 'O',
				"Ü" => 'U'
		));
		$result = mb_strtolower($phrase);
		$result = preg_replace("/[^a-z0-9\s-]/", "", $result);
		$result = trim(preg_replace("/[\s-]+/", " ", $result));
		$result = trim(substr($result, 0, $maxLength));
		$result = preg_replace("/\s/", $replacement, $result);
		
		return $result;
	}
}