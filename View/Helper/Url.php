<?php
/**
 *
 * @author canerdogan
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * URL helper
 *
 * @uses viewHelper Can
 */
class Can_View_Helper_Url extends Zend_View_Helper_ServerUrl
{
	public function url($requestUri = null)
	{
		$_config = Zend_Registry::getInstance()->config;
		return $_config->general->url . $requestUri;
// 		return parent::serverUrl($requestUri);
	}
}
