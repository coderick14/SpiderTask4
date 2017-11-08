CREATE TABLE users (
    Id int(4) NOT NULL AUTO_INCREMENT,
    user_name varchar(20) NOT NULL,
    user_pass varchar(50) NOT NULL,
    user_time datetime NOT NULL,
    user_level varchar(10) NOT NULL DEFAULT 'Visitor',
    mod_status varchar(255) NOT NULL DEFAULT 'No',
    PRIMARY KEY (Id),
    UNIQUE KEY user_name (user_name)
);

CREATE TABLE posts (
    Id int(4) NOT NULL AUTO_INCREMENT,
    post_content text NOT NULL,
    post_time datetime NOT NULL,
    post_topic varchar(200) NOT NULL,
    post_by varchar(25) NOT NULL DEFAULT 'Anonymous',
    PRIMARY KEY (Id),
    KEY post_by (post_by)
);

CREATE TABLE mods (
    post_topic varchar(25) NOT NULL,
    post_content text NOT NULL,
    post_time datetime NOT NULL,
    post_by varchar(25) NOT NULL,
    Id int(5) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (Id)
);

INSERT INTO users VALUES (1, "admin", "password", NOW(), "Admin");
