CREATE TABLE IF NOT EXISTS `#__oauth2_server_config` (
`id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
`client_name` varchar(255) NOT NULL ,
`client_secret` varchar(255) NOT NULL, 
`client_id` varchar(255) NOT NULL, 
`authorized_uri` varchar(255) NOT NULL,
`client_count` varchar(255) NOT NULL,
`token_length` int(3) default 64,

PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

ALTER TABLE `#__users` ADD COLUMN `oauth2_randcode` varchar(255) DEFAULT 0;
ALTER TABLE `#__users` ADD COLUMN `oauth2_client_token` varchar(255) DEFAULT 0;
ALTER TABLE `#__users` ADD COLUMN `oauth2_time_stamp` int(11) DEFAULT 0;
