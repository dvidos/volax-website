<?php

class AdvertisementWidget extends CWidget
{
	public $htmlOptions = array();
	
	public function init()
	{
		parent::init();
	}
	
	public function run()
	{
		// select a random advertisement that is_active,
		// increment its times_shown,
		// display image in <a href="this/advertisements/click?id=xxx">
		// SELECT * FROM table ORDER BY RAND() LIMIT 1
		
		
		$ad = Advertisement::model()->find(array(
			'order'=>'RAND()',
			'condition'=>'is_active = 1 AND from_time <= :t AND to_time >= :t',
			'params'=>array(
				':t'=>time(),
			),
		));
		
		if ($ad == null)
			return;
		
		// could trick user and present the URL on the status bar...
		// bloody Google! very nice trick!
		$image_tag = CHtml::image($ad->image_filename, $ad->image_title, array(
			'title'=>$ad->image_title,
		));
		$actual_url = Yii::App()->createUrl('/advertisement/click', array('id'=>$ad->id));
		$link_tag = CHtml::link($image_tag, $ad->target_url, array(
			'onMouseDown'=>'this.href="' . $actual_url . '";return true;',
		));
		$html = $link_tag;
		
		$ad->times_shown++;
		$ad->update(array('times_shown'));
		
		echo CHtml::tag('div', $this->htmlOptions, $html);
	}
}