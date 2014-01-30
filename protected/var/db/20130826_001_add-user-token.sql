--
-- Add a column to store each user's unique security token

ALTER TABLE `auth_users`
    ADD COLUMN `token` VARCHAR(32)
        AFTER `password`, 
    ADD UNIQUE INDEX `token_UNIQUE` (`token` ASC);


--
-- Populate existing rows with random tokens, 
-- so I can insert a NOT NULL condition afterwards

UPDATE `auth_users`
    SET `token` = MD5(RAND())
    WHERE 1;


--
-- Now add a condition for the token to be NOT NULL

ALTER TABLE `auth_users` 
    CHANGE COLUMN `token` 
        `token` VARCHAR(32) NOT NULL;

