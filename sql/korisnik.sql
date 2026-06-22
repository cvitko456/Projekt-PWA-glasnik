-- Izrada tablice "korisnik" za prijavu i registraciju
-- Pokrenuti NAKON baza.sql - koristi istu bazu "glasnik".

USE glasnik;

CREATE TABLE IF NOT EXISTS korisnik (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ime VARCHAR(50) NOT NULL,
  prezime VARCHAR(50) NOT NULL,
  korisnicko_ime VARCHAR(50) NOT NULL,
  lozinka VARCHAR(255) NOT NULL,
  razina INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY korisnicko_ime_unique (korisnicko_ime)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ručna promjena vrijednosti razine kako bi korisnik postao administrator (razina = 1):
--   UPDATE korisnik SET razina = 1 WHERE korisnicko_ime = 'vase_ime';