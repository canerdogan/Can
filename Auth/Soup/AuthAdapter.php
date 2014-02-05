<?php

/**
 * Description of AuthAdapter
 *
 * @author canerdogan
 */
class Can_Auth_Soup_AuthAdapter implements Zend_Auth_Adapter_Interface {

	const NOT_FOUND_MESSAGE = "Bu eposta ile bir üye bulunamadı.";
	const BAD_PW_MESSAGE = "Hatalı şifre. Lütfen tekrar deneyin.";
	const BANNED_MESSAGE = "Üyeliğiniz uygunsuz aktivitelerinizden dolayı durdurulmuştur.";
	const FAILURE_MESSAGE = "Giriş Başarısız";

	const NOT_FOUND = 1;
	const PASSWORD_MISMATCH = 2;

	protected $_user;
	protected $_email;
	protected $_password;
	protected $_passlogin;

	public function  __construct($email, $password, $passlogin = false) {
		$this->_email = $email;
		$this->_password = $password;
		$this->_passlogin = $passlogin;
	}

	/**
	 * Performs an authentication attempt
	 *
	 * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
	 * @return Zend_Auth_Result
	 */
	public function authenticate() {
		try {
			$this->_user = Soup_Query::select()
									 ->from('users')
									 ->where('email = ?', $this->_email)
									 ->execute();

			if (!$this->_user)
				throw new Zend_Exception(self::NOT_FOUND);
			else if(property_exists($this->_user[0], password)) {
				if ($this->_user[0]->password != md5($this->_password) AND !$this->_passlogin)
				throw new Zend_Exception(self::PASSWORD_MISMATCH);
			} else if(property_exists($this->_user[0], is_banned)) {
				if ($this->_user[0]->is_banned == 'Y' )
					throw new Zend_Exception(self::BANNED_MESSAGE);
			}

		}catch (Exception $e) {
			if($e->getMessage() == self::NOT_FOUND)
				return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::NOT_FOUND_MESSAGE);
			elseif($e->getMessage() == self::PASSWORD_MISMATCH)
				return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::BAD_PW_MESSAGE);
			elseif($e->getMessage() == self::BANNED_MESSAGE)
				return $this->result(Zend_Auth_Result::FAILURE, self::BANNED_MESSAGE);
			else
				return $this->result(Zend_Auth_Result::FAILURE, self::FAILURE_MESSAGE);
		}

		if(property_exists($this->_user[0], updated_at)) {
			$date = new Zend_Date();
			$this->_user[0]->updated_at = $date->toString(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY . ' ' . Zend_Date::HOUR . ':' . Zend_Date::MINUTE . ':' . Zend_Date::SECOND);
			$this->_user[0]->save();
		}

		return $this->result(Zend_Auth_Result::SUCCESS);
	}

	/**
	 * Factory for Zend_Auth_Result
	 *
	 *@param integer    The Result code, see Zend_Auth_Result
	 *@param mixed      The Message, can be a string or array
	 *@return Zend_Auth_Result
	 */
	public function result($code, $messages = array()) {
		if (!is_array($messages)) {
			$messages = array($messages);
		}

		return new Zend_Auth_Result(
				$code,
				$this->_user[0],
				$messages
		);
	}
}