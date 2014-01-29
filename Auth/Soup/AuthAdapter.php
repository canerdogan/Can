<?php

/**
 * Description of AuthAdapter
 *
 * @author canerdogan
 */
class Can_Auth_Soup_AuthAdapter implements Zend_Auth_Adapter_Interface {

    const NOT_FOUND_MESSAGE = "Bu eposta ile bir üye bulunamadı.";
    const BAD_PW_MESSAGE = "Hatalı şifre. Lütfen tekrar deneyin.";
    const FAILURE_MESSAGE = "Giriş Başarısız";
    
    const NOT_FOUND = 1;
    const PASSWORD_MISMATCH = 2;

    protected $_user;
    protected $_email;
    protected $_password;

    public function  __construct($email, $password) {
        $this->_email = $email;
        $this->_password = $password;
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
        	else if ($this->_user[0]->password != md5($this->_password) )
        		throw new Zend_Exception(self::PASSWORD_MISMATCH);
            
        }catch (Exception $e) {
            if($e->getMessage() == self::NOT_FOUND)
                return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::NOT_FOUND_MESSAGE);
            elseif($e->getMessage() == self::PASSWORD_MISMATCH)
                return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::BAD_PW_MESSAGE);
            else
                return $this->result(Zend_Auth_Result::FAILURE, self::FAILURE_MESSAGE);
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
