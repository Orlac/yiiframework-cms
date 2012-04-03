<?php
/**
 * Register form model
 */
class RegisterForm extends CFormModel
{
	/**
	 * @var string - username
	 */
    public $username;
    
	/**
	 * @var string - password
	 */
	public $password;
	
	/**
	 * @var string - password2
	 */
	public $password2;
    
	/**
	 * @var string - email
	 */
	public $email;

	/**
	 * @var string - captcha
	 */
	public $verifyCode;
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('username', 'match', 'allowEmpty' => false, 'pattern' => '/[A-Za-z0-9\x80-\xFF]+$/' ),
			array('email', 'email'),
			array('email, username', 'unique', 'className' => 'Members' ),
			array('email, password, password2', 'required'),
			array('username, password, password2', 'length', 'min' => 3, 'max' => 32),
			array('password2', 'compare', 'compareAttribute'=>'password'),
			array('email', 'length', 'min' => 3, 'max' => 55),
			array('verifyCode', 'captcha'),
		);
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'username' => Yii::t('members', 'Username'),
			'email' => Yii::t('members', 'Email'),
			'password' => Yii::t('members', 'Password'),
			'password2' => Yii::t('members', 'Password Confirmation'),
			'verifyCode' => Yii::t('members', 'Security Code'),
		);
	}
	
}