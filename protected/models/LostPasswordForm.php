<?php
/**
 * Lost password form model class
 */
class LostPasswordForm extends CFormModel
{	
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
			array('email', 'required'),
			array('email', 'email'),
			array('email', 'exist', 'className' => 'Members', 'message' => Yii::t('lostpassword', 'Sorry, That email does not exists in our records.') ),
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
			'email' => Yii::t('members', 'Email'),
			'verifyCode' => Yii::t('members', 'Security Code'),
		);
	}
	
}