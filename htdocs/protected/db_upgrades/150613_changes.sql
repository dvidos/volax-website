

CREATE TABLE `v4_post_revisions` (  
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`post_id` int(11) NOT NULL,
	`revision_no` int(11) NOT NULL,
	`user_id` int(11) NOT NULL,
	`datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`was_deleted` tinyint(1) NOT NULL,
	`title` varchar(128) NOT NULL,
	`prologue` text NOT NULL,
	`masthead` text NOT NULL,
	`content` longtext NOT NULL,
	`category_id` int(11) NOT NULL,
	`tags` text NOT NULL, 
	PRIMARY KEY (`id`),  
	KEY `post_id` (`post_id`), 
	KEY `user_id` (`user_id`), 
	KEY `datetime` (`datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

