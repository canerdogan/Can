<?php
/**
 *
 * @author canerdogan
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Date helper
 *
 * @uses viewHelper Can
 */
class Can_View_Helper_Date extends Zend_View_Helper_Abstract
{

	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;

	private $_date;

	/**
	 *
	 * @param Zend_Date|NULL $date        	
	 * @return Can_View_Helper_Date
	 */
	public function date ($date = NULL)
	{
		if ($date instanceof Zend_Date)
			$this->_date = $date;
		
		return $this;
	}

	/**
	 *
	 * @param string $date        	
	 * @return Can_View_Helper_Date
	 */
	public function mysql ($date)
	{
		$this->_date = new Zend_Date($date, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY . ' ' . Zend_Date::HOUR . ':' . Zend_Date::MINUTE . ':' . Zend_Date::SECOND);
		return $this;
	}

	/**
	 *
	 * @return Zend_Date
	 */
	public function toMysql ()
	{
		if (!$this->_date)
			$this->_date = new Zend_Date();

		return $this->_date->toString(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY . ' ' . Zend_Date::HOUR . ':' . Zend_Date::MINUTE . ':' . Zend_Date::SECOND);
	}

	/**
	 *
	 * @return Zend_Date
	 */
	public function toView ($hours = FALSE, $second = FALSE)
	{
		if (!$this->_date)
			$this->_date = new Zend_Date();

		$dateFormat = Zend_Date::DAY . ' ' . Zend_Date::MONTH_NAME . ' ' . Zend_Date::YEAR;
		
		if ($hours) {
			$dateFormat .= ' ' . Zend_Date::HOUR . ':' . Zend_Date::MINUTE;
			if ($second)
				$dateFormat .= ':' . Zend_Date::SECOND;
		}
		return $this->_date->toString($dateFormat, 'tr_TR');
	}

	public function toCustom ($dateFormat)
	{
		if (!$this->_date)
			$this->_date = new Zend_Date();

		return $this->_date->toString($dateFormat, 'tr_TR');
	}

	public function toArray ()
	{
		if (!$this->_date)
			$this->_date = new Zend_Date();

		return $this->_date->toArray();
	}
}
