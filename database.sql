CREATE TABLE IF NOT EXISTS Category (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), variable INT, defaultValue INT, active TINYINT DEFAULT 1)engine=innoDB;
CREATE TABLE IF NOT EXISTS FieldType (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), type INT NOT NULL, active TINYINT DEFAULT 1)engine=innoDB;
CREATE TABLE IF NOT EXISTS Phenobook (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200), experimental_units_number INT, experimental_unit_name VARCHAR(300), visible TINYINT DEFAULT 1, stamp DATETIME, description TEXT, userGroup INT, active TINYINT DEFAULT 1)engine=innoDB;
CREATE TABLE IF NOT EXISTS PhenobookVariable (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, phenobook INT, variable INT, active TINYINT DEFAULT 1)engine=innoDB;
CREATE TABLE IF NOT EXISTS Registry (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, user INT, experimental_unit_number INT, stamp DATETIME, variable INT, status TINYINT DEFAULT 1, value TEXT, latitude VARCHAR(100), phenobook INT, longitude VARCHAR(100), localStamp DATETIME, mobile TINYINT DEFAULT 0, fixed TINYINT DEFAULT 0, active TINYINT DEFAULT 1)engine=innoDB;
CREATE TABLE IF NOT EXISTS UserGroup (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), active TINYINT DEFAULT 1)engine=innoDB;
CREATE TABLE IF NOT EXISTS Variable (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200), required TINYINT DEFAULT 0, description VARCHAR(300), fieldType INT, isInformative TINYINT DEFAULT 0, active TINYINT DEFAULT 1, userGroup INT DEFAULT null)engine=innoDB;
CREATE TABLE IF NOT EXISTS Recover (id_recover INT NOT NULL AUTO_INCREMENT PRIMARY KEY, user INT, datetime DATETIME, hash INT, status INT, active INT)engine=innoDB;
CREATE TABLE IF NOT EXISTS User (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200), lastName VARCHAR(200), email VARCHAR(200), pass VARCHAR(200), passChanged TINYINT DEFAULT 0, isAdmin TINYINT DEFAULT 1,isSuperAdmin TINYINT DEFAULT 1, active TINYINT DEFAULT 1, userGroup INT)engine=innoDB;
CREATE TABLE IF NOT EXISTS EMail (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, email_from VARCHAR(200), email_to VARCHAR(200), subject VARCHAR(200), body VARCHAR(200), priority INT, datetimeCreated DATETIME, datetimeSent DATETIME, status INT, active TINYINT DEFAULT 1)engine=innoDB;


ALTER TABLE Category ADD CONSTRAINT fk_Category_Variable_variable FOREIGN KEY (variable) REFERENCES Variable(id);
ALTER TABLE Phenobook ADD CONSTRAINT fk_Phenobook_UserGroup_userGroup FOREIGN KEY (userGroup) REFERENCES UserGroup(id);
ALTER TABLE PhenobookVariable ADD CONSTRAINT fk_PhenobookVariable_Phenobook_phenobook FOREIGN KEY (phenobook) REFERENCES Phenobook(id);
ALTER TABLE PhenobookVariable ADD CONSTRAINT fk_PhenobookVariable_Variable_variable FOREIGN KEY (variable) REFERENCES Variable(id);
ALTER TABLE Registry ADD CONSTRAINT fk_Registry_User_user FOREIGN KEY (user) REFERENCES User(id);
ALTER TABLE Registry ADD CONSTRAINT fk_Registry_Variable_variable FOREIGN KEY (variable) REFERENCES Variable(id);
ALTER TABLE Registry ADD CONSTRAINT fk_Registry_Phenobook_phenobook FOREIGN KEY (phenobook) REFERENCES Phenobook(id);
ALTER TABLE Variable ADD CONSTRAINT fk_Variable_FieldType_fieldType FOREIGN KEY (fieldType) REFERENCES FieldType(id);
ALTER TABLE Recover ADD CONSTRAINT fk_Recover_User_user FOREIGN KEY (user) REFERENCES User(id);
ALTER TABLE User ADD CONSTRAINT fk_User_UserGroup_userGroup FOREIGN KEY (userGroup) REFERENCES UserGroup(id);

INSERT INTO UserGroup (name ) VALUES ('Default');
INSERT INTO User (name,lastName, email, pass,userGroup ) VALUES ('John','Doe','test@user.com','password','1');
INSERT INTO FieldType (name, type ) VALUES ('Text','1');
INSERT INTO FieldType (name, type ) VALUES ('Categorical','2');
INSERT INTO FieldType (name, type ) VALUES ('Check','3');
INSERT INTO FieldType (name, type ) VALUES ('Number','4');
INSERT INTO FieldType (name, type ) VALUES ('Date','5');
INSERT INTO FieldType (name, type ) VALUES ('Photo','6');
INSERT INTO FieldType (name, type ) VALUES ('Date Time','7');
INSERT INTO FieldType (name, type ) VALUES ('Time','8');
