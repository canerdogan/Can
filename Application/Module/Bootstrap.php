<?php

/**
 *
 * @author canerdogan
 * @version 
 */
class Can_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{

	protected $_resources;

	public function runInstall ()
	{
		$methodNames = get_class_methods($this);
		
		$this->_resources = array();
		foreach ($methodNames as $method) {
			if (4 < strlen($method) && 'init' === substr($method, 0, 4)) {
				$this->_resources[strtolower(substr($method, 4))] = $method;
			}
		}
		
		if ($this->_resources) {
			foreach ($this->_resources as $method) {
				$this->$method();
			}
		}
	}
}
