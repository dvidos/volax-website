<?php

class Post extends CActiveRecord
{
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;
	const STATUS_ARCHIVED=3;
	const TIMESTAMP_FORMAT = 'd-m-Y H:i'; // for date() function
	const TIMESTAMP_PARSE = 'dd-MM-yyyy hh:mm'; // for CDateTimeParser Yii class

	var $editable_create_time;
	
	var $_oldTitle;
	var $_oldCategoryId;
	var $_oldStatus;
	var $_oldDesiredWidth;
	var $_oldLayout;
	var $_oldHomePage;
	var $_oldAllowComments;
	var $_oldImageFilename;
	var $_oldTags;
	var $_oldAuthorId;
	var $_oldCreated;
	var $_oldUpdated;
	var $_oldContent;
	var $_oldPrologue;
	var $_oldMasthead;
	var $_original_editable_create_time;
	
	
	var $revision = null;
	
	public function createRevision()
	{
		$rev = new PostRevision();
		
		$rev->post_id = $this->id;
		$rev->title = $this->title;
		$rev->prologue = $this->prologue;
		$rev->masthead = $this->masthead;
		$rev->content = $this->content;
		$rev->category_id = $this->category_id;
		$rev->tags = $this->tags;
		
		return $rev;
	}
	
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
			array('category_id, status', 'required'),
			array('status', 'in', 'range'=>array(1,2,3)),
			array('title', 'length', 'max'=>128),
			array('tags', 'match', 'pattern'=>'/^[\S\s,]+$/', 'message'=>'Tags must be separated with comma.'),
			array('tags', 'normalizeTags'),
			array('category_id, status, layout, desired_width, in_home_page, author_id', 'numerical'),
			array('title, content, image_filename, allow_comments, prologue, masthead', 'safe'),
			array('editable_create_time', 'safe'),

