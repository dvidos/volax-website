<?php

class PostRevision extends CActiveRecord
{
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
		return '{{post_revisions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'length', 'max'=>128),
			array('comment', 'length', 'max'=>255),
			array('category_id', 'numerical'),
			array('title, content, masthead', 'safe'),
			array('was_created, was_deleted', 'boolean'),

			array('id, post_id, revision_no, datetime, user_id, comment, was_created, was_deleted, title, masthead, content, category_id, tags, status', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'post_id'=>'Ανάρτηση',
			'revision_no'=>'Α/Α',
			'user_id'=>'Χρήστης',
			'datetime'=>'Ημ/νία+Ωρα',
			'comment'=>'Σχόλιο',
			'was_created'=>'Δημιουργήθηκε',
			'was_deleted'=>'Διαγράφηκε',
			'title' => 'Τίτλος',
			'masthead' => 'Υπέρτιτλος',
			'content' => 'Περιεχόμενο',
			'category_id' => 'Κατηγορία',
			'tags' => 'Tags',
		);
	}

	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('post_id',$this->post_id);
		$criteria->compare('revision_no',$this->revision_no);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('comment',$this->comment, true);
		$criteria->compare('was_created',$this->was_created);
		$criteria->compare('was_deleted',$this->was_deleted);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('masthead',$this->masthead,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('tags',$this->tags,true);
		
		return new CActiveDataProvider('PostRevision', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'`datetime` DESC',
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
	}
	
	/**
	 * Return a user friendly date, in greek date format, dd-mm-yy, hh:mm
	 */
	public function getFriendlyDatetime()
	{
		return 
			substr($this->datetime, 8, 2) . '-' . 
			substr($this->datetime, 5, 2) . '-' . 
			substr($this->datetime, 2, 2) . ', ' . 
			substr($this->datetime, 11, 5);
	}
	
	/**
	 * Return a user friendly change caption
	 */
	public function getFriendlyAction()
	{
		return ($this->was_deleted ? 'Διαγραφή' : ($this->was_created ? 'Δημιουργία' : 'Διόρθωση'));
	}
	
	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
		if (!parent::beforeSave())
			return false;
		
		if($this->isNewRecord)
		{
			Yii::trace('finding revision_no for post id ' . $this->post_id, 'debug');
			$max = $this->find(array(
				'select'=>'MAX(revision_no) as revision_no',
				'condition'=>'post_id = :pid',
				'params'=>array(
					':pid'=>$this->post_id,
				),
			));
			Yii::trace('select MAX() returned: ' . CVarDumper::dumpAsString($max), 'debug');
			$this->revision_no = $max->revision_no + 1;
			$this->user_id = Yii::app()->user->id;
		}
		
		return true;
	}

	/**
	 * Compare with a post and return an array of changes
	 */
	public function getDifferencesWithPost($post)
	{
		$diffs = array();
		
		if (strcmp($post->title, $this->title) != 0)
			$diffs[] = array('field'=>'title', 'caption'=>'Τίτλος', 'old'=>$this->title, 'new'=>$post->title);
		
		if (strcmp($post->masthead, $this->masthead) != 0)
			$diffs[] = array('field'=>'masthead', 'caption'=>'Υπέρτιτλος', 'old'=>$this->masthead, 'new'=>$post->masthead);
		
		if (strcmp($post->content, $this->content) != 0)
			$diffs[] = array('field'=>'content', 'caption'=>'Κείμενο', 'old'=>$this->content, 'new'=>$post->content);
		
		if ($post->category_id != $this->category_id)
			$diffs[] = array('field'=>'category_id', 'caption'=>'Κατηγορία', 'old'=>$this->category_id, 'new'=>$post->category_id);
		
		if (strcmp($post->tags, $this->tags) != 0)
			$diffs[] = array('field'=>'tags', 'caption'=>'Tags', 'old'=>$this->tags, 'new'=>$post->tags);
		
		return $diffs;
	}
	
	/**
	 * Compare with another revision and return an array of changes
	 */
	public function getDifferencesWithRevision($revision)
	{
		$older = ($this->revision_no < $revision->revision_no) ? $this : $revision;
		$newer = ($this->revision_no > $revision->revision_no) ? $this : $revision;
		
		$diffs = array();
		
		if (strcmp($revision->title, $this->title) != 0)
			$diffs[] = array('field'=>'title', 'caption'=>'Τίτλος', 'old'=>$older->title, 'new'=>$newer->title);
		
		if (strcmp($revision->masthead, $this->masthead) != 0)
			$diffs[] = array('field'=>'masthead', 'caption'=>'Υπέρτιτλος', 'old'=>$older->masthead, 'new'=>$newer->masthead);
		
		if (strcmp($revision->content, $this->content) != 0)
			$diffs[] = array('field'=>'content', 'caption'=>'Κείμενο', 'old'=>$older->content, 'new'=>$newer->content);
		
		if ($revision->category_id != $this->category_id)
			$diffs[] = array('field'=>'category_id', 'caption'=>'Κατηγορία', 'old'=>$older->category_id, 'new'=>$newer->category_id);
		
		if (strcmp($revision->tags, $this->tags) != 0)
			$diffs[] = array('field'=>'tags', 'caption'=>'Tags', 'old'=>$older->tags, 'new'=>$newer->tags);
		
		return $diffs;
	}
	
	
	/**
	 * Find next or previous revision of same post
	 */
	public function findNextRevision($next)
	{
		$op = $next ? '>' : '<';
		$so = $next ? 'ASC' : 'DESC';
		
		$rev = self::model()->find(array(
			'condition'=>'post_id = :pid AND revision_no '.$op.' :rn',
			'params'=>array(':pid'=>$this->post_id, ':rn'=>$this->revision_no),
			'order'=>'`datetime` '.$so.', `id` '.$so.'',
		));
		
		return $rev;
	}
}


