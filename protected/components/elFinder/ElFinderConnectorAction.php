<?php

class ElFinderConnectorAction extends CAction
{
    /**
     * @var array
     */
    public $settings = array();

    public function run()
    {
		// Yii::log('ElFinder connector action running, settings are:\r\n' . var_export($this->settings, true), 'debug');
		
        require_once(dirname(__FILE__) . '/php/elFinder.class.php');
        $fm = new elFinder($this->settings);
        $fm->run();

    }
}
