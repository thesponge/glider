-- Remove the not null restriction on user token, as it is 
-- stored AFTER the initial user details insert query. Basically, in a series
-- of three queries, only the second will add a token to the user.
--

ALTER TABLE `auth_users` 
    CHANGE `token` `token` VARCHAR( 32 ) 
    CHARACTER SET utf8 
    COLLATE utf8_general_ci 
    NULL DEFAULT NULL ;
