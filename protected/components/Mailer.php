<?php

class Mailer extends CApplicationComponent
{
	/**
	 * The email address the mail is sent from
	 */
	public $from = '';
	
	/**
	 * An email address to bcc each email, for archiving purposes.
	 */
	public $bcc = '';
	
	/** 
	 * The disclaimer added to each message
	 */
	public $disclaimer = '';
	
	/**
	 * Whether to prefer sending html mail
	 */
	public $htmlFormat = true;
	
	public function init()
	{
	}
	
	public function send($to, $title, $body, $skip_disclaimer = false)
	{
		$body = $this->addDisclaimer($body, $skip_disclaimer);
		$headers = $this->prepareHeaders();
		
		Yii::log('Mailer: Sending mail to "' . $to . '", titled "' . $title . '"', 'info', 'Mailer');
		if (!mail($to, $title, $body, implode("\r\n", $headers) . "\r\n"))
		{
			Yii::log('Mailer: mail() function returned false, sending failed', 'error', 'Mailer');
			throw new CHttpException(500, 'Email functionality failed');
		}
	}
	
	protected function addDisclaimer($body, $skip_disclaimer)
	{
		if ($skip_disclaimer)
			return $body;

		$disc = $this->htmlFormat ? str_replace("\n", "<br />\n", $this->disclaimer) : $this->disclaimer;
		
		if (strpos($body, '{disclaimer}') !== false)
		{
			$body = str_replace('{disclaimer}', $disc, $body);
		}
		else
		{
			$sep = $this->htmlFormat ? "<br />\r\n" : "\r\n";
			$body = $body . $sep . $sep . $sep . $disc . $sep;
		}
		
		return $body;
	}
	
	protected function prepareHeaders()
	{
		$headers = array();
		
		if ($this->htmlFormat)
		{
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=utf-8';
		}
		
		$headers[] = 'From: ' . $this->from;
		
		if (is_array($this->bcc))
			$headers[] = 'Bcc: ' . implode(',', $this->bcc);
		else
			$headers[] = 'Bcc: ' . $this->bcc;
		
		return $headers;
	}
}


?>