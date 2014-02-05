<?php

class Can_Controller_Action extends Zend_Controller_Action
{
	protected $_session;

	protected $_config;

	protected $_log;

	protected $_auth;

	protected $_userDetails;

	public function init()
	{
		$this->_config = Zend_Registry::getInstance()->config;
		$this->view->config = $this->_config;

		$this->_log = Zend_Registry::getInstance()->log;

		$this->_auth = Zend_Auth::getInstance();
		if ($this->_auth->hasIdentity()) {
			$this->view->userDetails = $this->_userDetails = $this->_auth->getStorage()->read();
			Zend_Registry::set('userDetails', $this->_userDetails);
		}

		$this->_session = new Zend_Session_Namespace($this->_config->app->name);
	}

}