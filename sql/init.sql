CREATE TABLE users (
    id INT UNSIGNED PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    relation TINYTEXT,
    email VARCHAR(255),
    pin VARCHAR(255),
    carers TEXT,
    teacher INT UNSIGNED,
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

INSERT INTO users(id, name, pin) VALUES(1, "Default Admin Name", "0000");
INSERT INTO users(id, name, email, pin) VALUES(2, "Default Teacher Name", "teacher@domain.com", "0000");
INSERT INTO users(id, name, email, pin) VALUES(3, "Default Teacher Name", "teacher@domain.com", "0000");
INSERT INTO users(id, name, email, pin) VALUES(4, "Default Floater Name", "floater@domain.com", "0000");