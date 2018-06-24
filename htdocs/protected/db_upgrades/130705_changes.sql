
-- re-adding the width in posts

ALTER TABLE `v4_post` 
	ADD `desired_width` INT( 1 ) NOT NULL AFTER `layout`;


