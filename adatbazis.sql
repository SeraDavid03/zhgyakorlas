DROP SCHEMA IF EXISTS adatbazis;
CREATE SCHEMA adatbazis DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE adatbazis;

CREATE TABLE adatbazis (
	id INT PRIMARY KEY AUTO_INCREMENT,
	egyesulet VARCHAR(100),
	alapitasev YEAR,
    alapito VARCHAR(100),
    alapitoszul DATE,
    tagsagidij VARCHAR(100)
);

CREATE TABLE tagok (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nev VARCHAR(100),
    szul DATE,
    csatlakozaseve DATE,
    cegid INT,
     CONSTRAINT FK_tagok_adatbazis FOREIGN KEY (cegid) REFERENCES adatbazis(id)
);

INSERT INTO adatbazis (egyesulet, alapitasev, alapito, alapitoszul, tagsagidij) 
VALUES
('Informatikusok országos szövetsége', 1980, 'Török Gábor', '1975-05-13', 3000),
('PHP programozók társasága', 2010, 'Juhász András', '1981-09-27', 4500),
('CSS kedvelők szövetsége', 2017, 'Bálint Olívia', '1980-06-18', 2700),
('SQL adatbázist használók egyesülete', 2022, 'Kozma Ottó', '1990-01-30', 5000);

INSERT INTO tagok (nev, szul, csatlakozaseve, cegid)
VALUES
('Tamás Evelin', '1946-12-01', '2012-01-01', 3),
('Tamás Evelin', '1946-12-01', '1990-01-01', 2),
('Bálint Olívia', '1980-06-18', '1990-01-01', 2),
('Juhász András', '1981-09-27', '2019-01-01', 1),
('Kozma Ottó', '1990-01-30', '2020-01-01', 1);

SELECT * FROM adatbazis;