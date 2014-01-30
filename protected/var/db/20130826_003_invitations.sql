--
-- Create the table where we'll store membership invitations

CREATE TABLE auth_invitations (
    email VARCHAR(255) NOT NULL UNIQUE,
    cid   INT(3)       NOT NULL DEFAULT "3",
    token VARCHAR(32)  NOT NULL UNIQUE
) ENGINE=InnoDB CHARSET=UTF8;

ALTER TABLE `auth_invitations` ADD PRIMARY KEY(`email`);
