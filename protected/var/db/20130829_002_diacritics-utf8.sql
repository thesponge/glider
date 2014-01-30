ALTER TABLE blogRecords CONVERT TO CHARACTER SET utf8;

-- content 1 
UPDATE blogRecords SET content = REPLACE(content, 'Petru?', 'Petruț')
    WHERE INSTR(content, 'Petru?') > 0;
-- content 2 
UPDATE blogRecords SET content = REPLACE(content, 'Chi?in?u', 'Chișinău')
    WHERE INSTR(content, 'Chi?in?u') > 0;
-- content 3 
UPDATE blogRecords SET content = REPLACE(content, 'Vulc?ne?ti', 'Vulcănești')
    WHERE INSTR(content, 'Vulc?ne?ti') > 0;
-- content 4 
UPDATE blogRecords SET content = REPLACE(content, 'hor?', 'horă')
    WHERE INSTR(content, 'hor?') > 0;
-- content 5 
UPDATE blogRecords SET content = REPLACE(content, 'Vacan?a', 'Vacanța')
    WHERE INSTR(content, 'Vacan?a') > 0;
-- content 6 
UPDATE blogRecords SET content = REPLACE(content, 'Constan?a', 'Constanța')
    WHERE INSTR(content, 'Constan?a') > 0;
-- content 7 
UPDATE blogRecords SET content = REPLACE(content, 'Buz?u', 'Buzău')
    WHERE INSTR(content, 'Buz?u') > 0;
-- content 8 
UPDATE blogRecords SET content = REPLACE(content, 'Porti?ei', 'Portiței')
    WHERE INSTR(content, 'Porti?ei') > 0;
-- content 9 
UPDATE blogRecords SET content = REPLACE(content, '?tefan', 'Ștefan')
    WHERE INSTR(content, '?tefan') > 0;
-- content 10 
UPDATE blogRecords SET content = REPLACE(content, 'C?linescu', 'Călinescu')
    WHERE INSTR(content, 'C?linescu') > 0;
-- content 11 
UPDATE blogRecords SET content = REPLACE(content, 'St?nescu', 'Stănescu')
    WHERE INSTR(content, 'St?nescu') > 0;
-- content 12 
UPDATE blogRecords SET content = REPLACE(content, 'Costine?ti', 'Costinești')
    WHERE INSTR(content, 'Costine?ti') > 0;
-- content 13 
UPDATE blogRecords SET content = REPLACE(content, 'M?cin', 'Măcin')
    WHERE INSTR(content, 'M?cin') > 0;
-- content 14 
UPDATE blogRecords SET content = REPLACE(content, 'Br?ila', 'Brăila')
    WHERE INSTR(content, 'Br?ila') > 0;
-- content 15 
UPDATE blogRecords SET content = REPLACE(content, 'C?t?lin', 'Cătălin')
    WHERE INSTR(content, 'C?t?lin') > 0;
-- content 16 
UPDATE blogRecords SET content = REPLACE(content, 'Y???lca', 'Yığılca')
    WHERE INSTR(content, 'Y???lca') > 0;
-- content 17 
UPDATE blogRecords SET content = REPLACE(content, 'Alapl?', 'Alaplı')
    WHERE INSTR(content, 'Alapl?') > 0;
-- content 18 
UPDATE blogRecords SET content = REPLACE(content, 'K?z?lay', 'Kizalay')
    WHERE INSTR(content, 'K?z?lay') > 0;
-- content 19 
UPDATE blogRecords SET content = REPLACE(content, 'Arde?en', 'Ardeşen')
    WHERE INSTR(content, 'Arde?en') > 0;
-- content 20 
UPDATE blogRecords SET content = REPLACE(content, 'A?dam', 'Ağdam')
    WHERE INSTR(content, 'A?dam') > 0;
-- content 21 
UPDATE blogRecords SET content = REPLACE(content, '??neada', 'İğneada')
    WHERE INSTR(content, '??neada') > 0;
-- content 22 
UPDATE blogRecords SET content = REPLACE(content, '?skender', 'İskender')
    WHERE INSTR(content, '?skender') > 0;
-- content 23 
UPDATE blogRecords SET content = REPLACE(content, 'K?rklareli', 'Kirklareli')
    WHERE INSTR(content, 'K?rklareli') > 0;
