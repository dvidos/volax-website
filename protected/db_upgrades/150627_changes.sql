
-- first, add a new field for pre-processed content in posts
ALTER TABLE  `v4_post` ADD  `processed_content` LONGTEXT NOT NULL AFTER  `content`;


-- then, add a discussion field.
ALTER TABLE  `v4_post` 
	ADD  `sort_time` INT NOT NULL AFTER  `update_time` ,
	ADD  `discussion` LONGTEXT NOT NULL AFTER  `sort_time`;


	

-- please take table backup before blindly running these commands!!!!
	
-- fill new fields
UPDATE v4_post SET sort_time = create_time;

-- under test
UPDATE v4_post SET processed_content = 
	CONCAT( 
		IF( LENGTH( image_filename ) =0,  '', CONCAT(  '<p><img src="', image_filename,  '" alt="" /></p>\r\n\r\n' ) ) , 
		IF( LENGTH( prologue ) =0,  '', CONCAT(  '<p class="prologue x-style-alt-color">', prologue,  '</p>\r\n\r\n' ) ) , 
		content 
	);

UPDATE v4_post SET content = processed_content;
UPDATE v4_post SET prologue =  '';
UPDATE v4_post SET image_filename =  '';



-- changing categories as well.
ALTER TABLE  `v4_category` 
	DROP  `prologue` ,
	DROP  `masthead` ,
	DROP  `image_filename` ;
