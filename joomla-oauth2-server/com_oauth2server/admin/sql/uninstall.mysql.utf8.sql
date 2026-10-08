DROP TABLE IF EXISTS `#__oauth2_server_config`;

ALTER TABLE `#__users` DROP COLUMN `oauth2_randcode`;
ALTER TABLE `#__users` DROP COLUMN `oauth2_client_token`;
ALTER TABLE `#__users` DROP COLUMN `oauth2_time_stamp`;
ALTER TABLE `#__users` DROP COLUMN `randcode_client_id`;
ALTER TABLE `#__users` DROP COLUMN `randcode_time_stamp`;
