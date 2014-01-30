--
-- Change the former TEXT password field to a suitable MD5 
-- container, such as VARCHAR(32)

ALTER TABLE `auth_users` 
    CHANGE `password` `password` VARCHAR( 32 ) 
    CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
