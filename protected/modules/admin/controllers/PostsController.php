<?php

class PostsController extends Controller
{
	public function actionIndex()
	{
		$model=new Post('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$model = new Post;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes = $_POST['Post'];
			if($model->save())
			{
				$model->notifyEmailSubscribers(true);
				Yii::app()->user->setFlash('postSaved','Η ανάρτηση αποθηκεύτηκε: ' . CHtml::encode($model->title));
				if (isset($_POST['saveAndStay']))
					$this->redirect(array('update', 'id'=>$model->id));
				else
					$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes = $_POST['Post'];
			if($model->save())
			{
				$model->notifyEmailSubscribers(false);
				Yii::app()->user->setFlash('postSaved','Η ανάρτηση αποθηκεύτηκε: ' . CHtml::encode($model->title));
				if (isset($_POST['saveAndStay']))
					$this->redirect(array('update', 'id'=>$id));
				else
					$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin gridview), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function loadModel($id)
	{
		$model=Post::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Suggests tags based on the current user input.
	 * This is called via AJAX when the user is entering the tags input.
	 */
	public function actionSuggestTags()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
	}

	public function actionLinks()
	{
		$ids = Post::model()->findAll(array(
			'select'=>'id',
			'order'=>'update_time DESC',
		));
		
		$data = array();
		foreach ($ids as $id)
		{
			$post = Post::model()->findByPk($id['id']);
			$links = $post->getContentLinks();
			if (empty($links))
				continue;
			$data[] = array(
				'id'=>$post->id,
				'title'=>$post->title,
				'links'=>$links,
			);
		}
		
		$this->render('links', array(
			'data'=>$data,
		));
	}

	public function actionImages()
	{
		$ids = Post::model()->findAll(array(
			'select'=>'id',
			'order'=>'create_time DESC',
		));
		
		$data = array();
		foreach ($ids as $id)
		{
			$post = Post::model()->findByPk($id['id']);
			$images = $post->getContentImages();
			if (empty($images))
				continue;
			$data[] = array(
				'id'=>$post->id,
				'title'=>$post->title,
				'images'=>$images,
			);
		}
		
		$this->render('images', array(
			'data'=>$data,
		));
	}
	
	
	public function actionIntegrity()
	{
		$categories = Category::model()->findAll(array(
			'select'=>'id, parent_id, status, title',
		));
		
		$posts = Post::model()->findAll(array(
			'select'=>'id, category_id, author_id, status, title',
		));
		
		$authors = User::model()->findAll(array(
			'select'=>'id, username',
		));
		
		
		$categories_ids = array();
		$parent_categories_ids = array();
		foreach ($categories as $category)
		{
			$categories_ids[] = $category->id;
			$parent_categories_ids[$category->id] = $category->parent_id;
		}
		$leaves_categories_ids = array();
		$branches_categories_ids = array();
		foreach ($categories as $category)
		{
			// see if there is a child pointing to this category.
			$has_children = in_array($category->id, $parent_categories_ids);
			if ($has_children)
				$branches_categories_ids[] = $category->id;
			else
				$leaves_categories_ids[] = $category->id;
		}
		
		$author_ids = array();
		foreach ($authors as $author)
			$author_ids[] = $author->id;
			

		$errors = array();
		
		foreach ($posts as $post)
		{
			$messages = array();
			
			if ($post->category_id == 0)
				$messages[] = 'Δεν δηλώθηκε κατηγορία';
			
			else if (!in_array($post->category_id, $categories_ids))
				$messages[] = 'Η κατηγορία δεν βρέθηκε';
			
			else if (in_array($post->category_id, $branches_categories_ids))
				$messages[] = 'Η κατηγορία που ανήκει η ανάρτηση έχει υποκατηγορίες';;
			
			
			
			if ($post->author_id == 0)
				$messages[] = 'Δεν δηλώθηκε συγγραφέας';
			
			else if (!in_array($post->author_id, $author_ids))
				$messages[] = 'Ο συγγραφέας δεν βρέθηκε';
			
			
			if (count($messages) > 0)
			{
				$errors[] = array(
					'type'=>'Ανάρτηση',
					'status'=> ($post->status == 2 ? 'Published' : $post->status == 1 ? 'Draft' : 'Archived'),
					'title'=>$post->title,
					'url'=>array('/admin/posts/update', 'id'=>$post->id),
					'error'=>implode('<br>', $messages),
				);
			}
		}
		
		foreach ($categories as $category)
		{
			$messages = array();
			
			if ($category->parent_id == 0)
			{
				// nothing! allowed!
			}
			else if (!in_array($category->parent_id, $categories_ids))
				$messages[] = 'Η πατρική κατηγορία δεν βρέθηκε';
			
			
			if (count($messages) > 0)
			{
				$errors[] = array(
					'type'=>'Κατηγορία',
					'status'=> ($category->status == 2 ? 'Published' : 'Draft'),
					'title'=>$category->title,
					'url'=>array('/admin/categories/update', 'id'=>$category->id),
					'error'=>implode('<br>', $messages),
				);
			}
		}
		
		$this->render('integrity', array(
			'errors'=>$errors,
		));
	}
	
	public function actionLanguages()
	{
		$errors = array();
		$greek = 'ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρστυφχψωςάέύίόήώΆΈΎΊΌΉΏΐΰ';
		$regexp = '/([a-zA-Z]['.$greek.']|['.$greek.'][a-zA-Z])/uS';
		
		$posts = Post::model()->findAll(array(
			'select'=>'id, status, title',
		));
		foreach ($posts as $post)
		{
			if (preg_match($regexp, $post->title))
				$errors[] = array('type'=>'Post', 'title'=>$post->title, 'url'=>array('/admin/posts/update', 'id'=>$post->id));
		}
		$posts = null;
		
			
		$categories = Category::model()->findAll(array(
			'select'=>'id, status, title',
		));
		foreach ($categories as $category)
		{
			if (preg_match($regexp, $category->title))
				$errors[] = array('type'=>'Category', 'title'=>$category->title, 'url'=>array('/admin/categories/update', 'id'=>$category->id));
		}
		$categories = null;


		
		$tags = Tag::model()->findAll(array(
			'select'=>'id, name',
		));
		foreach ($tags as $tag)
		{
			if (preg_match($regexp, $tag->name))
				$errors[] = array('type'=>'Tag', 'title'=>$tag->name, 'url'=>array('/admin/posts', 'Post[tags]'=>$tag->name));
		}
		$tags = null;


		
		$this->render('languages', array(
			'errors'=>$errors,
		));
	}
	
	
	public function actionSearchContent()
	{
		$key = array_key_exists('key', $_REQUEST) ? $_REQUEST['key'] : '';
		$regex = array_key_exists('regex', $_REQUEST) ? $_REQUEST['regex'] : '';
		
		$results = array();
		if (!empty($key))
			$results = Post::model()->searchPostsForContent($key, $regex);
			
		$this->render('searchContent', array(
			'key'=>$key,
			'regex'=>$regex,
			'results'=>$results,
		));
	}
	
	
}
