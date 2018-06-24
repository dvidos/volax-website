<?php

class SocialShareButton extends CWidget
{
	public $url = null;
	public $title = null;
	public $networks = array('facebook','googleplus','linkedin','twitter');


	public function init()
	{
	}


	public function run()
	{
		self::renderSocial();
	}
	
	/**
	 * Render social extension
	 *
	 * @return nothing
	 */
	private function renderSocial()
	{
		/*
		simple sharing samples
		-----------------------------
		<a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsimplesharingbuttons.com%2F" target="_blank">fb</a>
		<a href="https://twitter.com/intent/tweet?source=http%3A%2F%2Fsimplesharingbuttons.com%2F&text=Simple%20Sharing%20Buttons%20Generator: http%3A%2F%2Fsimplesharingbuttons.com%2F&via=fourtonfish" target="_blank" title="Tweet">tw</a>
		<a href="https://plus.google.com/share?url=http%3A%2F%2Fsimplesharingbuttons.com%2F" target="_blank" title="Share on Google+">g+</a>
		<a href="http://www.tumblr.com/share?v=3&u=http%3A%2F%2Fsimplesharingbuttons.com%2F&t=Simple%20Sharing%20Buttons%20Generator&s=" target="_blank" title="Post to Tumblr">tm</a>
		<a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fsimplesharingbuttons.com%2F&media=http://simplesharingbuttons.com/images/preview.png&description=Share%20to%20Facebook%2C%20Twitter%2C%20Google%2B%20and%20other%20social%20networks%20using%20simple%20HTML%20buttons" target="_blank" title="Pin it">pi</a>
		<a href="http://www.reddit.com/submit?url=http%3A%2F%2Fsimplesharingbuttons.com%2F&title=Simple%20Sharing%20Buttons%20Generator" target="_blank" title="Submit to Reddit">re</a>
		<a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fsimplesharingbuttons.com%2F&title=Simple%20Sharing%20Buttons%20Generator&summary=Share%20to%20Facebook%2C%20Twitter%2C%20Google%2B%20and%20other%20social%20networks%20using%20simple%20HTML%20buttons&source=http%3A%2F%2Fsimplesharingbuttons.com%2F" target="_blank" title="Share on LinkedIn">in</a>
		<a href="http://wordpress.com/press-this.php?u=http%3A%2F%2Fsimplesharingbuttons.com%2F&t=Simple%20Sharing%20Buttons%20Generator&s=Share%20to%20Facebook%2C%20Twitter%2C%20Google%2B%20and%20other%20social%20networks%20using%20simple%20HTML%20buttons&i=http://simplesharingbuttons.com/images/preview.png" target="_blank" title="Publish on WordPress">wp</a>
		*/
		
		$rendered = '';
		foreach($this->networks as $network)
			$rendered .= $this->render($network, array(
				'url'=>$this->url,
				'title'=>$this->title,
			), true)."\r\n";
			
		$this->render('share', array('rendered'=>$rendered));
	}
}

?>
