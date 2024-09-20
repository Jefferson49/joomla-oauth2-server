DROP TABLE IF EXISTS `#__oauth2_server_config`;

ALTER TABLE `#__users` DROP COLUMN `oauth2_randcode`;
ALTER TABLE `#__users` DROP COLUMN `oauth2_client_token`;
ALTER TABLE `#__users` DROP COLUMN `oauth2_time_stamp`;