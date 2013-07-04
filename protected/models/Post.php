<?php

class Post extends CActiveRecord
{
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;
	const STATUS_ARCHIVED=3;

	private $_oldTags;

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
		return '{{post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, category_id, status, layout', 'required'),
			array('status', 'in', 'range'=>array(1,2,3)),
			array('title', 'length', 'max'=>128),
			array('tags', 'match', 'pattern'=>'/^[\S\s,]+$/', 'message'=>'Tags must be separated with comma.'),
			array('tags', 'normalizeTags'),
			array('category_id, status, layout, in_home_page', 'numerical'),
			array('image_filename, image2_filename, allow_comments', 'safe'),

			array('id, title, prologue, masthead, category_id, content, image_filename, image2_filename, tags, status, in_home_page, layout, author_id, allow_comments', 'safe', 'on'=>'search'),
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
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'condition'=>'comments.status='.Comment::STATUS_APPROVED, 'order'=>'comments.create_time DESC'),
			'commentCount' => array(self::STAT, 'Comment', 'post_id', 'condition'=>'status='.Comment::STATUS_APPROVED),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Τίτλος',
			'prologue' => 'Πρόλογος',
			'masthead' => 'Υπέρτιτλος',
			'content' => 'Περιεχόμενο',
			'category_id' => 'Κατηγορία',
			'image_filename' => 'Εικόνα',
			'image2_filename' => 'Μικρή εικόνα',
			'layout' => 'Layout',
			'tags' => 'Tags',
			'status' => 'Κατάσταση',
			'in_home_page' => 'Σε αρχική σελίδα',
			'allow_comments' => 'Επιτρέπονται σχόλια',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'author_id' => 'Συγγραφέας',
		);
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('post/view', array(
			'id'=>$this->id,
			'title'=>$this->title,
		));
	}

	/**
	 * @return array a list of links that point to the post list filtered by every tag of this post
	 */
	public function getTagLinks()
	{
		$links=array();
		foreach(Tag::string2array($this->tags) as $tag)
			$links[]=CHtml::link(CHtml::encode($tag), array('post/list', 'tag'=>$tag));
		return $links;
	}

	/**
	 * Normalizes the user-entered tags.
	 */
	public function normalizeTags($attribute,$params)
	{
		$this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
	}

	/**
	 * Adds a new comment to this post.
	 * This method will set status and post_id of the comment accordingly.
	 * @param Comment the comment to be added
	 * @return boolean whether the comment is saved successfully
	 */
	public function addComment($comment)
	{
		if(Yii::app()->params['commentNeedApproval'])
			$comment->status=Comment::STATUS_PENDING;
		else
			$comment->status=Comment::STATUS_APPROVED;
		$comment->post_id=$this->id;
		return $comment->save();
	}

	
	/**
	 * To set default values
	 */
	protected function afterConstruct()
	{
		$this->in_home_page = true;
	}
	
	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		$this->_oldTags=$this->tags;
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
			{
				$this->create_time=$this->update_time=time();
				$this->author_id=Yii::app()->user->id;
			}
			else
				$this->update_time=time();
			return true;
		}
		else
			return false;
	}

	/**
	 * This is invoked after the record is saved.
	 */
	protected function afterSave()
	{
		parent::afterSave();
		Tag::model()->updateFrequency($this->_oldTags, $this->tags);
	}

	/**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
		Comment::model()->deleteAll('post_id='.$this->id);
		Tag::model()->updateFrequency($this->tags, '');
	}

	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('title',$this->title,true);
		$criteria->compare('prologue',$this->prologue,true);
		$criteria->compare('masthead',$this->masthead,true);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image_filename',$this->image_filename,true);
		$criteria->compare('image2_filename',$this->image2_filename,true);
		$criteria->compare('layout', $this->layout);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('in_home_page',$this->in_home_page);
		
		return new CActiveDataProvider('Post', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'create_time DESC',
			),
			'pagination'=>array(
				'pageSize'=>25,
			),
		));
	}
	
	/**
	 * Return only the first portion of an article, up to the "more" link
	 * Usable in posts listings.
	 */
	public function getContentHtmlUptoMore()
	{
		// using markdown syntax.
		$parser = new CMarkdownParser();
		$content = $parser->transform($this->content);

		// go up to the "[more]"
		$pos = strpos($content, Yii::app()->params['postsMoreIndicator']);
		if ($pos !== false)
		{
			$content = substr($content, 0, $pos);
			$content .= CHtml::link(Yii::app()->params['postsMoreLinkText'], array(
				'/post/view', 
				'id'=>$this->id, 
				'title'=>$this->title,
				'#'=>'more',
			), array(
				'class'=>'more',
			));
		}
		
		return $content;
	}
	
	/**
	 * Return the whole content, changing the "[more]" indicator to an anchor
	 * Usable in full page posts rendering
	 */
	public function getContentHtmlIncludingMore()
	{
		// using markdown syntax.
		$parser = new CMarkdownParser();
		$content = $parser->transform($this->content);
		
		// put a "more" tag
		if (strpos($content, Yii::app()->params['postsMoreIndicator']) !== false)
			$content = str_replace(Yii::app()->params['postsMoreIndicator'], '<a id="more" name="more"></a>', $content);
		
		return $content;
	}
	
	
	public static function getLayoutOptions()
	{
		// what WordPress names Post Format: a hint to the theme for rendering the post. see codex.wordpress.org/Post_Formats
		
		return array(
			0 => 'Standard',
			1 => 'Aside', // without a title, similar to a facebook note.
			2 => 'Link', // the first link in the content, or the title itself, if it is a url
			3 => 'Gallery', // usually a bunch of photos.
			4 => 'Status', // similar to twitter status update.
			5 => 'Quote', // a quotation.
			6 => 'Image', // a single image
			7 => 'Video', // a single video
			8 => 'Audio', // audio file. podcasting, recording etc.
		);
	}
	
	
	public static function getLayoutCaption($layout)
	{
		$options = self::getLayoutOptions();
		if (array_key_exists($layout, $options))
			return $options[$layout];
		else
			return '(unknown)';
	}

	
	
	
}