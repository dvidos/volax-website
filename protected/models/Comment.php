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
		$months = array('', 'Ιανουαρίου', 'Φεβρουαρίου', 'Μαρτίου', 'Απριλίου', 'Μαϊου', 'Ιουνίου', 'Ιουλίου', 'Αυγούστου', 'Σεπτεμβρίου', 'Οκτωβρίου', 'Νοεμβρίου', 'Δεκεμβρίου');
		
		if (date('Y', $this->create_time) != date('Y'))
		{
			// different year
			return date('j', $this->create_time) . ' ' . $months[date('n', $this->create_time)] . ' ' . date('Y', $this->create_time);
		}
		else if (date('m', $this->create_time) != date('m'))
		{
			// different month
			return date('j', $this->create_time) . ' ' . $months[date('n', $this->create_time)];
		}
		else if (date('d', $this->create_time) != date('d'))
		{
			// different day
			return date('j', $this->create_time) . ' ' . $months[date('n', $this->create_time)];
		}
		else
		{
			return 'Σήμερα';
		}
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
	
	public function notifyEmailSubscribers()
	{
		foreach (Yii::app()->params['newCommentSubscribers'] as $receiver)
		{
			$title = 'Νέο σχόλιο';
			$body = '';
			
			$body .=
				'<p>Κάποιος επισκέπτης προσέθεσε νέο σχόλιο στην σελίδα <b>' . CHtml::link($this->post->title, $this->post->getUrl(true)) . '</b>, ' .
				'σήμερα '. date('d/m/Y') . ', στις ' . date('H:i:s') . ', ώρα server</p>';
			
			if ($this->status == Comment::STATUS_PENDING)
				$body .= '<p>Επειδή ο σχολιασμός απαιτεί έλεγχο, το σχόλιο αυτό δεν θα εμφανιστεί μέχρι να το εγκρίνει κάποιος διαχειριστής.</p>';
			else if ($this->status == Comment::STATUS_APPROVED)
				$body .= '<p>O σχολιασμός δεν απαιτεί έλεγχο, το σχόλιο εμφανίζεται ήδη στην σελίδα, ' . CHtml::link('εδώ', $this->getUrl(null, true)) . '.</p>';
			
			
			$body .= '<div style="border: 1px solid #aaa; padding: 2em; margin: 2em 0;">';
			$body .= 'Ονομα: <b>' . $this->author . '</b><br />';
			$body .= 'Email: <b>' . $this->email . '</b><br />';
			$body .= 'URL: <b>' . $this->url . '</b></p>';
			$body .= '<p>' . $this->content . '</p>';
			$body .= '</div>';
			
			$body .= '<p>Αν είστε administrator, μπορείτε να διαχειριστείτε τα εκκρεμή σχόλια ' . 
					CHtml::link('εδώ', Yii::app()->createAbsoluteUrl('/admin/comments', array('status'=>1))) . '.</p>';
			
			Yii::app()->mailer->send($receiver, $title, $body);
		}
	}
	
	public function notifyAuthor()
	{
		$title = 'Νέο σχόλιο στο ' . $this->post->title;
		$body = '';
		
		$body .=
			'<p>Κάποιος επισκέπτης προσέθεσε νέο σχόλιο στην ανάρτησή σας <b>' . CHtml::link($this->post->title, $this->post->getUrl(true)) . '</b>, ' .
			'σήμερα '. date('d/m/Y') . ', στις ' . date('H:i:s') . ', ώρα server</p>';
		
		if ($this->status == Comment::STATUS_PENDING)
			$body .= '<p>Επειδή ο σχολιασμός απαιτεί έλεγχο, το σχόλιο αυτό δεν θα εμφανιστεί μέχρι να το εγκρίνει κάποιος διαχειριστής.</p>';
		else if ($this->status == Comment::STATUS_APPROVED)
			$body .= '<p>O σχολιασμός δεν απαιτεί έλεγχο, το σχόλιο εμφανίζεται ήδη στην σελίδα, ' . CHtml::link('εδώ', $this->getUrl(null, true)) . '.</p>';
		
		$body .= '<div style="border: 1px solid #aaa; padding: 2em; margin: 2em 0;">';
		$body .= 'Ονομα: <b>' . $this->author . '</b><br />';
		$body .= 'Email: <b>' . $this->email . '</b><br />';
		$body .= 'URL: <b>' . $this->url . '</b></p>';
		$body .= '<p>' . $this->content . '</p>';
		$body .= '</div>';
		
		Yii::app()->mailer->send($this->post->author->email, $title, $body);
	}
}