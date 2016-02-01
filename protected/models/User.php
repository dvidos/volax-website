<?php

class User extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_user':
		id					int(11)
		username			varchar(128)
		password			varchar(128)
		email				varchar(128)
		fullname			varchar(100)
		initials			varchar(5)
		is_author			int(11)
		is_admin			int(11)
		profile				text
		registered_at		datetime
		last_login_at		datetime
		security_token		varchar(128)
		token_expires_at	datetime
		email_confirmed		tinyint(1)
		want_newsletter		tinyint(1)
		is_banned			tinyint(1)
	 */

	
	// for changing password.
	var $password1;
	var $password2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email', 'required'),
			array('username, password, fullname, email', 'length', 'max'=>128),
			array('initials', 'length', 'max'=>5),
			array('username, initials', 'unique'),
			array('is_admin, is_author, is_banned, want_newsletter, email_confirmed', 'boolean'),
			array('password2', 'compare', 'compareAttribute'=>'password1'),
			array('profile, password1, password2', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Ονομα χρήστη',
			'password' => 'Κρυπτόγραμμα κωδικού',
			'password1' => 'Νέος κωδικός (αφήστε κενό για διατήρηση του υπάρχοντος)',
			'password2' => 'Επανάληψη νέου κωδικού',
			'fullname' => 'Πλήρες όνομα',
			'initials' => 'Αρχικά',
			'email' => 'Email',
			'profile' => 'Προφίλ',
			'registered_at'=>'Ημ/νια εγγραφής',
			'last_login_at'=>'Ημ/νία τελευταίας εισόδου',
			'security_token'=>'Κρυπτόγραμμα ασφαλείας',
			'token_expires_at'=>'Λήξη κρυπτογράμματος ασφαλείας',
			'email_confirmed'=>'Email επιβεβαιωμένο',
			'want_newsletter'=>'Συμμετοχή σε αλληλογραφία',
			'is_banned'=>'Απαγορευμένος',
			'is_admin'=>'Διαχειριστής',
			'is_author'=>'Συντάκτης',
		);
	}

	public function beforeSave()
	{
		if (strlen($this->password1) > 0 && strlen($this->password2) > 0 && $this->password1 == $this->password2)
		{
			$this->password = $this->hashPassword($this->password1);
			Yii::app()->user->setFlash('passwordChanged','Ο κωδικός πρόσβασης άλλαξε');
		}
			
		// allow continue
		return parent::beforeSave();
	}
	
	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return crypt($password,$this->password)===$this->password;
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return crypt($password, $this->generateSalt());
	}

	/**
	 * Generates a token to be used in a url sent through email
	 */
	public function createEmailToken()
	{
		$signature_data = $this->id . $this->email . $this->password;
		return crypt($signature_data, $this->generateSalt());
	}
	
	/**
	 * Validates a token sent in a url through email
	 */
	public function validateEmailToken($token)
	{
		$signature_data = $this->id . $this->email . $this->password;
		return crypt($signature_data, $token) === $token;
	}
	
	/**
	 * Generates a salt that can be used to generate a password hash.
	 *
	 * The {@link http://php.net/manual/en/function.crypt.php PHP `crypt()` built-in function}
	 * requires, for the Blowfish hash algorithm, a salt string in a specific format:
	 *  - "$2a$"
	 *  - a two digit cost parameter
	 *  - "$"
	 *  - 22 characters from the alphabet "./0-9A-Za-z".
	 *
	 * @param int cost parameter for Blowfish hash algorithm
	 * @return string the salt
	 */
	protected function generateSalt($cost=10)
	{
		if(!is_numeric($cost)||$cost<4||$cost>31){
			throw new CException(Yii::t('Cost parameter must be between 4 and 31.'));
		}
		// Get some pseudo-random data from mt_rand().
		$rand='';
		for($i=0;$i<8;++$i)
			$rand.=pack('S',mt_rand(0,0xffff));
		// Add the microtime for a little more entropy.
		$rand.=microtime();
		// Mix the bits cryptographically.
		$rand=sha1($rand,true);
		// Form the prefix that specifies hash algorithm type and cost parameter.
		$salt='$2a$'.str_pad((int)$cost,2,'0',STR_PAD_RIGHT).'$';
		// Append the random salt string in the required base64 format.
		$salt.=strtr(substr(base64_encode($rand),0,22),array('+'=>'.'));
		return $salt;
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->email,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('is_admin',$this->is_admin);
		$criteria->compare('is_author',$this->is_author);

		return new CActiveDataProvider('user', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'username ASC',
			),
			'pagination'=>array(
				'pageSize'=>25,
			),
		));
	}
	
	private static $_items = null;
	public static function dropDownListItems()
	{
		if (self::$_items == null)
		{
			$users = User::model()->findAll(array('order'=>'username'));
			foreach ($users as $user)
				self::$_items[$user->id] = $user->username;
		}
		return self::$_items;
	}
	
	
	public function generateUniqueUsername($email)
	{
		$username = substr($email, 0, strpos($email, '@'));
		$count = User::model()->countByAttributes(array('username'=>$username));
		if ($count == 0)
			return $username;
		
		$num = 1;
		while (User::model()->countByAttributes(array('username'=>$username . $num)) > 0)
			$num++;
		
		return $username . $num;
	}
	
	public function getGreeting()
	{
		$h = date('G');
		$greeting = '';
		if ($h < 7) $greeting = 'Είναι αργά';
		else if ($h < 12) $greeting = 'Καλημέρα';
		else if ($h < 16) $greeting = 'Καλό μεσημέρι';
		else if ($h < 20) $greeting = 'Καλό απόγευμα';
		else $greeting = 'Καλησπέρα';
		
		if ($this->username != '')
			$greeting .= ' ' . $this->username;
		else if ($this->email != '')
			$greeting .= ' ' . $this->email;
		else
			$greeting .= ' user #' . $this->id;
		
		return $greeting . '!';
	}
	
	static function tryGetFullName($id)
	{
		$c = self::model()->find(array(
			'select'=>'fullname',
			'condition'=>'id = :id', 
			'params'=>array(':id'=>$id),
			'limit'=>1,
		));
		return ($c == null) ? '(#' . $id . ')' : $c->fullname;
	}
}	


