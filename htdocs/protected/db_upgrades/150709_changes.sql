

ALTER TABLE  `v4_user` 
	ADD  `registered_at` DATETIME NOT NULL ,
	ADD  `last_login_at` DATETIME NOT NULL ,
	ADD  `email_confirmed` BOOLEAN NOT NULL ,
	ADD  `want_newsletter` BOOLEAN NOT NULL ,
	ADD  `is_banned` BOOLEAN NOT NULL;



