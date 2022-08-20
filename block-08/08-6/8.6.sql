CREATE TABLE category (
    id    int           AUTO_INCREMENT,
    title varchar( 64 ) NOT NULL,
    alias varchar( 64 ) NOT NULL,
    PRIMARY KEY( id ),
    UNIQUE LOWER( alias )
);

CREATE TABLE user (
    id        int            AUTO_INCREMENT,
    login     varchar( 32 )  NOT NULL,
    password  varchar( 128 ) NOT NULL,
    email     varchar( 255 ) NOT NULL,
    lastname  varchar( 64 )  DEFAULT NULL,
    firstname varchar( 64 )  DEFAULT NULL,
    PRIMARY KEY( id ),
    UNIQUE LOWER( login, email )
);

CREATE TABLE post (
    id           int            AUTO_INCREMENT,
    author_id    int,
    category_id  int,
    title        varchar( 128 ) NOT NULL,
    alias        varchar( 128 ) NOT NULL,
    content      longtext       NOT NULL,
    publish_date datetime       NOT NULL,
    status       varchar( 32 )  NOT NULL,
    PRIMARY KEY( id ),
    UNIQUE LOWER( alias ),
    FOREIGN KEY( author_id )   REFERENCES user( id ),
    FOREIGN KEY( category_id ) REFERENCES category( id )
);

CREATE TABLE post_cover (
    id       int            AUTO_INCREMENT,
    post_id  int,
    filename varchar( 128 ) NOT NULL,
    title    varchar( 64 )  DEFAULT NULL,
    PRIMARY KEY( id ),
    UNIQUE LOWER( filename ),
    FOREIGN KEY( post_id ) REFERENCES post( id )
);

CREATE TABLE post_comment (
    id           int            AUTO_INCREMENT,
    user_id      int,
    post_id      int,
    created_date datetime       NOT NULL,
    comment      varchar( 255 ) NOT NULL ,
    PRIMARY KEY( id ),
    FOREIGN KEY( user_id ) REFERENCES user( id ),
    FOREIGN KEY( post_id ) REFERENCES post( id )
);
