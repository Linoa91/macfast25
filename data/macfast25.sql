CREATE DATABASE IF NOT EXISTS macfast25;

USE macfast25;

DROP TABLE IF EXISTS users;
create table users (
	email varchar(255) NOT NULL PRIMARY KEY,
	nom varchar(255) NOT NULL,
	prenom varchar(255) NOT NULL,
	telephone varchar(10),
	newsletter bool,
	offre_partenaires bool,
  code_participation varchar(8)
);

DROP TABLE IF EXISTS cadeaux;
create table cadeaux (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nom varchar(255) NOT NULL,
  code_activation varchar(8),
  type_cadeau varchar(3),
  coupon varchar(8)
);

INSERT INTO cadeaux
  (nom, type, code_activation, coupon)
VALUES
  ('1 voyages à New York', '', '29d35aeb', '20d7ffe1'), 
  ('1 week end à Paris',   '', '6d10a01f', 'bb9629c7'),
  ('1 IPhone 6',           '', 'aaf4ae1b', 'cc12d0fe'),
  ('1 goodie',             '', '26d37aea', '20b7afe9');