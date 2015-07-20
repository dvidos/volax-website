
-- add "was_created" in post revisions.
ALTER TABLE  `v4_post_revisions` ADD  `was_created` BOOLEAN NOT NULL AFTER  `datetime`;
