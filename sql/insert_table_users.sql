insert into `users` (
    `user`,
    `pass`,
    `family_name`,
    `first_name`,
    `is_admin`
)
values
(
    'test1',
    '$2y$10$eSePpwz2hteTQZNXO1BvFeI.VCSGF/YqGdpZda/sHQDQWzAJoehYi', -- パスワード test1
    'テスト',
    '花子',
    0
),
(
    'test2',
    '$2y$10$btIzYtozzeEJ2J53ZU/Qz.YBK61RilXtGcVJkrZfz1r/fS8R72F.i', -- パスワード test2
    'テスト',
    '太郎',
    0
);
(
    'test3',
    '$2y$10$pQ99VXOdPt3Dg6TEkUtzPeFeROzGB9RJbkkk2AhcovvEuDQXmuCo.', -- パスワード test3
    'テスト',
    '管理者',
    1
);
