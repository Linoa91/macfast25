CREATE DATABASE macfast25;

USE macfast25;

DROP TABLE IF EXISTS `users`;
create table users (
	email varchar(255) NOT NULL PRIMARY KEY,
	nom varchar(255) NOT NULL,
	prenom varchar(255) NOT NULL,
	telephone varchar(10),
    code_participation varchar(8)
);

DROP TABLE IF EXISTS `cadeaux`;
create table cadeaux (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nom varchar(255) NOT NULL,
    code_activation varchar(8),
    coupon varchar(8)
);