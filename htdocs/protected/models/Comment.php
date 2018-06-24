<?php

class Comment extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_comment':
	 * @var integer $id
	 * @var string $content
	 * @var integer $status
	 * @var integer $create_time
	 * @var string $author
	 * @var string $email
	 * @var string $url
	 * @var integer $post_id
	 */
	const STATUS_PENDING=1;
	const STATUS_APPROVED=2;
	
	var $captcha_content;

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
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, author, email', 'required'),
			array('author, email, url', 'length', 'max'=>128),
			array('email','email'),
			array('url','url'),
			
			array('captcha_content', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'on'=>'insert'),
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
			'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'content' => 'Σχόλιο',
			'status' => 'Κατάσταση',
			'create_time' => 'Δημιουργία',
			'author' => 'Συγγραφέας',
			'email' => 'Email',
			'url' => 'Website',
			'post_id' => 'Post',
			'captcha_content'=>'Κωδικός επιβεβαίωσης',
		);
	}

	/**
	 * Approves a comment.
	 */
	public function approve()
	{
		$this->status=Comment::STATUS_APPROVED;
		$this->update(array('status'));
	}

	/**
	 * @param Post the post that this comment belongs to. If null, the method
	 * will query for the post.
	 * @return string the permalink URL for this comment
	 */
	public function getUrl($post=null, $absolute = false)
	{
		if($post===null)
			$post=$this->post;
		
		return $post->getUrl($absolute) . '#c' . $this->id;
	}

	/**
	 * @return string the hyperlink display for the current comment's author
	 */
	public function getAuthorLink()
	{
		if(!empty($this->url))
			return CHtml::link(CHtml::encode($this->author),$this->url);
		else
			return CHtml::encode($this->author);
	}

	/**
	 * @return integer the number of comments that are pending approval
	 */
	public function getPendingCommentCount()
	{
		return $this->count('status='.self::STATUS_PENDING);
	}

	/**
	 * @param integer the maximum number of comments that should be returned
	 * @return array the most recently added comments
	 */
	public function findRecentComments($limit=10)
	{
		return $this->with('post')->findAll(array(
			'condition'=>'t.status='.self::STATUS_APPROVED,
			'order'=>'t.create_time DESC',
			'limit'=>$limit,
		));
	}

	public function getFriendlyCreateTime()
	{
		if (strcmp(date('dmY', $this->create_time), date('dmY')) == 0)
			return 'Σήμερα';
		
		$months = array('', 'Ιανουαρίου', 'Φεβρουαρίου', 'Μαρτίου', 'Απριλίου', 'Μαϊου', 'Ιουνίου', 'Ιουλίου', 'Αυγούστου', 'Σεπτεμβρίου', 'Οκτωβρίου', 'Νοεμβρίου', 'Δεκεμβρίου');
		return date('j', $this->create_time) . ' ' . $months[date('n', $this->create_time)] . ' ' . date('Y', $this->create_time);
	}
	
	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
				$this->create_time=time();
			return true;
		}
		else
			return false;
	}
	
	public function notifyAllByEmail()
	{
		if ($this->post == null)
			return;
		
		// create a list of recipients
		// arrays are assigned by copy in PHP
		$all_recipients = Yii::app()->params['newCommentSubscribers'];
		if ($this->post->author != null)
			$all_recipients[] = $this->post->author->email;
		foreach ($this->post->comments as $comment)
			$all_recipients[] = $comment->email;
		$recipients = array_unique($all_recipients);
		
		
		foreach ($recipients as $recipient)
		{
			$title = '[Νέο σχόλιο] ' . $this->post->title;
			
			$body = '';
			$body .= 
				'<p>O/Η <b>' . $this->author . '</b> πρόσθεσε ένα σχόλιο '.
				'στην ανάρτηση <b>' . CHtml::link($this->post->title, $this->post->getUrl(true)) . '</b></p>';
			$body .= '<div style="border: 1px solid #aaa; padding: 1em; margin: 1em 0;">';
			$body .= '<p>' . nl2br($this->content) . '</p>';
			$body .= '</div>';
			$body .= '<p>&nbsp;</p>';
			$body .= '<p>Φιλικά,<br />η ομάδα του <a href="http://volax.gr/">volax.gr</a></p>';
			
			Yii::app()->mailer->send($recipient, $title, $body);
		}
	}
}

