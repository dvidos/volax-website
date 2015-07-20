
-- add "was_created" in post revisions.
ALTER TABLE  `v4_post_revisions` ADD  `was_created` BOOLEAN NOT NULL AFTER  `datetime`;
ALTER TABLE  `v4_post_revisions` ADD  `comment` VARCHAR( 250 ) NOT NULL AFTER  `datetime`;
