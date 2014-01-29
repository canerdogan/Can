<?php

class Can_Controller_Plugin_Module extends Zend_Controller_Plugin_Abstract
{
	public $_call;
	
	public function __construct ($call)
	{
		$this->_call = $call;
	}

	public function routeShutdown (Zend_Controller_Request_Abstract $request)
	{
		if ($request->getModuleName() == strtolower($this->_call->getModuleName()))
		{
			Zend_Layout::getMvcInstance()->setLayout($request->getModuleName());
			
			$this->_call->install();
			
// 			Zend_Debug::dump(get_class_methods($this->_call));
			
// 			Zend_Debug::dump('Bootstrap Plugin', $this->_call->getModuleName());
		}
			
	}
}