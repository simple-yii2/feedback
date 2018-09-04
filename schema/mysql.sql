create table if not exists `feedback`
(
    `id` int(10) not null auto_increment,
    `email` varchar(100),
    primary key (`id`)
) engine InnoDB;