			array('id, title, prologue, masthead, category_id, content, image_filename, tags, status, in_home_page, layout, author_id, allow_comments', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'condition'=>'comments.status='.Comment::STATUS_APPROVED, 'order'=>'comments.create_time'),
			'commentCount' => array(self::STAT, 'Comment', 'post_id', 'condition'=>'status='.Comment::STATUS_APPROVED),
			'oldAuthor' => array(self::BELONGS_TO, 'User', '_oldAuthorId'),
			'oldCategory' => array(self::BELONGS_TO, 'Category', '_oldCategoryId'),
			'revisions' => array(self::HAS_MANY, 'PostRevision', 'post_id'),
			'revisionCount' => array(self::STAT, 'PostRevision', 'post_id'),
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
			'layout' => 'Layout',
			'desired_width' => 'Πλάτος',
			'tags' => 'Tags',
			'status' => 'Κατάσταση',
			'in_home_page' => 'Σε αρχική σελίδα',
			'allow_comments' => 'Επιτρέπονται σχόλια',
			'create_time' => 'Δημιουργία',
			'update_time' => 'Ενημέρωση',
			'author_id' => 'Συγγραφέας',
		);
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl($absolute = false)
	{
		if ($absolute)
			return Yii::app()->createAbsoluteUrl('post/view', array('id'=>$this->id,'title'=>$this->title));
		else
			return Yii::app()->createUrl('post/view', array('id'=>$this->id,'title'=>$this->title));
	}

	/**
	 * @return array a list of links that point to the post list filtered by every tag of this post
	 */
	public function getTagLinks()
	{
		$links=array();
		foreach(Tag::string2array($this->tags) as $tag)
		{
			// ignore tags beggining with a parenthesis, they are for administration.
			if (substr($tag, 0, 1) == '(')
				continue;
			
			$links[]=CHtml::link(CHtml::encode($tag), array('post/list', 'tag'=>$tag));
		}
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
		Yii::log('Post::afterConstruct()', 'trace');
		
		$this->in_home_page = true;
		$this->allow_comments = true;
		$this->desired_width = 2;
		
		// proposed author maybe the same as user
		if (Yii::app()->user != null)
			$this->author_id=Yii::app()->user->id;
		
		$this->editable_create_time = date(self::TIMESTAMP_FORMAT);
		$this->_original_editable_create_time = $this->editable_create_time;
	}
	
	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		Yii::log('Post::afterFind()', 'trace');
		
		$this->_oldTitle = $this->title;
		$this->_oldPrologue = $this->prologue;
		$this->_oldMasthead = $this->masthead;
		$this->_oldCategoryId = $this->category_id;
		$this->_oldContent = $this->content;
		$this->_oldImageFilename = $this->image_filename;
		$this->_oldLayout = $this->layout;
		$this->_oldDesiredWidth = $this->desired_width;
		$this->_oldTags = $this->tags;
		$this->_oldStatus = $this->status;
		$this->_oldHomePage = $this->in_home_page;
		$this->_oldAllowComments = $this->allow_comments;
		$this->_oldCreated = $this->create_time;
		$this->_oldAuthorId = $this->author_id;
		
		// format editable dates
		$this->editable_create_time = date(self::TIMESTAMP_FORMAT, $this->create_time);
		$this->_original_editable_create_time = $this->editable_create_time;
		
		$this->revision = $this->createRevision();
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
		if (!parent::beforeSave())
			return false;
		
		Yii::log('Post::beforeSave()', 'trace');
		if ($this->isNewRecord)
			$this->create_time = time();
		$this->update_time = time();

		// if create or update time was edited, update it.
		if ($this->editable_create_time != $this->_original_editable_create_time && $this->editable_create_time != '')
			$this->create_time = CDateTimeParser::parse($this->editable_create_time, self::TIMESTAMP_PARSE);
		
		return true;
	}

	/**
	 * This is invoked after the record is saved.
	 */
	protected function afterSave()
	{
		parent::afterSave();
		
		Yii::log('Post::afterSave()', 'trace');
		Tag::model()->updateFrequency($this->_oldTags, $this->tags);
		
		// save a revision.
		if ($this->revision != null)
		{
			$diffs = $this->revision->getDifferencesWithPost($this);
			Yii::log("revision to post differences:\r\n" . CVarDumper::dumpAsString($diffs), 'debug');
			if (count($diffs) > 0)
				$this->revision->save();
		}
	}

	/**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
		Yii::log('Post::afterDelete()', 'trace');
		
		Comment::model()->deleteAll('post_id='.$this->id);
		Tag::model()->updateFrequency($this->tags, '');
		
		
		// mark deletion
		if ($this->revision != null)
		{
			$this->revision->was_deleted = 1;
			$revision->save();
		}
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
				'pageSize'=>15,
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
		//$parser = new CMarkdownParser();
		//$content = $parser->transform($this->content);
		$content = $this->content;

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
		//$parser = new CMarkdownParser();
		//$content = $parser->transform($this->content);
		$content = $this->content;
		
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
	
	
	public static function getDesiredWidthOptions()
	{
		return array(
			1 => 'Στενό',
			2 => 'Μεσαίο',
			3 => 'Φαρδύ',
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
	
	
	
	public function notifyEmailSubscribers($is_new = false)
	{
		foreach (Yii::app()->params['postSavedSubscribers'] as $receiver)
			$this->notifyEmailSubscriber($receiver, $is_new);
	}
	
	public function notifyEmailSubscriber($email, $is_new = false)
	{
		$title = $is_new ? 'Νέα ανάρτηση: ' . $this->title : 'Διορθώθηκε: ' . $this->title;
		$body = $this->prepareEmailBody($is_new);
		
		Yii::app()->mailer->send($email, $title, $body);
	}
	
	private function prepareEmailBody($is_new = false)
	{
		$body = '';
		
		$body .= '<p>';
		$body .= 
			'Ο χρήστης <b>' . Yii::app()->user->name . '</b> ' .
			($is_new ? 'δημιούργησε' : 'διόρθωσε') . ' την παρακάτω ' . 
			(($this->status == Post::STATUS_PUBLISHED || $this->status == Post::STATUS_ARCHIVED) ? 'δημόσια' : 'πρόχειρη') .
			' ανάρτηση, σήμερα '. date('d/m/Y') . ', στις ' . date('H:i:s') . ', ώρα server. ';
		
		$h1 = '<h1>' . CHtml::encode($this->title) . ' </h1>';
		$body .= CHtml::link($h1, $this->getUrl(true), array('style'=>'text-decoration: none; color:#2B5BA8;')) . "\r\n";
		
		
		$body .= '<p>';

		if (!$is_new)
		{
			if ($this->_oldTitle != $this->title)
				$body .= 'Ο τίτλος άλλαξε από "' . $this->_oldTitle . '" σε "' . $this->title . '"<br />';
				
			if ($this->_oldCategoryId != $this->category_id)
				$body .= 'Η κατηγορία άλλαξε από "' . 
					(($this->oldCategory == null) ? '(καμμία)' : $this->oldCategory->title) . '" σε "' . 
					(($this->category == null) ? '(καμμία)' : $this->category->title) . '"<br />';
				
			if ($this->_oldImageFilename != $this->image_filename)
				$body .= 'Η εικόνα άλλαξε από "' . $this->_oldImageFilename . '" σε "' . $this->image_filename . '"<br />';
				
			$layouts = $this->getLayoutOptions();
			if ($this->_oldLayout != $this->layout)
				$body .= 'Το layout άλλαξε από "' . $layouts[$this->_oldLayout] . '" σε "' . $layouts[$this->layout] . '"<br />';
				
			$widths = $this->getDesiredWidthOptions();
			if ($this->_oldDesiredWidth != $this->desired_width)
				$body .= 'Το πλάτος άλλαξε από "' . $widths[$this->_oldDesiredWidth] . '" σε "' . $widths[$this->desired_width] . '"<br />';
				
			if ($this->_oldTags != $this->tags)
				$body .= 'Τα tags άλλαξαν από "' . $this->_oldTags . '" σε "' . $this->tags . '"<br />';
				
			$statusCaptions = array(1=>'Draft', 2=>'Published', 3=>'Archived');
			if ($this->_oldStatus != $this->status)
				$body .= 'Η κατάσταση άλλαξε από "' . $statusCaptions[$this->_oldStatus] . '" σε "' . $statusCaptions[$this->status] . '"<br />';
				
			if ($this->_oldHomePage != $this->in_home_page)
				$body .= 'Το σε-αρχική-σελίδα άλλαξε από "' . $this->_oldHomePage . '" σε "' . $this->in_home_page . '"<br />';
				
			if ($this->_oldAllowComments != $this->allow_comments)
				$body .= 'To επιτρέπονται-σχόλια άλλαξε από "' . $this->_oldAllowComments . '" σε "' . $this->allow_comments . '"<br />';
				
			if ($this->_oldCreated != $this->create_time)
				$body .= 'Η ημ/νία δημιουργίας άλλαξε από "' . 
					date(self::TIMESTAMP_FORMAT, $this->_oldCreated) . '" σε "' .
					date(self::TIMESTAMP_FORMAT, $this->create_time) . '"<br />';
				
			if ($this->_oldAuthorId != $this->author_id)
				$body .= 'Ο συγγραφέας άλλαξε από "' . 
					(($this->oldAuthor == null) ? '(κανένας)' : $this->oldAuthor->username) . '" σε "' .
					(($this->author == null) ? '(κανένας)' : $this->author->username) . '"<br />';
			
			if ($this->_oldMasthead != $this->masthead)
				$body .= $this->getContentChangeDescription($this->_oldMasthead, $this->masthead, 'Υπέρτιτλος', $is_new);
				
			if ($this->_oldMasthead != $this->masthead)
				$body .= $this->getContentChangeDescription($this->_oldPrologue, $this->prologue, 'Πρόλογος', $is_new);
		}
		
		$body .= $this->getContentChangeDescription($this->_oldContent, $this->content, 'Περιεχόμενο', $is_new);
		
		return $body;
	}
	
	private function getContentChangeDescription($oldText, $newText, $contentTitle = '', $is_new = false)
	{
		if ($newText == '')
			return '';
		
		$html = '';
		
		if ($contentTitle != '')
			$html .= '<h3 style="margin-bottom: -1em;">' . CHtml::encode($contentTitle) . '</h3>';
		
		$html .= '<div style="border: 1px solid #aaa; padding: 1em; margin: 1em 0;">';
		$html .= ($is_new) ? $newText : Yii::app()->differer->compare($oldText, $newText);
		$html .= '</div>';
		
		return $html;
	}
	
	public function getImageHtml()
	{
		if ($this->image_filename == '')
			return false;
		
		// find dimensions to scale
		$fn = $this->image_filename;
		
		// must find a better way for this...
		if (substr($fn, 0, 8) == '/volax4/')
			$fn = substr($fn, 8);
		else if (substr($fn, 0, 7) == '/volax/')
			$fn = substr($fn, 7);
		else if (substr($fn, 0, 4) == '/v4/')
			$fn = substr($fn, 4);
		
		return CHtml::image(Yii::app()->baseUrl . '/' . ltrim($fn, '/'));
	}

	public function getContentLinks()
	{
		$re = '/<a.+?href="([^"]+)".+?<\/a>/i';
		$matches = array();
		preg_match_all($re, $this->content, $matches);
		$links = $matches[1];
		return $links;
	}
	
	public function getContentImages()
	{
		$re = '/<img.+?src="([^"]+)".+?>/i';
		$matches = array();
		preg_match_all($re, $this->content, $matches);
		$images = $matches[1];
		if ($this->image_filename != '')
			array_unshift($images, $this->image_filename);
		return $images;
	}
	
}


