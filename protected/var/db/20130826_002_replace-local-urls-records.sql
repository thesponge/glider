--
-- Replace all local URL addresses in articles with paths relative to domain

UPDATE blogRecords 
    SET content = REPLACE(content, 'http://dev.blacksea.eu', '') 
    WHERE INSTR(content, 'http://dev.blacksea.eu') > 0;


UPDATE blogRecords 
    SET content = REPLACE(content, 'http://local.blacksea.eu', '') 
    WHERE INSTR(content, 'http://local.blacksea.eu') > 0;


UPDATE blogRecords 
    SET content = REPLACE(content, 'http://2013.theblacksea.eu', '') 
    WHERE INSTR(content, 'http://2013.theblacksea.eu') > 0;

