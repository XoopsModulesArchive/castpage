CREATE TABLE CastPage (
    ID          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL DEFAULT '',
    first_seen  VARCHAR(255) NOT NULL DEFAULT '',
    last_seen   VARCHAR(255) NOT NULL DEFAULT '',
    picture     VARCHAR(255) NOT NULL DEFAULT '',
    description TEXT         NOT NULL,
    section_id  INT          NOT NULL DEFAULT '1',
    chapter_id  INT          NOT NULL DEFAULT '1',
    PRIMARY KEY (ID)
)
    ENGINE = ISAM;

CREATE TABLE CastPage_Chapters (
    Chapter_id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Chapter_name      VARCHAR(255) DEFAULT NULL,
    Chapter_image     VARCHAR(255),
    Chapter_directory VARCHAR(255),
    PRIMARY KEY (Chapter_id)
)
    ENGINE = ISAM;

CREATE TABLE CastPage_Sections (
    Section_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Section_name VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (Section_id)
)
    ENGINE = ISAM;

CREATE TABLE CastPage_Setup (
    id           INT,
    title        VARCHAR(255),
    next_image   VARCHAR(255),
    prev_image   VARCHAR(255),
    Image_size_x INT,
    Image_size_y INT,
    First_seen   VARCHAR(255),
    Last_seen    VARCHAR(255)
)
    ENGINE = ISAM;

INSERT INTO CastPage_Setup (id, title)
VALUES ('1', 'Temp Title');