-- content 24 
UPDATE blogRecords SET content = REPLACE(content, 'R?ze?ii', 'Răzeșii')
    WHERE INSTR(content, 'R?ze?ii') > 0;
-- content 25 
UPDATE blogRecords SET content = REPLACE(content, 'Dub?sari', 'Dubasari')
    WHERE INSTR(content, 'Dub?sari') > 0;
-- content 26 
UPDATE blogRecords SET content = REPLACE(content, 'Costa?', 'Costas')
    WHERE INSTR(content, 'Costa?') > 0;
-- content 27 
UPDATE blogRecords SET content = REPLACE(content, 'Çaml?hem?in', 'Çamlıhemşin')
    WHERE INSTR(content, 'Çaml?hem?in') > 0;
-- content 28 
UPDATE blogRecords SET content = REPLACE(content, 'Ç?ld?r', 'Çıldır')
    WHERE INSTR(content, 'Ç?ld?r') > 0;
-- content 29 
UPDATE blogRecords SET content = REPLACE(content, 'š?? All?h?', 'In šāۥ Allāh?')
    WHERE INSTR(content, 'š?? All?h?') > 0;
-- content 30 
UPDATE blogRecords SET content = REPLACE(content, 'K?y?köy', 'Kiyiköy')
    WHERE INSTR(content, 'K?y?köy') > 0;
-- content 31 
UPDATE blogRecords SET content = REPLACE(content, '???????? ???????????', 'პატრიციუ …სირცხვილია')
    WHERE INSTR(content, '???????? ???????????') > 0;


-- lead 1 
UPDATE blogRecords SET lead = REPLACE(lead, 'Petru?', 'Petruț')
    WHERE INSTR(lead, 'Petru?') > 0;
-- lead 2 
UPDATE blogRecords SET lead = REPLACE(lead, 'Chi?in?u', 'Chișinău')
    WHERE INSTR(lead, 'Chi?in?u') > 0;
-- lead 3 
UPDATE blogRecords SET lead = REPLACE(lead, 'Vulc?ne?ti', 'Vulcănești')
    WHERE INSTR(lead, 'Vulc?ne?ti') > 0;
-- lead 4 
UPDATE blogRecords SET lead = REPLACE(lead, 'hor?', 'horă')
    WHERE INSTR(lead, 'hor?') > 0;
-- lead 5 
UPDATE blogRecords SET lead = REPLACE(lead, 'Vacan?a', 'Vacanța')
    WHERE INSTR(lead, 'Vacan?a') > 0;
-- lead 6 
UPDATE blogRecords SET lead = REPLACE(lead, 'Constan?a', 'Constanța')
    WHERE INSTR(lead, 'Constan?a') > 0;
-- lead 7 
UPDATE blogRecords SET lead = REPLACE(lead, 'Buz?u', 'Buzău')
    WHERE INSTR(lead, 'Buz?u') > 0;
-- lead 8 
UPDATE blogRecords SET lead = REPLACE(lead, 'Porti?ei', 'Portiței')
    WHERE INSTR(lead, 'Porti?ei') > 0;
-- lead 9 
UPDATE blogRecords SET lead = REPLACE(lead, '?tefan', 'Ștefan')
    WHERE INSTR(lead, '?tefan') > 0;
-- lead 10 
UPDATE blogRecords SET lead = REPLACE(lead, 'C?linescu', 'Călinescu')
    WHERE INSTR(lead, 'C?linescu') > 0;
-- lead 11 
UPDATE blogRecords SET lead = REPLACE(lead, 'St?nescu', 'Stănescu')
    WHERE INSTR(lead, 'St?nescu') > 0;
-- lead 12 
UPDATE blogRecords SET lead = REPLACE(lead, 'Costine?ti', 'Costinești')
    WHERE INSTR(lead, 'Costine?ti') > 0;
-- lead 13 
UPDATE blogRecords SET lead = REPLACE(lead, 'M?cin', 'Măcin')
    WHERE INSTR(lead, 'M?cin') > 0;
-- lead 14 
UPDATE blogRecords SET lead = REPLACE(lead, 'Br?ila', 'Brăila')
    WHERE INSTR(lead, 'Br?ila') > 0;
-- lead 15 
UPDATE blogRecords SET lead = REPLACE(lead, 'C?t?lin', 'Cătălin')
    WHERE INSTR(lead, 'C?t?lin') > 0;
