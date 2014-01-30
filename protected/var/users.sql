INSERT INTO auth_users (uid, cid, name, active, email, password)
    VALUES ('7',  '4', 'michael', '1', 'michael@no-spam.org','icxnlrf'),
           ('8',  '4', 'vlad',    '1', 'vlad@no-spam.org','934rhfdj'),
           ('9',  '4', 'adrian',  '1', 'adrian@no-spam.org','IUsd7d'),
           ('10', '4', 'sorin',   '1', 'sorin@no-spam.org','kldsf7D');

INSERT INTO auth_user_details (uid, first_name, last_name,title)
    VALUES ('7',  'Michael', 'Bird',     'Journalist'),
           ('8',  'Vlad',    'Ursulean', 'Journalist'),
           ('9',  'Adrian',  'Mogoș',    'Journalist'),
           ('10', 'Sorin',   'Ozon',     'Journalist');

INSERT INTO auth_user_stats (uid)
VALUES ('7'),('8'),('9'),('10');
