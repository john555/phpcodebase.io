
drop  table post;
create table post(
    id varchar(16) not null,
    user_id varchar(16) not null,
    message text not null,
    timestamp TIMESTAMP not null default CURRENT_TIMESTAMP,
    primary key(id),
    constraint fkey 
        foreign key (user_id) references user(id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;
