CREATE TABLE Users
(
ID INTEGER NOT NULL AUTO_INCREMENT,
Name VARCHAR(20) NOT NULL,
Surname VARCHAR(50) NOT NULL,
Login VARCHAR(30) NOT NULL,
Password VARCHAR(30) NOT NULL,
User_type INTEGER,
Gender INTEGER NOT NULL,
Phone_number VARCHAR(15),
Email VARCHAR(50),
Height INTEGER,
Weight INTEGER,
CONSTRAINT Users_PK PRIMARY KEY (ID),
CONSTRAINT Users_Login_U UNIQUE (Login),
CONSTRAINT Users_Email_U UNIQUE (Email)
)DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


CREATE TABLE Training_types
(
ID INTEGER NOT NULL AUTO_INCREMENT,
Name VARCHAR(50) NOT NULL,
CONSTRAINT Training_types_PK PRIMARY KEY (ID),
CONSTRAINT Training_types_Name_U UNIQUE (Name)
);

CREATE TABLE Body_parts
(
ID INTEGER NOT NULL AUTO_INCREMENT,
Name VARCHAR(30) NOT NULL,
CONSTRAINT Body_parts_PK PRIMARY KEY (ID),
CONSTRAINT Body_parts_Name_U UNIQUE (Name)
);

CREATE TABLE Exercise_types
(
ID INTEGER NOT NULL AUTO_INCREMENT,
Name VARCHAR(50) NOT NULL,
CONSTRAINT Exercise_types_PK PRIMARY KEY (ID),
CONSTRAINT Exercise_types_Name_U UNIQUE (Name)
);

CREATE TABLE Training_session
(
ID INTEGER NOT NULL AUTO_INCREMENT,
Name VARCHAR(30),
ID_User INTEGER,
Training_date Date,
ID_Training_type INTEGER,
ID_Trainer INTEGER,
Location VARCHAR(40),
Description VARCHAR(200),
CONSTRAINT Training_session_PK PRIMARY KEY (ID),
CONSTRAINT Training_session_ID_User_FK FOREIGN KEY	(ID_User) REFERENCES Users (ID),
CONSTRAINT Training_session_ID_Training_type_FK FOREIGN KEY (ID_Training_type) REFERENCES Training_types (ID),
CONSTRAINT Training_session_ID_Trainer_FK FOREIGN KEY (ID_Trainer) REFERENCES Users (ID)
)DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE Exercises
(
ID INTEGER NOT NULL AUTO_INCREMENT,
Name VARCHAR(20),
ID_Exercise_type INTEGER,
Calories_burning_rate INTEGER,
Equipment VARCHAR(100),
Permanent INTEGER,
ID_User INTEGER,
CONSTRAINT Exercises_PK PRIMARY KEY(ID),
CONSTRAINT Exercises_Name_User_ID_U UNIQUE (Name, ID_User),
CONSTRAINT EXercises_ID_Exercise_type FOREIGN KEY (ID_Exercise_type) REFERENCES Exercise_types(ID),
CONSTRAINT Exercises_ID_User FOREIGN KEY (ID_User) REFERENCES Users(ID)
);

CREATE TABLE Exercises_Body_parts
(
ID INTEGER NOT NULL AUTO_INCREMENT,
ID_Exercises INTEGER,
ID_Body_parts INTEGER,
CONSTRAINT Exercises_Body_parts_PK PRIMARY KEY (ID),
CONSTRAINT Exercises_Body_parts_U UNIQUE (ID_Exercises, ID_Body_parts),
CONSTRAINT Exercises_Body_parts_ID_Exercises_FK FOREIGN KEY (ID_Exercises) REFERENCES Exercises(ID),
CONSTRAINT Exercises_Body_parts_ID_Body_parts_FK FOREIGN KEY (ID_Body_parts) REFERENCES Body_parts(ID)
);

CREATE TABLE Series
(
ID INTEGER NOT NULL AUTO_INCREMENT,
ID_Exercise INTEGER,
Repetition INTEGER,
Duration FLOAT(4),
Series_load FLOAT(4),
CONSTRAINT Series_PK PRIMARY KEY (ID),
CONSTRAINT Series_ID_Exercise_FK FOREIGN KEY (ID_Exercise) REFERENCES Exercises(ID)
);

CREATE TABLE Training_session_Series
(
ID INTEGER NOT NULL AUTO_INCREMENT,
ID_Training_session INTEGER,
ID_Series INTEGER,
CONSTRAINT Training_session_Series_PK PRIMARY KEY (ID),
CONSTRAINT Training_session_Series_U UNIQUE (ID_Training_session, ID_Series),
CONSTRAINT Training_session_Series_ID_Training_session_FK FOREIGN KEY (ID_Training_session) REFERENCES Training_session(ID),
CONSTRAINT Training_session_Series_ID_Series_FK FOREIGN KEY (ID_Series) REFERENCES Series(ID)
) DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


ALTER TABLE Users
MODIFY `ID` INTEGER NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE Exercises
MODIFY `ID` INTEGER NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE Exercises_Body_parts
MODIFY `ID` INTEGER NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE training_types
MODIFY `ID` INTEGER NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE training_session
MODIFY `ID` INTEGER NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE Series
MODIFY `ID` INTEGER NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE Training_session_Series
MODIFY `ID` INTEGER NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;