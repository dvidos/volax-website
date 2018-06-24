<?php

class Post extends CActiveRecord
{
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;
	const STATUS_ARCHIVED=3;
	
	const TIMESTAMP_FORMAT = 'd-m-Y H:i'; // for date() function
	const TIMESTAMP_PARSE = 'dd-MM-yyyy hh:mm'; // for CDateTimeParser Yii class

	var $editable_create_time;
	var $_original_tags;
	var $_original_editable_create_time;
	var $revision = null;
	
	public function createRevision()
	{
		$rev = new PostRevision();
		
		$rev->post_id = $this->id;
		$rev->title = $this->title;
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
			array('title, category_id, status', 'required'),
			array('status', 'in', 'range'=>array(1,2,3)),
			array('title', 'length', 'max'=>128),
			array('title', 'application.components.validators.NoMixedLangValidator'),
			array('tags', 'match', 'pattern'=>'/^[\S\s,]+$/', 'message'=>'Tags must be separated with comma.'),
			array('tags', 'normalizeTags'),
			array('tags', 'application.components.validators.NoMixedLangValidator'),
			array('category_id, status, layout, desired_width, in_home_page, sticky, author_id', 'numerical'),
			array('category_id', 'validateCategoryId'),
			array('title, content, allow_comments, masthead, discussion', 'safe'),
			array('editable_create_time', 'safe'),

			array('id, title, masthead, category_id, content, tags, status, in_home_page, layout, author_id, allow_comments', 'safe', 'on'=>'search'),
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
			'masthead' => 'Υπέρτιτλος',
			'content' => 'Περιεχόμενο',
			'category_id' => 'Κατηγορία',
			'layout' => 'Layout',
			'desired_width' => 'Πλάτος',
			'tags' => 'Tags',
			'status' => 'Κατάσταση',
			'in_home_page' => 'Σε αρχική σελίδα',
			'sticky' => 'Sticky',
			'allow_comments' => 'Επιτρέπονται σχόλια',
			'discussion'=>'Σημειώσεις',
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
	
	public function getSharingUrl()
	{
		// our short url, for twitter etc, without SEO title part.
		$url = Yii::app()->createAbsoluteUrl('posts/' . $this->id);
		
		// remove index.php, if it exists.
		// a .htaccess should rename this to correct for anyway
		$url = str_replace('/index.php', '', $url);
		
		return $url;
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
	 * Make sure we have a meaningful category_id
	 */
	public function validateCategoryId($attribute,$params)
	{
		$cid = $this->$attribute;
		if ($cid == 0)
		{
			$this->addError($attribute, 'Η κατηγορία είναι κενή');
			return;
		}
		
		$cat = Category::model()->findByPk($cid);
		if ($cat == null)
		{
			$this->addError($attribute, 'Η κατηγορία δεν βρέθηκε');
			return;
		}
		
		// make sure this is not a category containing subcategories
		$c = Category::model()->countByAttributes(array('parent_id'=>$cid));
		if ($c > 0)
		{
			$this->addError($attribute, 'Η κατηγορία περιέχει υποκατηγορίες');
			return;
		}
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
		$this->sticky = false;
		$this->desired_width = 2;
		$this->category_id = Yii::app()->params['defaultPostCategoryId'];
		
		// proposed author maybe the same as user
		if (Yii::app()->user != null)
			$this->author_id=Yii::app()->user->id;
		
		$this->editable_create_time = date(self::TIMESTAMP_FORMAT);
		$this->_original_editable_create_time = $this->editable_create_time;
		$this->_original_tags = $this->tags;
	}
	
	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		Yii::log('Post::afterFind()', 'trace');
		
		// format editable dates
		$this->editable_create_time = date(self::TIMESTAMP_FORMAT, $this->create_time);
		$this->_original_editable_create_time = $this->editable_create_time;
		$this->_original_tags = $this->tags;
		
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
		Yii::log('isNewRecord: ' . ($this->isNewRecord ? 'true' : 'false'));
		
		Tag::model()->updateFrequency($this->_original_tags, $this->tags);
		
		// save a revision (isNewRecord is true if new record, although we could have auto-incremented id value)
		if ($this->isNewRecord)
		{
			$this->revision = $this->createRevision();
			$this->revision->was_created = 1;
			$this->revision->save();
		}
		else 
		{
			if ($this->revision != null)
			{
				$diffs = $this->revision->getDifferencesWithPost($this);
				Yii::log("revision to post differences:\r\n" . CVarDumper::dumpAsString($diffs), 'debug');
				if (count($diffs) > 0)
					$this->revision->save();
			}
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
			$this->revision->save();
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
		$criteria->compare('masthead',$this->masthead,true);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('layout', $this->layout);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('in_home_page',$this->in_home_page);
		$criteria->compare('sticky',$this->sticky);
		$criteria->compare('allow_comments',$this->allow_comments);
		
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
		if (strcmp(date('dmY', $this->create_time), date('dmY')) == 0)
			return 'Σήμερα';
		
		$months = array('', 'Ιανουαρίου', 'Φεβρουαρίου', 'Μαρτίου', 'Απριλίου', 'Μαϊου', 'Ιουνίου', 'Ιουλίου', 'Αυγούστου', 'Σεπτεμβρίου', 'Οκτωβρίου', 'Νοεμβρίου', 'Δεκεμβρίου');
		return date('j', $this->create_time) . ' ' . $months[date('n', $this->create_time)] . ' ' . date('Y', $this->create_time);
	}

	public function getFriendlyStatus()
	{
		if ($this->status == Post::STATUS_DRAFT)
			return 'Πρόχειρη';
		else if ($this->status == Post::STATUS_PUBLISHED)
			return 'Δημοσιευμένη';
		else if ($this->status == Post::STATUS_ARCHIVED)
			return 'Αρχειοθετημένη';
		else
			return '(Αγνωστο)';
	}
	
	public function notifyEmailSubscribers($is_new = false, $is_deleted = false)
	{
		$subject = ($is_new ? 'Νέα ανάρτηση' : ($is_deleted ? 'Διαγράφηκε' : 'Διορθώθηκε')) . ': ' . $this->title;
		foreach (Yii::app()->params['postSavedSubscribers'] as $receiver)
		{
			$body = Yii::app()->controller->renderPartial('_changeNotificationEmail', array(
				'is_new'=>$is_new,
				'is_deleted'=>$is_deleted,
				'post'=>$this,
				'revision'=> ($is_new || $is_deleted) ? null : $this->revision,
			), true);
			Yii::app()->mailer->send($receiver, $subject, $body);
		}
	}
	
	public function getContentLinks()
	{
		$re = '/href\s*?=\s*?"([^"]+)"/i';
		$matches = array();
		preg_match_all($re, $this->content, $matches);
		$links = $matches[1];
		
		// also, get from macros
		$links = array_merge($links, Yii::app()->contentProcessor->getLinks($this->content));
		
		return $links;
	}
	
	public function getContentImages()
	{
		$re = '/<img.+?src="([^"]+)".+?>/i';
		$matches = array();
		preg_match_all($re, $this->content, $matches);
		$images = $matches[1];
		
		// also, get from macros
		$images = array_merge($images, Yii::app()->contentProcessor->getImages($this->content));
		
		return $images;
	}

	public function searchPostsForContent($key, $regex)
	{
		$results = array();
		$crit = new CDbCriteria();
		$crit->select = 'id, title';
		$crit->order = 'update_time DESC';
		
		if ($regex) // "1" for clicked, "" for not.
		{
			$crit->addCondition('content REGEXP :key');
			$crit->params = array(':key'=>$key);
		}
		else 
		{
			$crit->addSearchCondition('content', $key);
			
		}
		
		$posts = $this->findAll($crit);
		foreach ($posts as $post)
			$results[] = array('id'=>$post->id, 'title'=>$post->title);
		
		return $results;
	}
	

	/**
	 * Return true if url is external
	 */
	public static function isAbsoluteUrl($url)
	{
		return (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://');
	}
	
	/**
	 * Convert any url ponting to our domain to a relative form
	 */
	public static function makeRelativeUrl($url)
	{
		if (self::isAbsoluteUrl($url))
		{
			$local_sites = array('http://volax.gr', 'http://www.volax.gr');
			foreach ($local_sites as $local_site)
			{
				if (strlen($url) > strlen($local_site) && substr($url, 0, strlen($local_site)) == $local_site)
				{
					$url = substr($url, strlen($local_site));
					break;
				}
			}
		}
		
		// so, we have an interal url. remove possible base url
		if (substr($url, 0, strlen(Yii::app()->baseUrl)) == Yii::app()->baseUrl)
			$url = substr($url, strlen(Yii::app()->baseUrl));
		
		return $url;
	}
	
	/**
	 * Check url for local files existance
	 * Return values:
	 *    1: exists localy
	 *    0: does not exist localy
	 *   -1: cannot test, url is external
	 */
	public static function checkUrlExistance($url)
	{
		// in case it is pointing to volax.gr, convert to relative
		$url = self::makeRelativeUrl($url);
		
		// if it still is an external url, we cannot test
		if (self::isAbsoluteUrl($url))
			return -1;

		// see if file exists
		$rootPath = dirname(Yii::app()->basePath);
		$exists = file_exists($rootPath . $url);
		
		return $exists ? 1 : 0;
	}
	
	static function tryGetTitle($id)
	{
		$c = self::model()->find(array(
			'select'=>'title',
			'condition'=>'id = :id', 
			'params'=>array(':id'=>$id),
			'limit'=>1,
		));
		return ($c == null) ? '(#' . $id . ')' : $c->title;
	}
	
	
	public function getExcerpt($length = 150)
	{
		$excerpt = '';
		
		if (!empty($this->content))
		{
			$excerpt = trim(mb_substr(strip_tags($this->content), 0, $length));
		}
		else if (!empty($this->masthead))
		{
			$excerpt = trim(mb_substr(strip_tags($this->masthead), 0, $length));
		}
		
		// remove more
		$excerpt = str_replace('[more]', '', $excerpt);
		$excerpt = str_replace("\r\n", ' ', $excerpt);
		$excerpt = str_replace("\n", ' ', $excerpt);
		
		// cut to last space
		if (mb_strlen($excerpt) > 10)
		{
			$pos = mb_strrpos($excerpt, ' ');
			if ($pos != -1)
			{
				$excerpt = mb_substr($excerpt, 0, $pos);
				$excerpt = rtrim($excerpt, '.,/-:');
				$excerpt .= '...';
			}
		}
		return $excerpt;
	}
	
	public function getSharingImage()
	{
		// try to find the first image of the content.
		// if not, return empty... (and the icon will take over!)
		
		$matches = array();
		if (preg_match('/<img[^>]+src="([^"]+)"/', $this->content, $matches))
		{
			return 
				Yii::app()->request->hostInfo . 
				Yii::app()->request->baseUrl . 
				$this->makeRelativeUrl($matches[1]);
		}
		
		return '';
	}
}


