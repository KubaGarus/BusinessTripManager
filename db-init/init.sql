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
    introduction_date DATE NOT NULL,
    acceptance_date DATE,
    status INT NOT NULL
);

CREATE TABLE business_trips_expenses (
    expense_id SERIAL PRIMARY KEY,
    expense_date DATE NOT NULL,
    cost FLOAT NOT NULL,
    note VARCHAR(100) NOT NULL,
    business_trip_id INT NOT NULL,
    CONSTRAINT fk_business_trip
        FOREIGN KEY (business_trip_id)
        REFERENCES business_trips (business_trip_id)
        ON DELETE CASCADE
);

CREATE TABLE business_trips_basic_data (
    business_trip_basic_id SERIAL PRIMARY KEY,
    purpose VARCHAR(255) NOT NULL,
    transport VARCHAR(255) NOT NULL,
    business_trip_id INT NOT NULL,
    CONSTRAINT fk_business_trip
        FOREIGN KEY (business_trip_id)
        REFERENCES business_trips (business_trip_id)
        ON DELETE CASCADE
);

CREATE TABLE business_trips_logs (
    log_id SERIAL PRIMARY KEY,
    operation_type VARCHAR(10) NOT NULL,
    business_trip_id INT,
    user_id INT,
    operation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    log_message TEXT
);

CREATE OR REPLACE FUNCTION log_business_trip_operation()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO business_trips_logs (operation_type, business_trip_id, user_id, log_message)
        VALUES ('INSERT', NEW.business_trip_id, NEW.user_id, 'Delegacja została dodana.');
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO business_trips_logs (operation_type, business_trip_id, user_id, log_message)
        VALUES ('UPDATE', NEW.business_trip_id, NEW.user_id, 'Delegacja została zmodyfikowana.');
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO business_trips_logs (operation_type, business_trip_id, user_id, log_message)
        VALUES ('DELETE', OLD.business_trip_id, OLD.user_id, 'Delegacja została usunięta.');
        RETURN OLD;
    END IF;
    
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_business_trip_insert
AFTER INSERT ON business_trips
FOR EACH ROW
EXECUTE FUNCTION log_business_trip_operation();

CREATE TRIGGER after_business_trip_update
AFTER UPDATE ON business_trips
FOR EACH ROW
EXECUTE FUNCTION log_business_trip_operation();

CREATE TRIGGER after_business_trip_delete
AFTER DELETE ON business_trips
FOR EACH ROW
EXECUTE FUNCTION log_business_trip_operation();

CREATE VIEW business_trips_informations_view AS
SELECT 
    bt.business_trip_id,
    bt.user_id,
    bt.introduction_date,
    bt.acceptance_date,
    bt.status,
    btb.purpose,
    u.firstname,
    u.surname,
    btb.transport,
    exp.expense_id,
    exp.expense_date,
    exp.cost,
    exp.note
FROM business_trips bt
LEFT JOIN business_trips_basic_data btb ON bt.business_trip_id = btb.business_trip_id
LEFT JOIN business_trips_expenses exp ON bt.business_trip_id = exp.business_trip_id
LEFT JOIN users u ON bt.user_id = u.user_id