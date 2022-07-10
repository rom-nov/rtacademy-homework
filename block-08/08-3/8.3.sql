--MariaDB
create table users (
    id int unsigned auto_increment,
    lastname varchar(100) not null,
    firstname varchar(50) not null,
    birthday date default null,
    email varchar(100) not null,
    phone varchar(17) default null,
    gender enum('male', 'female', 'other') default 'other',
    tz varchar(50) default null,
    reg_date timestamp not null,
    reg_ip varchar(15) default null,
    primary key (id),
    unique (email)
);


--PostgreSQL
create type sex as enum ('male', 'female', 'other');
create table users (
    id serial,
    lastname varchar(100) not null,
    firstname varchar(50) not null,
    birthday date default null,
    email varchar(100) not null,
    phone varchar(17) default null,
    gender sex default 'other',
    time_zone varchar(50) default null,
    reg_date timestamp without time zone not null ,
    reg_ip inet default null,
    primary key (id),
    unique (email)
);

insert into users (lastname, firstname, email, phone, gender, reg_date, reg_ip)
values ('Novikov', 'Roman', 'novikovrom@gmail.com', '+380991483988', 'male',
        '2022-06-29 18:50:15.000000', '198.24.10.0/24');
