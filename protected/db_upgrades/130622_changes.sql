
-- first, we change the subtitles into prologue and masthead
-- then we add image2_filename (for small images) and layout.

ALTER TABLE `v4_category` 
	CHANGE `subtitle` 
		`prologue` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
		
ALTER TABLE `v4_category` 
	ADD `masthead` TEXT NOT NULL AFTER `prologue` ;
	
ALTER TABLE `v4_category` 
	ADD `image2_filename` VARCHAR( 128 ) NOT NULL AFTER `image_filename` ,
	ADD `layout` INT NOT NULL AFTER `image2_filename` ;



-- then the posts.

ALTER TABLE `v4_post` 
	CHANGE `subtitle` 
		`prologue` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;

ALTER TABLE `v4_post` 
	ADD `masthead` TEXT NOT NULL AFTER `prologue` ;

ALTER TABLE `v4_post` 
	ADD `render_narrow` INT( 1 ) NOT NULL AFTER `status` ;

ALTER TABLE `v4_post` 
	ADD `image2_filename` VARCHAR( 128 ) NOT NULL AFTER `image_filename` ;

ALTER TABLE `v4_post` 
	ADD `in_home_page` INT( 1 ) NOT NULL AFTER `status` ;








