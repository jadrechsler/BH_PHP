CREATE TABLE users (
    id INT UNSIGNED PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    relation TINYTEXT,
    email VARCHAR(255),
    pin INT,
    present TINYINT(1)
);

CREATE TABLE info (
    `date` TEXT,
    reports TEXT
);

CREATE TABLE nextid (
    floater INT UNSIGNED DEFAULT 4,
    carer INT UNSIGNED DEFAULT 500,
    child INT UNSIGNED DEFAULT 1000
);

INSERT INTO nextid() VALUES(); -- Initialize to default values