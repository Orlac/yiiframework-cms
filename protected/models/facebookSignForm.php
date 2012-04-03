<?php
/**
 * facebook form model class
 */
class facebookSignForm extends CFormModel
{
	/**
	 * @var string - password
	 */
	public $password;
	
	/**
	 * @var string - email
	 */
	public $email;
	
	/**
	 * @var string - username
	 */
	public $username;
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('email, password, username', 'required'),
			array('email', 'email'),
			array('password, username', 'length', 'min' => 3, 'max' => 32),
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
			'email' => Yii::t('members', 'Email'),
			'password' => Yii::t('members', 'Password'),
			'username' => Yii::t('members', 'Username'),
		);
	}
	
}