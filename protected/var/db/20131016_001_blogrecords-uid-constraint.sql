-- Adds a constraint to enforce referential integrity on blog records, as follows:
-- * articles cannot be orphaned
-- * blogRecords.uidRec is a FK for auth_users.uid
--

ALTER TABLE `blogRecords` ADD CONSTRAINT `blogRecords_uidfk_1` FOREIGN KEY ( `uidRec` ) REFERENCES `theblack_production`.`auth_users` (
`uid`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;
