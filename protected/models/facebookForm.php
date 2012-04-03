<?php
/**
 * facebook form model class
 */
class facebookForm extends CFormModel
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
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('email, password', 'required'),
			array('email', 'email'),
			array('password', 'length', 'min' => 3, 'max' => 32),
			array('email', 'length', 'min' => 3, 'max' => 55),
			array('password', 'authenticate'),
		);
	}
	
	/**
	 * @return null on success error on failure
	 */
	public function authenticate()
	{
		$identity = new InternalIdentity($this->email, $this->password);
		if($identity->authenticate())
		{
			// Member authenticated
			return true;
		}
		else
		{
			$this->addError('password', $identity->errorMessage);
		}
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
		);
	}
	
}