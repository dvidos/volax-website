<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// version, displayed on Admin.
	'version'=>0.1,
	
	// this is displayed in the header section
	'title'=>'My Yii Blog',
	
	// this is used in error pages
	'adminEmail'=>'dvidos@gmail.com',
	
	// who receives email from the contact form.
	'contactFormReceivers'=>array(
		'dvidos@gmail.com',
		// 'jimel.vidos@gmail.com',
	),
	
	// who receives email from new comments
	'newCommentSubscribers'=>array(
		'dvidos@gmail.com',
		//'jimel.vidos@gmail.com',
	),
	
	// the markup to use for "read more..."
	'postsMoreIndicator'=>"[more]",
	
	// the text to use in the link for "read more..."
	'postsMoreLinkText'=>'συνέχεια...',
	
	
	// number of posts displayed per page
	'postsPerPage'=>10,
	
	// maximum number of comments that can be displayed in recent comments portlet
	'recentCommentCount'=>10,
	
	// maximum number of tags that can be displayed in tag cloud portlet
	'tagCloudCount'=>20,
	
	// whether post comments need to be approved before published
	'commentNeedApproval'=>true,
	
	// the copyright information displayed in the footer section
	'copyrightInfo'=>'Copyright &copy; 2009 by My Company.',
);
