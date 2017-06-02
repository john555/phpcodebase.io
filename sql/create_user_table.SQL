drop table user;
create table user(
    id varchar(16) primary key,
    first_name varchar(50) not null,
    last_name varchar(50) not null,
    username varchar(50) not null unique,
    email varchar(60) not null unique,
    email_confirmed tinyint(1) unsigned not null default 0,
    password_hash varchar(255) not null,
    phone_number varchar(25),
    phone_number_confirmed tinyint(1) unsigned not null default 0,
    is_active tinyint(1) unsigned not null default 1,
    timestamp TIMESTAMP not null default CURRENT_TIMESTAMP,
    lockout_enabled tinyint(1) not null default 0,
    lockout_end_date DATETIME,
    access_failed_count int(1) not null default 0
) ENGINE=INNODB DEFAULT CHARSET=utf8;
