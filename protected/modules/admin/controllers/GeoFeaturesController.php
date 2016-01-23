<?php

class GeoFeaturesController extends Controller
{
	public function actionIndex()
	{
		$model=new GeoFeature('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GeoFeature']))
			$model->attributes=$_GET['GeoFeature'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$model = new GeoFeature();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GeoFeature']))
		{
			$model->attributes = $_POST['GeoFeature'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success','Η εγγραφή αποθηκεύτηκε: ' . CHtml::encode($model->title));
				$this->redirect(array('update', 'id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST['GeoFeature']))
		{
			$model->attributes = $_POST['GeoFeature'];
			if($model->save())
				Yii::app()->user->setFlash('success','Η εγγραφή αποθηκεύτηκε: ' . CHtml::encode($model->title));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionUpload()
	{
		$file = CUploadedFile::getInstanceByName('waypoints_file');
		if ($file == null || $file->hasError)
		{
			Yii::app()->user->setFlash('error','Δεν δόθηκε αρχείο ή παρουσιάστηκε σφάλμα κατά την μεταφορά του');
			$this->redirect(array('index'));
		}
		try
		{
			$imported = Yii::app()->geoFileConverter->importFile($file);
		}
		catch (Exception $e)
		{
			Yii::app()->user->setFlash('error','Σφάλμα κατά την εισαγωγή του αρχείου: ' . $e->getMessage());
			$this->redirect(array('index'));
		}
		
		$this->render('importSelection',array(
			'imported'=>$imported,
		));
	}
	
	/**
	 * Called after parsing of an uploaded file, when user selects what to import.
	 * See views/GeoFeatures/importSelection.
	 */
	public function actionImport()
	{
		$model = new GeoFeature();
		$model->feature_type = $_POST['type'];
		$model->title = $_POST['name'];
		$model->description = $_POST['desc'];
		
		if ($model->feature_type == 'point')
		{
			$model->geo_lat = $_POST['lat'];
			$model->geo_long = $_POST['lon'];
		}
		else
		{
			$model->geo_lat = 0;
			$model->geo_long = 0;
		}
			
		if (!$model->insert())
		{
			Yii::app()->user->setFlash('error', 'Σφάλμα κατά την εισαγωγή της εγγραφής: ' . var_export($model->errors, true));
			$this->redirect(array('index'));
		}
		
		// now that we have feature_id, we shall save possible waypoints...
		if ($model->feature_type == 'route')
		{
			// we shall be adding them using one big INSERT,
			// for may ones take time (30 seconds for 500 waypoints)
			// Yii 1.14 has the CDbCommandBuilder->createMultipleInsertCommand() function. this version does not.
			$points = explode('|', $_POST['points']);
			if (count($points) > 0)
			{
				$no = 1;
				$boundVars = array();
				foreach ($points as $point)
				{
					$boundVars[] = '(:f'.$no.', :n'.$no.', :la'.$no.', :lo'.$no.')';
					$no++;
				}
				
				$sql = 
					'INSERT INTO ' . GeoWaypoint::model()->tableSchema->rawName . ' (feature_id, waypoint_no, geo_lat, geo_long) '.
					'VALUES ' . implode(', ', $boundVars);
				
				$no = 1;
				$cmd = Yii::app()->db->createCommand($sql);
				foreach ($points as $point)
				{
					$coords = explode(',', $point);
					$cmd->bindValue(':f'.$no, $model->id);
					$cmd->bindValue(':n'.$no, $no);
					$cmd->bindValue(':la'.$no, $coords[0]);
					$cmd->bindValue(':lo'.$no, $coords[1]);
					$no++;
				}
				
				$cmd->execute();
			}
		}

		Yii::app()->user->setFlash('success','Η εγγραφή αποθηκεύτηκε: ' . CHtml::encode($model->title));
		$this->redirect(array('update', 'id'=>$model->id));
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
		$model=GeoFeature::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='geofeature-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
