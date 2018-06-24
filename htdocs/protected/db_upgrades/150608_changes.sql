
-- these changes were made by hand: 
-- i changed the "content" field of tables posts, pages and categories,
-- from TEXT, to LONGTEXT.

ALTER TABLE  `v4_pages` CHANGE  `content`  `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE  `v4_post` CHANGE  `content`  `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE  `v4_categories` CHANGE  `content`  `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;


