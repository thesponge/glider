-- Add a column for the side content to be used in templates.
--

ALTER TABLE  `blogRecords` 
    ADD  `sideContent` LONGTEXT CHARACTER 
    SET utf8 COLLATE utf8_general_ci NULL AFTER  `content` ;

-- Recreate the view to include the new column
--

CREATE OR REPLACE VIEW `blogRecords_view` AS 
SELECT 
    `blogRecords`.`idRecord` AS `idRecord`,
    `blogRecords`.`idCat` AS `idCat`,
    `blogRecords`.`idTree` AS `idTree`,
    `blogRecords`.`uidRec` AS `uidRec`,
    `blogRecords`.`title` AS `title`,
    `blogRecords`.`content` AS `content`,
    `blogRecords`.`sideContent` AS `sideContent`,
    `blogRecords`.`lead` AS `lead`,
    `blogRecords`.`leadSec` AS `leadSec`,
    `blogRecords`.`country` AS `country`,
    `blogRecords`.`city` AS `city`,
    `blogRecords_stats`.`entryDate` AS `entryDate`,
    `blogRecords_stats`.`publishDate` AS `publishDate`,
    `blogRecords_stats`.`nrRates` AS `nrRates`,
    `blogRecords_stats`.`ratingTotal` AS `ratingTotal`,
    `blogRecords_stats`.`republish` AS `republish`,
    `blogRecords_settings`.`relatedStory` AS `relatedStory`,
    `blogRecords_settings`.`css` AS `css`,
    `blogRecords_settings`.`js` AS `js`,
    `blogRecords_settings`.`SEO` AS `SEO`,
    `blogRecord_folders`.`folderName` AS `folderName`,
    `blogRecord_folders`.`idFolder` AS `idFolder`,
    `blogRecord_formats`.`format` AS `format`,
    `blogRecord_formats`.`idFormat` AS `idFormat` 
FROM 
    ((((`blogRecords` join `blogRecords_stats` 
            on((`blogRecords`.`idRecord` = `blogRecords_stats`.`idRecord`))) 
        left join `blogRecords_settings` 
            on((`blogRecords`.`idRecord` = `blogRecords_settings`.`idRecord`))) 
        left join `blogRecord_folders` 
            on((`blogRecords_settings`.`idFolder` = `blogRecord_folders`.`idFolder`))) 
        left join `blogRecord_formats` 
            on((`blogRecords_settings`.`idFormat` = `blogRecord_formats`.`idFormat`)));
