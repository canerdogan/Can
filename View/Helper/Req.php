<?php
/**
 *
 * @author canerdogan
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Request helper
 *
 * @uses viewHelper Can
 */
class Can_View_Helper_Req extends Zend_View_Helper_Abstract
{	
	public function req ()
	{
		return Zend_Controller_Front::getInstance()->getRequest();
	}
}
