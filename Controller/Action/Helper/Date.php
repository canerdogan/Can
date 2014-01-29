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
class Can_Controller_Action_Helper_Date extends Zend_Controller_Action_Helper_Abstract
{

	public function find($date)
	{
		$change = Array(
				'ocak'		=> 'Ocak',
				'subat'		=> 'Şubat',
				'mart'		=> 'Mart',
				'nisan'		=> 'Nisan',
				'mayis'		=> 'Mayıs',
				'haziran'	=> 'Haziran',
				'temmuz'	=> 'Temmuz',
				'agustos'	=> 'Ağustos',
				'eylul'		=> 'Eylül',
				'ekim'		=> 'Ekim',
				'kasim'		=> 'Kasım',
				'aralik'	=> 'Aralık'
				);
		
		return strtr($date, $change);
	}
	
	public function zendToMysql(Zend_Date $date)
	{
		return $date->toString(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY . ' ' . Zend_Date::HOUR . ':' . Zend_Date::MINUTE . ':' . Zend_Date::SECOND);
	}
	
	public function mysqlToZend($date)
	{
		$zendDate = new Zend_Date($date, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY . ' ' . Zend_Date::HOUR . ':' . Zend_Date::MINUTE . ':' . Zend_Date::SECOND);
		return $zendDate;
	}
}