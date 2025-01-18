CREATE DATABASE IF NOT EXISTS blog;
USE blog;

CREATE TABLE IF NOT EXISTS usuaris (
    id INT AUTO_INCREMENT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    cognom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    data DATE NOT NULL,
    CONSTRAINT pk_usuaris PRIMARY KEY (id),
    CONSTRAINT uq_email UNIQUE (email)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(100),
    CONSTRAINT pk_categories PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS entrades (
    id INT AUTO_INCREMENT NOT NULL,
    usuari_id INT NOT NULL,
    categoria_id INT NOT NULL,
    titol VARCHAR(255) NOT NULL,
    descripcio MEDIUMTEXT,
    data DATE NOT NULL,
    CONSTRAINT pk_entrades PRIMARY KEY (id),
    CONSTRAINT fk_entrada_usuari FOREIGN KEY (usua>
    CONSTRAINT fk_entrada_categoria FOREIGN KEY (c>
) ENGINE=InnoDB;
