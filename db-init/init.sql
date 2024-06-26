CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    function INT NOT NULL,
    firstname VARCHAR(40) NOT NULL,
    surname VARCHAR(40) NOT NULL,
    mail_address VARCHAR(40) NOT NULL
);

INSERT INTO users (username, password, function, firstname, surname, mail_address) VALUES
('admin', md5('password'), -9, 'admin', 'admin', 'admin@o2.pl');

CREATE TABLE business_trips (
    business_trip_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    intrudaction_date DATE NOT NULL,
    acceptance_date DATE,
    status INT NOT NULL
);

-- CREATE TABLE business_trips_expenses (
--     expense_id SERIAL PRIMARY KEY,
--     expense_type INT(1) NOT NULL,
--     expense_date DATE NOT NULL,
--     cost FLOAT NOT NULL,
--     note VARCHAR(100) NOT NULL,
--     attachment_id INT DEFAULT 0
-- );

-- CREATE TABLE business_trips_expenses_attachments (
--     attachment_id SERIAL PRIMARY KEY,
--     name VARCHAR(100) NOT NULL,
--     size INT(100) NOT NULL,
--     type VARCHAR(100) NOT NULL,
--     content BLOB NOT NULL
-- );

-- CREATE TABLE business_trips_basic_data (

-- );

-- CREATE TABLE business_trips_options (

-- );

-- CREATE TABLE business_trips_places (

-- );

-- CREATE TABLE business_trips_lump_sums (

-- );
