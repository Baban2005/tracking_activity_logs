-- funeral home management system
-- database schema
-- defines all tables used by the application

-- stores registered user accounts and their hashed passwords
CREATE TABLE user_passwords (
    user_id    INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50),
    password   VARCHAR(50),   -- stored as sha1 hash
    first_name VARCHAR(50),
    last_name  VARCHAR(50),
    address    VARCHAR(255),
    age        INT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- parent entity: funeral directors
-- one director can have many services
CREATE TABLE funeral_directors (
    director_id      INT AUTO_INCREMENT PRIMARY KEY,
    username         VARCHAR(50),
    first_name       VARCHAR(50),
    last_name        VARCHAR(50),
    date_of_birth    VARCHAR(50),
    specialization   TEXT,
    added_by         VARCHAR(50),  -- username of who inserted this record
    last_updated_by  VARCHAR(50),  -- username of who last edited this record
    date_added       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- child entity: funeral services
-- each service belongs to one director (one-to-many relationship)
CREATE TABLE services (
    service_id      INT AUTO_INCREMENT PRIMARY KEY,
    service_name    VARCHAR(50),
    package_type    TEXT,
    director_id     INT,          -- foreign key referencing funeral_directors
    added_by        VARCHAR(50),  -- username of who inserted this record
    last_updated_by VARCHAR(50),  -- username of who last edited this record
    date_added      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- tracks all crud operations performed in the system
-- used by the activity logs page
CREATE TABLE activity_logs (
    log_id             INT AUTO_INCREMENT PRIMARY KEY,
    performed_by       VARCHAR(50),  -- username of who did the action
    action             VARCHAR(20),  -- create, read, update, or delete
    table_affected     VARCHAR(50),  -- which table was changed
    record_description TEXT,         -- human-readable summary of what happened
    date_performed     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