-- lead 16 
UPDATE blogRecords SET lead = REPLACE(lead, 'Y???lca', 'Yığılca')
    WHERE INSTR(lead, 'Y???lca') > 0;
-- lead 17 
UPDATE blogRecords SET lead = REPLACE(lead, 'Alapl?', 'Alaplı')
    WHERE INSTR(lead, 'Alapl?') > 0;
-- lead 18 
UPDATE blogRecords SET lead = REPLACE(lead, 'K?z?lay', 'Kizalay')
    WHERE INSTR(lead, 'K?z?lay') > 0;
-- lead 19 
UPDATE blogRecords SET lead = REPLACE(lead, 'Arde?en', 'Ardeşen')
    WHERE INSTR(lead, 'Arde?en') > 0;
-- lead 20 
UPDATE blogRecords SET lead = REPLACE(lead, 'A?dam', 'Ağdam')
    WHERE INSTR(lead, 'A?dam') > 0;
-- lead 21 
UPDATE blogRecords SET lead = REPLACE(lead, '??neada', 'İğneada')
    WHERE INSTR(lead, '??neada') > 0;
-- lead 22 
UPDATE blogRecords SET lead = REPLACE(lead, '?skender', 'İskender')
    WHERE INSTR(lead, '?skender') > 0;
-- lead 23 
UPDATE blogRecords SET lead = REPLACE(lead, 'K?rklareli', 'Kirklareli')
    WHERE INSTR(lead, 'K?rklareli') > 0;
-- lead 24 
UPDATE blogRecords SET lead = REPLACE(lead, 'R?ze?ii', 'Răzeșii')
    WHERE INSTR(lead, 'R?ze?ii') > 0;
-- lead 25 
UPDATE blogRecords SET lead = REPLACE(lead, 'Dub?sari', 'Dubasari')
    WHERE INSTR(lead, 'Dub?sari') > 0;
-- lead 26 
UPDATE blogRecords SET lead = REPLACE(lead, 'Costa?', 'Costas')
    WHERE INSTR(lead, 'Costa?') > 0;
-- lead 27 
UPDATE blogRecords SET lead = REPLACE(lead, 'Çaml?hem?in', 'Çamlıhemşin')
    WHERE INSTR(lead, 'Çaml?hem?in') > 0;
-- lead 28 
UPDATE blogRecords SET lead = REPLACE(lead, 'Ç?ld?r', 'Çıldır')
    WHERE INSTR(lead, 'Ç?ld?r') > 0;
-- lead 29 
UPDATE blogRecords SET lead = REPLACE(lead, 'š?? All?h?', 'In šāۥ Allāh?')
    WHERE INSTR(lead, 'š?? All?h?') > 0;
-- lead 30 
UPDATE blogRecords SET lead = REPLACE(lead, 'K?y?köy', 'Kiyiköy')
    WHERE INSTR(lead, 'K?y?köy') > 0;
-- lead 31 
UPDATE blogRecords SET lead = REPLACE(lead, '???????? ???????????', 'პატრიციუ …სირცხვილია')
    WHERE INSTR(lead, '???????? ???????????') > 0;


-- title 1 
UPDATE blogRecords SET title = REPLACE(title, 'Petru?', 'Petruț')
    WHERE INSTR(title, 'Petru?') > 0;
-- title 2 
UPDATE blogRecords SET title = REPLACE(title, 'Chi?in?u', 'Chișinău')
    WHERE INSTR(title, 'Chi?in?u') > 0;
-- title 3 
UPDATE blogRecords SET title = REPLACE(title, 'Vulc?ne?ti', 'Vulcănești')
    WHERE INSTR(title, 'Vulc?ne?ti') > 0;
-- title 4 
UPDATE blogRecords SET title = REPLACE(title, 'hor?', 'horă')
    WHERE INSTR(title, 'hor?') > 0;
-- title 5 
UPDATE blogRecords SET title = REPLACE(title, 'Vacan?a', 'Vacanța')
    WHERE INSTR(title, 'Vacan?a') > 0;
-- title 6 
UPDATE blogRecords SET title = REPLACE(title, 'Constan?a', 'Constanța')
    WHERE INSTR(title, 'Constan?a') > 0;
-- title 7 
UPDATE blogRecords SET title = REPLACE(title, 'Buz?u', 'Buzău')
    WHERE INSTR(title, 'Buz?u') > 0;
