create table users (
    id int unsigned,
    lastname varchar(100),
    firstname varchar(50),
    birthday date,
    email varchar(100),
    phone varchar(17),
    gender enum('male', 'female', 'other'),
    tz varchar(50),
    reg_date timestamp,
    reg_ip varchar(15)
);