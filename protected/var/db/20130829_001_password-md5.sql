--
-- Replace all passwords with md5 versions, if not already

UPDATE `auth_users` 
    SET `password` = md5( PASSWORD ) 
    WHERE length( PASSWORD ) < 30;
