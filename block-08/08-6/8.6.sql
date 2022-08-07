CREATE TABLE category (
    id int,
    title varchar( 64 ),
    alias varchar( 64 ),
    PRIMARY KEY( id ),
    UNIQUE( alias )
);

CREATE TABLE user (
    id int,
    login varchar( 32 ),
    password varchar( 128 ),
    email varchar( 255 ),
    lastname varchar( 64 ),
    firstname varchar( 64 ),
    PRIMARY KEY( id ),
    UNIQUE( login, email )
);

CREATE TABLE post (
    id int,
    author_id int,
    category_id int,
    title varchar( 128 ),
    alias varchar( 128 ),
    content longtext,
    publish_date datetime,
    status varchar( 32 ),
    PRIMARY KEY( id ),
    UNIQUE( alias ),
    FOREIGN KEY( author_id ) REFERENCES user( id ),
    FOREIGN KEY( category_id ) REFERENCES category( id )
);

CREATE TABLE post_cover (
    id int,
    post_id int,
    filename varchar( 128 ),
    title varchar( 64 ),
    PRIMARY KEY( id ),
    UNIQUE( filename ),
    FOREIGN KEY( post_id ) REFERENCES post( id )
);

CREATE TABLE post_comment (
    id int,
    user_id int,
    post_id int,
    created_date datetime,
    comment varchar( 255 ),
    PRIMARY KEY( id ),
    FOREIGN KEY( user_id ) REFERENCES user( id ),
    FOREIGN KEY( post_id ) REFERENCES post( id )
);
