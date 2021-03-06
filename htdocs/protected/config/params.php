<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// version, displayed on Admin.
	'version'=>0.4,
	
	// this is displayed in the header section
	'title'=>'Βωλάξ',
	
	// this is used in error pages
	'adminEmail'=>'info@volax.gr',
	
	// who receives email from the contact form.
	'contactFormReceivers'=>array(
		'dvidos@gmail.com',
		//'jimel.vidos@gmail.com',
		//'nikaliamoutos@gmail.com',
	),
	
	// who receives email from new comments
	'newCommentSubscribers'=>array(
		'dvidos@gmail.com',
		//'jimel.vidos@gmail.com',
	),
	
	// who receives email from saving a post
	'postSavedSubscribers'=>array(
		'dvidos@gmail.com',
		//'jimel.vidos@gmail.com',
		//'nikaliamoutos@gmail.com',
	),
	
	'newUserSubscribers'=>array(
		'dvidos@gmail.com',
		//'jimel.vidos@gmail.com',
		//'nikaliamoutos@gmail.com',
	),
	
	// the markup to use for "read more..."
	'postsMoreIndicator'=>"[more]",
	
	// the text to use in the link for "read more..."
	'postsMoreLinkText'=>'συνέχεια...',
	
	// default category for new posts
	'defaultPostCategoryId'=>124,
	
	// number of posts displayed per page
	'postsPerPage'=>10,
	
	// pager params for where listings are paged.
	'defaultPagerParams'=>array(
		'class'=>'CLinkPager',
		//'header'=>'Σελίδα: &nbsp; ',
		'header'=>'',
		//'firstPageLabel'=>'Πρώτη',
		'prevPageLabel'=>'<',
		'nextPageLabel'=>'>',	
		//'lastPageLabel'=>'Τελευταία',
		'maxButtonCount'=>5,
	),
	
	
	// maximum number of comments that can be displayed in recent comments portlet
	'recentCommentCount'=>10,
	
	// maximum number of tags that can be displayed in tag cloud portlet
	'tagCloudCount'=>20,
	
	// whether to show the "Add Comment" Form
	'allowPostingNewComments'=>true,
	
	// whether post comments need to be approved before published
	'commentNeedApproval'=>false,
	
	// the copyright information displayed in the footer section
	'copyrightInfo'=>'Copyright &copy; 2008-'.date('Y').' Volax-Team',
);
