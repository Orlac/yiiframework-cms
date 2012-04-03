<?php
/**
 * facebook Identity Class
 * Basically verifies a member by his facebook user id
 * 
 * 
 */
class facebookIdentity extends CBaseUserIdentity
{
	/**
	 * @var int unique member id
	 */
    private $_id;

	protected $fbuid;
	protected $fbemail;

	/**
	 * Initiate the class
	 */
	public function __construct( $fbuid, $fbemail )
	{
		$this->fbuid = $fbuid;
		$this->fbemail = $fbemail;
	}

	/**
	 * Authenticate a member
	 *
	 * @return int value greater then 0 means an error occurred 
	 */
    public function authenticate( )
    {
        $record=Members::model()->find('fbuid=:fbuid', array(':fbuid'=>$this->fbuid));
        if($record===null)
		{
            $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
			$this->errorMessage = Yii::t('members', 'Sorry, We could not find a member with that facebook account.');
		}
        else if( $record->email != $this->fbemail )
		{
            $this->errorCode=self::ERROR_USERNAME_INVALID;
			$this->errorMessage = Yii::t('members', 'Sorry, But the emails of the accounts did no match.');
		}
        else
        {
            $this->_id = $record->id;

			$auth=Yii::app()->authManager;
            if(!$auth->isAssigned($record->role,$this->_id))
            {
                if($auth->assign($record->role,$this->_id))
                {
                    Yii::app()->authManager->save();
                }
            }
			
			// We add username to the state 
            $this->setState('name', $record->username);
			$this->setState('username', $record->username);
			$this->setState('seoname', $record->seoname);
			$this->setState('email', $record->email);
			$this->setState('role', $record->role);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
	/**
	 * @return int unique member id
	 */
    public function getId()
    {
        return $this->_id;
    }
}