<?php

/**
 * 
 * @author canerdogan
 *
 */
class Can_Controller_Plugin_Module extends Zend_Controller_Plugin_Abstract
{

	public function routeShutdown (Zend_Controller_Request_Abstract $request)
	{
		$activeModuleName = $request->getModuleName();
		$activeBootstrap = $this->_getActiveBootstrap($activeModuleName);
		$activeBootstrap->runInstall();
	}

	/**
	 * return the default bootstrap of the app
	 *
	 * @return Zend_Application_Bootstrap_Bootstrap
	 */
	protected function _getBootstrap ()
	{
		$frontController = Zend_Controller_Front::getInstance();
		$bootstrap = $frontController->getParam('bootstrap');
		return $bootstrap;
	}

	/**
	 * return the bootstrap object for the active module
	 *
	 * @return Offshoot_Application_Module_Bootstrap
	 */
	public function _getActiveBootstrap ($activeModuleName)
	{
		$moduleList = $this->_getBootstrap()->getResource('modules');

		if (isset($moduleList[$activeModuleName])) {
			Zend_Layout::getMvcInstance()->setLayout($activeModuleName);
			return $moduleList[$activeModuleName];
		}

		return null;
	}
}