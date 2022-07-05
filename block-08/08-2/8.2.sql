create type sex as enum ('male', 'female', 'other');
create table users (
    id serial,
    lastname varchar(100),
    firstname varchar(50),
    birthday date,
    email varchar(100),
    phone varchar(17),
    gender sex,
    time_zone varchar(50),
    reg_date timestamp without time zone,
    reg_ip inet
);

insert into users (lastname, firstname, birthday, email, phone, gender, time_zone, reg_date, reg_ip)
values ('Novikov', 'Roman', '1985-11-26', 'novikovrom@gmail.com', '+380991483988', 'male',
        'UTC+3', '2022-06-29 18:50:15.000000', '198.24.10.0/24');

insert into users (lastname, firstname, birthday, email, phone, gender, time_zone, reg_date, reg_ip)
values ('Novikova', 'Julia', '1986-09-01', 'novikovajulia@gmail.com', '+380991234567', 'female',
        'UTC+3', '2022-06-29 18:55:15.000000', '198.24.20.10/24');