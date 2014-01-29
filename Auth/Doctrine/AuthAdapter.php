<?php

/**
 * Description of AuthAdapter
 *
 * @author canerdogan
 */
class Can_Auth_Doctrine_AuthAdapter implements Zend_Auth_Adapter_Interface
{

	const NOT_FOUND_MESSAGE = "Bu eposta ile bir üye bulunamadı.";

	const BAD_PW_MESSAGE = "Hatalı şifre. Lütfen tekrar deneyin.";

	const FAILURE_MESSAGE = "Giriş Başarısız";

	protected $_user;

	protected $_email;

	protected $_password;

	public function __construct ($email, $password)
	{
		$this->_email = $email;
		$this->_password = $password;
	}

	/**
	 * Performs an authentication attempt
	 *
	 * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
	 * @return Zend_Auth_Result
	 */
	public function authenticate ()
	{
		try {
			$this->_user = Users::authenticate($this->_email, $this->_password);
		} catch (Exception $e) {
			if ($e->getMessage() == Users::NOT_FOUND)
				return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::NOT_FOUND_MESSAGE);
			elseif ($e->getMessage() == Users::PASSWORD_MISMATCH)
				return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::BAD_PW_MESSAGE);
			else
				return $this->result(Zend_Auth_Result::FAILURE, self::FAILURE_MESSAGE);
		}
		return $this->result(Zend_Auth_Result::SUCCESS);
	}

	/**
	 * Factory for Zend_Auth_Result
	 *
	 * @param
	 *        	integer The Result code, see Zend_Auth_Result
	 * @param
	 *        	mixed The Message, can be a string or array
	 * @return Zend_Auth_Result
	 */
	public function result ($code, $messages = array())
	{
		if (! is_array($messages)) {
			$messages = array(
					$messages
			);
		}
		
		return new Zend_Auth_Result($code, $this->_user, $messages);
	}

	public function getResultRowObject ($returnColumns = null, $omitColumns = null)
	{
		if (! $this->_user) {
			return false;
		}
		
		$returnObject = new stdClass();
		
		if (null !== $returnColumns) {
			
			$availableColumns = array_keys($this->_user);
			foreach ((array) $returnColumns as $returnColumn) {
				if (in_array($returnColumn, $availableColumns)) {
					$returnObject->{$returnColumn} = $this->_user[$returnColumn];
				}
			}
			return $returnObject;
		} elseif (null !== $omitColumns) {
			
			$omitColumns = (array) $omitColumns;
			foreach ($this->_user as $resultColumn => $resultValue) {
				if (! in_array($resultColumn, $omitColumns)) {
					$returnObject->{$resultColumn} = $resultValue;
				}
			}
			return $returnObject;
		} else {
			
			foreach ($this->_user as $resultColumn => $resultValue) {
				$returnObject->{$resultColumn} = $resultValue;
			}
			return $returnObject;
		}
	}
}
