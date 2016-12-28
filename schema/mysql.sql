create table if not exists `Feedback`
(
	`id` int(10) not null auto_increment,
	`email` varchar(100),
	primary key (`id`)
) engine InnoDB;
