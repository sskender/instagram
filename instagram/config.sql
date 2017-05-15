/**
 *  MySQL DATABASE SETUP
 *
 *
 *  Initialize file like this:
 *
 *  mysql -u <username> -p < config.sql
 *  Type in password
 *
 *  DONE!
 */



-- DATABASE
CREATE DATABASE instagram;
USE instagram;



-- USERS TABLE
CREATE TABLE users (
    user_id         INTEGER UNSIGNED    NOT NULL    AUTO_INCREMENT  PRIMARY KEY,
    username        VARCHAR(30)         NOT NULL,
    password        VARCHAR(255)        NOT NULL,
    email           VARCHAR(255)        NOT NULL
);


-- FOLLOWS TABLE
CREATE TABLE follows (
    follow_id       INTEGER UNSIGNED    NOT NULL    AUTO_INCREMENT  PRIMARY KEY,
    id_from         INTEGER UNSIGNED    NOT NULL,
    id_to           INTEGER UNSIGNED    NOT NULL
);


-- PROFILE PHOTOS TABLE
CREATE TABLE photos (
    photo_id        INTEGER UNSIGNED    NOT NULL    AUTO_INCREMENT  PRIMARY KEY,
    user_id         INTEGER UNSIGNED    NOT NULL,
    photo_hash      VARCHAR(60)         NOT NULL
);


-- UPLOADS TABLE
CREATE TABLE uploads (
    photo_id        INTEGER UNSIGNED    NOT NULL    AUTO_INCREMENT  PRIMARY KEY,
    user_id         INTEGER UNSIGNED    NOT NULL,
    photo_hash      VARCHAR(60)         NOT NULL,
    timestamp       DATETIME            NOT NULL    DEFAULT NOW()
);


-- LOGINS TABLE
CREATE TABLE logs (
    log_id          INTEGER UNSIGNED    NOT NULL    AUTO_INCREMENT  PRIMARY KEY,
    user_id         INTEGER UNSIGNED    NOT NULL,
    ip              VARCHAR(15)         NOT NULL,
    timestamp       DATETIME            NOT NULL    DEFAULT NOW()
);