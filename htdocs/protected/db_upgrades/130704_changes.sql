
-- add layout to posts. wide, 1/2, 1/3, 2/3.

ALTER TABLE `v4_post` 
	ADD `layout` INT( 11 ) NOT NULL AFTER `image2_filename` ;
	
	
ALTER TABLE `v4_post` 
	DROP `render_narrow` ;

ALTER TABLE `v4_post` 
	ADD `allow_comments` INT( 1 ) NOT NULL AFTER `in_home_page` ;
	
