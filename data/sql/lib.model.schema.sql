
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- sf_guard_user_profile
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_user_profile`;


CREATE TABLE `sf_guard_user_profile`
(
	`user_id` INTEGER  NOT NULL,
	`firstname` VARCHAR(100),
	`lastname` VARCHAR(100),
	`email` VARCHAR(100),
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`),
	INDEX `sf_guard_user_profile_FI_1` (`user_id`),
	CONSTRAINT `sf_guard_user_profile_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tokens
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tokens`;


CREATE TABLE `tokens`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER  NOT NULL,
	`token` VARCHAR(255)  NOT NULL,
	`created_at` DATETIME,
	`active` TINYINT default 1,
	PRIMARY KEY (`id`),
	INDEX `tokens_FI_1` (`user_id`),
	CONSTRAINT `tokens_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- app_tokens
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `app_tokens`;


CREATE TABLE `app_tokens`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`token` VARCHAR(255)  NOT NULL,
	`created_at` DATETIME,
	`name` VARCHAR(255)  NOT NULL,
	`company` VARCHAR(255)  NOT NULL,
	`os` VARCHAR(255),
	`active` TINYINT default 1,
	PRIMARY KEY (`id`),
	UNIQUE KEY `app_tokens_U_1` (`token`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- events_type
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `events_type`;


CREATE TABLE `events_type`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- events
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `events`;


CREATE TABLE `events`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`place` VARCHAR(255)  NOT NULL,
	`date` DATETIME,
	`key` VARCHAR(255)  NOT NULL,
	`event_type_id` INTEGER,
	`created_at` DATETIME,
	`admin_id` INTEGER  NOT NULL,
	`active` TINYINT default 1,
	PRIMARY KEY (`id`),
	UNIQUE KEY `events_U_1` (`key`),
	INDEX `events_FI_1` (`event_type_id`),
	CONSTRAINT `events_FK_1`
		FOREIGN KEY (`event_type_id`)
		REFERENCES `events_type` (`id`),
	INDEX `events_FI_2` (`admin_id`),
	CONSTRAINT `events_FK_2`
		FOREIGN KEY (`admin_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- programs
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `programs`;


CREATE TABLE `programs`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`event_id` INTEGER,
	`time` DATETIME  NOT NULL,
	`act` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `programs_FI_1` (`event_id`),
	CONSTRAINT `programs_FK_1`
		FOREIGN KEY (`event_id`)
		REFERENCES `events` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- users_event
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `users_event`;


CREATE TABLE `users_event`
(
	`user_id` INTEGER  NOT NULL,
	`event_id` INTEGER  NOT NULL,
	`joined_at` DATETIME,
	`active` TINYINT default 0,
	PRIMARY KEY (`user_id`,`event_id`),
	CONSTRAINT `users_event_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE,
	INDEX `users_event_FI_2` (`event_id`),
	CONSTRAINT `users_event_FK_2`
		FOREIGN KEY (`event_id`)
		REFERENCES `events` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- photos
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `photos`;


CREATE TABLE `photos`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER  NOT NULL,
	`event_id` INTEGER  NOT NULL,
	`uploaded_at` DATETIME,
	`title` VARCHAR(255),
	`filename` VARCHAR(255)  NOT NULL,
	`path` VARCHAR(255)  NOT NULL,
	`visible` TINYINT default 1,
	`deleted` TINYINT default 0,
	PRIMARY KEY (`id`),
	INDEX `photos_FI_1` (`user_id`),
	CONSTRAINT `photos_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE,
	INDEX `photos_FI_2` (`event_id`),
	CONSTRAINT `photos_FK_2`
		FOREIGN KEY (`event_id`)
		REFERENCES `events` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
