<?php

class TagsController extends Controller
{
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Tag', array(
			'criteria'=>array(
				'order'=>'t.name',
			),
			'pagination'=>array(
				'pageSize'=>200,
			),
		));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionRename()
	{
		$model = new RenameTagsFormModel('search');
		if(isset($_POST['RenameTagsFormModel']))
		{
			if (isset($_POST['search']))
			{
				$model->attributes = $_POST['RenameTagsFormModel'];
				if ($model->validate())
				{
					$model->searchPosts();
					$model->scenario = 'rename';
				}
			}
			else if (isset($_POST['rename']))
			{
				$model->scenario = 'rename';
				$model->attributes = $_POST['RenameTagsFormModel'];
				
				// search posts in case user validates 'search' rules, but not 'rename' rules
				$model->searchPosts();
				
				if ($model->validate())
				{
					$model->renamePosts();
					Yii::app()->user->setFlash('success',
						'Η μετονομασία tag από ' . CHtml::encode($model->initialTag) . 
						' σε ' . CHtml::encode($model->targetTag) . 
						' ολοκληρώθηκε σε ' . count($model->posts) . ' αναρτήσεις');
					
					// reset model for new search
					$model = new RenameTagsFormModel();
				}
			}
		}
		
		$this->render('rename',array(
			'model'=>$model,
		));
	}
	
	public function actionSuggestTags()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
	}
}