-- title 8 
UPDATE blogRecords SET title = REPLACE(title, 'Porti?ei', 'Portiței')
    WHERE INSTR(title, 'Porti?ei') > 0;
-- title 9 
UPDATE blogRecords SET title = REPLACE(title, '?tefan', 'Ștefan')
    WHERE INSTR(title, '?tefan') > 0;
-- title 10 
UPDATE blogRecords SET title = REPLACE(title, 'C?linescu', 'Călinescu')
    WHERE INSTR(title, 'C?linescu') > 0;
-- title 11 
UPDATE blogRecords SET title = REPLACE(title, 'St?nescu', 'Stănescu')
    WHERE INSTR(title, 'St?nescu') > 0;
-- title 12 
UPDATE blogRecords SET title = REPLACE(title, 'Costine?ti', 'Costinești')
    WHERE INSTR(title, 'Costine?ti') > 0;
-- title 13 
UPDATE blogRecords SET title = REPLACE(title, 'M?cin', 'Măcin')
    WHERE INSTR(title, 'M?cin') > 0;
-- title 14 
UPDATE blogRecords SET title = REPLACE(title, 'Br?ila', 'Brăila')
    WHERE INSTR(title, 'Br?ila') > 0;
-- title 15 
UPDATE blogRecords SET title = REPLACE(title, 'C?t?lin', 'Cătălin')
    WHERE INSTR(title, 'C?t?lin') > 0;
-- title 16 
UPDATE blogRecords SET title = REPLACE(title, 'Y???lca', 'Yığılca')
    WHERE INSTR(title, 'Y???lca') > 0;
-- title 17 
UPDATE blogRecords SET title = REPLACE(title, 'Alapl?', 'Alaplı')
    WHERE INSTR(title, 'Alapl?') > 0;
-- title 18 
UPDATE blogRecords SET title = REPLACE(title, 'K?z?lay', 'Kizalay')
    WHERE INSTR(title, 'K?z?lay') > 0;
-- title 19 
UPDATE blogRecords SET title = REPLACE(title, 'Arde?en', 'Ardeşen')
    WHERE INSTR(title, 'Arde?en') > 0;
-- title 20 
UPDATE blogRecords SET title = REPLACE(title, 'A?dam', 'Ağdam')
    WHERE INSTR(title, 'A?dam') > 0;
-- title 21 
UPDATE blogRecords SET title = REPLACE(title, '??neada', 'İğneada')
    WHERE INSTR(title, '??neada') > 0;
-- title 22 
UPDATE blogRecords SET title = REPLACE(title, '?skender', 'İskender')
    WHERE INSTR(title, '?skender') > 0;
-- title 23 
UPDATE blogRecords SET title = REPLACE(title, 'K?rklareli', 'Kirklareli')
    WHERE INSTR(title, 'K?rklareli') > 0;
-- title 24 
UPDATE blogRecords SET title = REPLACE(title, 'R?ze?ii', 'Răzeșii')
    WHERE INSTR(title, 'R?ze?ii') > 0;
-- title 25 
UPDATE blogRecords SET title = REPLACE(title, 'Dub?sari', 'Dubasari')
    WHERE INSTR(title, 'Dub?sari') > 0;
-- title 26 
UPDATE blogRecords SET title = REPLACE(title, 'Costa?', 'Costas')
    WHERE INSTR(title, 'Costa?') > 0;
-- title 27 
UPDATE blogRecords SET title = REPLACE(title, 'Çaml?hem?in', 'Çamlıhemşin')
    WHERE INSTR(title, 'Çaml?hem?in') > 0;
-- title 28 
UPDATE blogRecords SET title = REPLACE(title, 'Ç?ld?r', 'Çıldır')
    WHERE INSTR(title, 'Ç?ld?r') > 0;
-- title 29 
UPDATE blogRecords SET title = REPLACE(title, 'š?? All?h?', 'In šāۥ Allāh?')
    WHERE INSTR(title, 'š?? All?h?') > 0;
-- title 30 
UPDATE blogRecords SET title = REPLACE(title, 'K?y?köy', 'Kiyiköy')
    WHERE INSTR(title, 'K?y?köy') > 0;
-- title 31 
UPDATE blogRecords SET title = REPLACE(title, '???????? ???????????', 'პატრიციუ …სირცხვილია')
    WHERE INSTR(title, '???????? ???????????') > 0;
