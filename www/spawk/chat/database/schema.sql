-- Application "chat" is about web chatting between application users.
-- Behind "chat" application, lies the "chat" database were user data and
-- user relationships are stored.

-- (Re)create the "chat" database.

DROP DATABASE IF EXISTS `chat`
;

CREATE DATABASE `chat`
DEFAULT CHARSET = utf8
DEFAULT COLLATE = utf8_general_ci
;

-- Select "chat" database as the default database.

USE `chat`
;

-- Table "user" is the most significant table of the "chat" database.
-- Each row represents a registered user of the "chat" application.

CREATE TABLE `user` (
	`login`		VARCHAR(64) NOT NULL COMMENT 'Login name',
	`registration`	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Registration date',
	`name`		VARCHAR(128) NOT NULL COMMENT 'Full name',
	`email`		VARCHAR(128) NULL DEFAULT NULL COMMENT 'e-mail',
	-- 		user passwords are stored in SHA1
	`password`	CHARACTER(40) COLLATE utf8_bin NOT NULL COMMENT 'Password',

	PRIMARY KEY (
		`login`
	) USING BTREE,

	INDEX (
		`name`
	) USING BTREE,

	UNIQUE INDEX (
		`email`
	) USING BTREE
)

ENGINE = InnoDB
COMMENT = 'Person table'
;

-- Table "relation" holds the relationships between users.
-- There are three kinds of relationships between any two users:
-- The users are unrelated, friends or blocked.

CREATE TABLE `relation` (
	`user`		VARCHAR(64) NOT NULL COMMENT 'Login name',
	`related`	VARCHAR(64) NOT NULL COMMENT 'Related login name',
	`relationship`	ENUM (
		'FRIEND',
		'BLOCKED'
	) NOT NULL COMMENT 'Kind of relationship',
	`when`		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date of relation',

	PRIMARY KEY (
		`user`,
		`related`
	) USING BTREE,

	INDEX (
		`related`
	) USING BTREE
)

ENGINE = InnoDB
COMMENT = 'Relations table'
;

ALTER TABLE `relation` ADD FOREIGN KEY (
	`user`
) REFERENCES `user` (
	`login`
) ON UPDATE CASCADE ON DELETE CASCADE
;

ALTER TABLE `relation` ADD FOREIGN KEY (
	`related`
) REFERENCES `user` (
	`login`
) ON UPDATE CASCADE ON DELETE CASCADE
;

-- Create user for generic DQL/DML access to "chat" database

-- Warning!!!
-- Option "IF EXISTS" produces an error prior to MySQL version 5.7
-- In that case, drop the user manually and delete DROP command
-- from this file. Then run the (modified) SQL script again to
-- create the "chat" database and the user "chat".

DROP USER IF EXISTS 'chat'@'localhost'
;

CREATE USER 'chat'@'localhost' IDENTIFIED BY 'xxx'
;

GRANT SELECT, INSERT, UPDATE, DELETE ON `chat`.* TO 'chat'@'localhost'
;
