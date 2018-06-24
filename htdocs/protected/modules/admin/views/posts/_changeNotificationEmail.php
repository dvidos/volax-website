<?php 
	// have $post, $revision (if updated), $is_new, $is_deleted

	$did = $is_new ? 'δημιούργησε' : ($is_deleted ? 'διέγραψε' : 'διόρθωσε');
	echo '<p>Ο χρήστης <b>' . Yii::app()->user->name . '</b> '. $did .' την παρακάτω ανάρτηση</p>'."\r\n";
	
	$link = CHtml::link(CHtml::encode($post->title), $post->getUrl(true), array('style'=>'text-decoration: none; color:#09f;'));
	echo CHtml::tag('h1', array(), $link)."\r\n";

	echo '<div style="border: 1px solid #aaa; padding: 1em; margin: 1em 0;">'."\r\n";
	if ($is_new || $is_deleted || $revision == null)
	{
		echo '<p><b>Υπέρτιτλος</b><br />' . $post->masthead . '<p>'."\r\n";
		echo '<p><b>Περιεχόμενο</b></p>'."\r\n" . $post->content ."\r\n";
		echo '<p><b>Κατηγορία</b><br />' . Category::tryGetTitle($post->category_id) . '</p>'."\r\n";
		echo '<p><b>Tags</b><br />' . $post->tags . '<p>'."\r\n";
	}
	else
	{
		if (strcmp($revision->title, $post->title) != 0)
			echo '<p><b>Τίτλος</b><br />' . Yii::app()->textDiff->compare($revision->title, $post->title) . '<p>'."\r\n";
		if (strcmp($revision->masthead, $post->masthead) != 0)
			echo '<p><b>Υπέρτιτλος</b><br />' . Yii::app()->textDiff->compare($revision->masthead, $post->masthead) . '<p>'."\r\n";
		if (strcmp($revision->content, $post->content) != 0)
			echo '<p><b>Περιεχόμενο</b><br />' . Yii::app()->textDiff->compare($revision->content, $post->content) . '<p>'."\r\n";
		if ($revision->category != $post->category)
			echo '<p><b>Κατηγορία</b><br />' . Category::tryGetTitle($revision->category_id) . ' --&gt; ' . Category::tryGetTitle($post->category_id).'</p>'."\r\n";
		if (strcmp($revision->tags, $post->title) != 0)
			echo '<p><b>Tags</b><br />' . Yii::app()->textDiff->compare($revision->tags, $post->tags) . '<p>'."\r\n";
	}
	echo '</div>'."\r\n";
	
	echo '<p>'."\r\n";
	if (!$is_deleted)
		echo 'Η ανάρτηση είναι '.$post->friendlyStatus.'<br />'."\r\n";
	echo 'Η ημερομηνία και ώρα του server είναι '.date('d/m/Y, H:i:s') . '<br />'."\r\n";
	if (!$is_deleted)
		echo 'Αν είστε διαχειριστής μπορείτε να διορθώσετε την ανάρτηση ' . CHtml::link('εδώ', Yii::app()->createAbsoluteUrl('/admin/posts/update', array('id'=>$post->id)))."\r\n";
	echo '</p>'."\r\n";
	
	
	
	
