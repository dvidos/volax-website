<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&amp;subset=latin,greek" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/print.css" media="print" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<div id="page">



<div id="top-menu">
	<div id="top-menu-left" style="float:left;">
		<?php echo CHtml::link('Αρχική', array('/')); ?> |
		<?php echo CHtml::link('Ποιοί Είμαστε', array('/site/page', 'view'=>'about')); ?>
	</div>
	<div id="top-menu-right" style="float:right;">
		<?php
			if (Yii::app()->user->isGuest)
			{
				echo CHtml::link('Γίνε Μέλος', array('/site/register'));
				echo ' | ';
				echo CHtml::link('Είσοδος', array('/site/login'));
			}
			else
			{
				if (Yii::app()->user->isAdmin)
					echo CHtml::link('Admin', array('/admin')) . ' | ';
				
				echo CHtml::link('Έξοδος', array('/site/logout'));
			}
		?>
	</div>
	<div style="clear:both;"></div>
</div><!-- /top-menu -->



<div id="banner">
	<div id="banner-left" style="float:left; width:25%;">
		<?php
			$img = CHtml::image(Yii::app()->baseUrl . '/assets/images/logo.jpg');
			echo CHtml::link($img, array('/'))
		?>
	</div>
	<div id="banner-right" style="float:right; width:75%;">
		<?php
			// echo CHtml::image(Yii::app()->baseUrl . '/assets/images/ad_sample.gif');
		?>
	</div>
	<div style="clear:both;"></div>
</div><!-- /banner -->




	
	
<div id="content">
	<!-- here content according to each page -->
	<?php echo $content; ?>
</div><!-- /content -->




	

<div id="footer">
	<div id="footer-col1" style="float:left; width:50%;">
		<b>volax.gr:</b>
		Ενημέρωση με email<br />
		<input><br />
		Λοιπό functionality<br />
	</div>
	<div id="footer-col2" style="float:left; width:50%;">
		<a href="#" onClick="$('#footer-categories-list').slideToggle();"><b>Στήλες</b></a>
		<div id="footer-categories-list" style="display: none;">
			<?php
				echo '<table width="100%"><tr><td>';
				
				$cat = Category::model()->findByPk(3);
				echo '<b>' . $cat->title . '</b><br />';
				foreach ($cat->subcategories as $category)
				{
					echo CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title)) . '<br />';
				}
				
				echo '</td><td>';

				$cat = Category::model()->findByPk(12);
				echo '<b>' . $cat->title . '</b><br />';
				foreach ($cat->subcategories as $category)
				{
					echo CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title)) . '<br />';
				}

				echo '</td><td>';
				
				$cat = Category::model()->findByPk(17);
				echo '<b>' . $cat->title . '</b><br />';
				foreach ($cat->subcategories as $category)
				{
					echo CHtml::link($category->title, array('/category/view', 'id'=>$category->id, 'title'=>$category->title)) . '<br />';
				}
				
				echo '</td></tr></table>';
			?>
		</div>
		
	</div>
	<div style="clear:both;"></div>
</div><!-- /footer -->


</div><!-- /page -->
</body>
</html>