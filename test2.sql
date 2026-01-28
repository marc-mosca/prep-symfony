-- Create Municipalities Table
CREATE TABLE municipalities
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Create User Table
CREATE TABLE users
(
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    username            VARCHAR(255) NOT NULL,
    password            VARCHAR(255) NOT NULL,
    is_blocked          BOOLEAN DEFAULT FALSE,
    role                ENUM('ADMIN', 'REDACTEUR', 'CORRESPONDANT'),
    municipality_id     INT NOT NULL,

    CONSTRAINT fk_users_municipality FOREIGN KEY (municipality_id) REFERENCES municipalities(id)
) ENGINE=InnoDB;

-- Create Newspapers Table
CREATE TABLE newspapers
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    numero      INT NOT NULL,
    is_actif    BOOLEAN DEFAULT FALSE,
    is_locked   BOOLEAN DEFAULT FALSE,
    locked_at   DATETIME
) ENGINE=InnoDB;

-- Create Contact Type Table
CREATE TABLE contact_type
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Create Contact Tables
CREATE TABLE contact
(
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(255) NOT NULL,
    email               VARCHAR(255) NOT NULL,
    phone               VARCHAR(20) NOT NULL,
    type_id             INT NOT NULL,
    municipality_id     INT NOT NULL,

    CONSTRAINT fk_contact_type FOREIGN KEY (type_id) REFERENCES contact_type(id),
    CONSTRAINT fk_contact_municipality FOREIGN KEY (municipality_id) REFERENCES municipalities(id)
) ENGINE=InnoDB;

-- Create Articles Table
CREATE TABLE articles
(
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    title               VARCHAR(255) NOT NULL,
    description         TEXT NOT NULL,
    is_locked           BOOLEAN DEFAULT FALSE,
    locked_at           DATETIME,
    created_at          DATETIME NOT NULL,
    updated_at          DATETIME NOT NULL,
    municipality_id     INT NOT NULL,
    newspaper_id        INT NOT NULL,

    CONSTRAINT fk_articles_municipality FOREIGN KEY (municipality_id) REFERENCES municipalities(id),
    CONSTRAINT fk_articles_newspaper FOREIGN KEY (newspaper_id) REFERENCES newspapers(id)
) ENGINE=InnoDB;

-- Create Prices Types Table
CREATE TABLE prices_types
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Create Calendars Table
CREATE TABLE calendars
(
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    title               VARCHAR(255) NOT NULL,
    description         TEXT NOT NULL,
    email               VARCHAR(255),
    phone               VARCHAR(255),
    website             VARCHAR(255),
    coordinates         TEXT,
    schedules           VARCHAR(255),
    place               VARCHAR(255),
    start               DATETIME NOT NULL,
    end                 DATETIME,
    is_locked           BOOLEAN DEFAULT FALSE,
    locked_at           DATETIME,
    created_at          DATETIME NOT NULL,
    updated_at          DATETIME NOT NULL,
    municipality_id     INT NOT NULL,
    newspaper_id        INT NOT NULL,

    CONSTRAINT fk_calendars_municipality FOREIGN KEY (municipality_id) REFERENCES municipalities(id),
    CONSTRAINT fk_calendars_newspaper FOREIGN KEY (newspaper_id) REFERENCES newspapers(id)
) ENGINE=InnoDB;

-- Create Prices Tables
CREATE TABLE calendars_prices
(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    amount          DOUBLE DEFAULT 0,
    type_id         INT NOT NULL,
    calendar_id     INT NOT NULL,

    CONSTRAINT fk_calendars_prices_type FOREIGN KEY (type_id) REFERENCES prices_types(id),
    CONSTRAINT fk_calendars_prices_calendar FOREIGN KEY (calendar_id) REFERENCES calendars(id)
) ENGINE=InnoDB;

-- Create Upload Table
CREATE TABLE uploads
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    legend      TEXT NOT NULL,
    path        VARCHAR(255) NOT NULL,
    filename    VARCHAR(255) NOT NULL,
    is_deleted  BOOLEAN DEFAULT FALSE,
    article_id  INT NOT NULL,

    CONSTRAINT fk_uploads_article FOREIGN KEY (article_id) REFERENCES articles(id)
) ENGINE=InnoDB;
