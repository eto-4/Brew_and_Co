CREATE TABLE usuaris (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL DEFAULT 'client',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE adreces (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    usuari_id BIGINT UNSIGNED NOT NULL,
    etiqueta VARCHAR(255) NOT NULL,
    adreca VARCHAR(255) NOT NULL,
    codi_postal VARCHAR(20) NOT NULL,
    ciutat VARCHAR(255) NOT NULL,
    predeterminada BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_adreca_usuari
        FOREIGN KEY (usuari_id)
        REFERENCES usuaris(id)
        ON DELETE CASCADE
);

CREATE TABLE categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    descripcio VARCHAR(500) NULL,
    activa BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE productes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    descripcio VARCHAR(500) NULL,
    preu DECIMAL(10,2) NOT NULL,
    imatge VARCHAR(255) NULL,
    disponible BOOLEAN NOT NULL DEFAULT TRUE,
    destacat BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE categoria_producte (
    categoria_id BIGINT UNSIGNED NOT NULL,
    producte_id BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (categoria_id, producte_id),

    CONSTRAINT fk_cp_categoria
        FOREIGN KEY (categoria_id)
        REFERENCES categories(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_cp_producte
        FOREIGN KEY (producte_id)
        REFERENCES productes(id)
        ON DELETE CASCADE
);

CREATE TABLE ofertes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    preu_rebaixat DECIMAL(10,2) NOT NULL,
    data_inici DATETIME NOT NULL,
    data_fi DATETIME NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE producte_oferta (
    producte_id BIGINT UNSIGNED NOT NULL,
    oferta_id BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (producte_id, oferta_id),

    CONSTRAINT fk_po_producte
        FOREIGN KEY (producte_id)
        REFERENCES productes(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_po_oferta
        FOREIGN KEY (oferta_id)
        REFERENCES ofertes(id)
        ON DELETE CASCADE
);

CREATE TABLE codis_descompte (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    codi VARCHAR(100) NOT NULL UNIQUE,
    percentatge DECIMAL(5,2) NOT NULL,
    actiu BOOLEAN NOT NULL DEFAULT TRUE,
    expires_at DATETIME NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE comandes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    usuari_id BIGINT UNSIGNED NOT NULL,
    adreca_id BIGINT UNSIGNED NULL,
    temps_estimat DATETIME NULL,
    missatge VARCHAR(255) NULL,
    estat VARCHAR(100) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_comanda_usuari
        FOREIGN KEY (usuari_id)
        REFERENCES usuaris(id),

    CONSTRAINT fk_comanda_adreca
        FOREIGN KEY (adreca_id)
        REFERENCES adreces(id)
);

CREATE TABLE linies_comanda (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    comanda_id BIGINT UNSIGNED NOT NULL,
    producte_id BIGINT UNSIGNED NOT NULL,
    quantitat INT NOT NULL,
    preu_unitari DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_lc_comanda
        FOREIGN KEY (comanda_id)
        REFERENCES comandes(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_lc_producte
        FOREIGN KEY (producte_id)
        REFERENCES productes(id)
);

CREATE TABLE pagaments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    comanda_id BIGINT UNSIGNED NOT NULL,
    metode VARCHAR(255) NOT NULL,
    estat VARCHAR(100) NOT NULL,
    descripcio VARCHAR(500) NULL,
    codi_descompte_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_pagament_comanda
        FOREIGN KEY (comanda_id)
        REFERENCES comandes(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_pagament_codi
        FOREIGN KEY (codi_descompte_id)
        REFERENCES codis_descompte(id)
        ON DELETE SET NULL
);