INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender, Phone_number, Email, Height, Weight) VALUES (NULL, 'Jan', 'Kowalski', 'jan', 'password', 1, 1, '123456789', 'jan.kowalski@o2.com', 185, 83);
INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender,  Phone_number, Email, Height, Weight) VALUES (NULL, 'Maria', 'Nowak', 'maria', 'azerty', 1, 2, '451254851', 'maria.nowak@gmail.com', 160, 50);
INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender,  Phone_number, Email, Height, Weight) VALUES (NULL, 'Zygmunt', 'Rominski', 'zygmunt', 'dvorak', 1, 1, '854776185', 'zygmunt.rominski@o2.com', 184, 78);
INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender, Phone_number, Email, Height, Weight) VALUES (NULL, 'Admin', 'Adminowski', 'admin', 'asdf', 3, 1, '123456789', 'admin@admin.com', 180, 70);
INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender, Phone_number, Email, Height, Weight) VALUES (NULL, 'Trener', 'Treningowski', 'trener', 'trener', 2, 1, '987654321', 'trener@gmail.com', 180, 70);
INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender, Phone_number, Email, Height, Weight) VALUES (NULL, 'Lucjusz', 'Czerwiński', 'lucjusz', 'lucjusz', 2, 1, '987654321', 'lucjusz@gmail.com', 180, 70);
INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender, Phone_number, Email, Height, Weight) VALUES (NULL, 'Marian', 'Tomaszewski', 'marian', 'marian', 2, 1, '987654321', 'marian@gmail.com', 180, 70);
INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender, Phone_number, Email, Height, Weight) VALUES (NULL, 'Przemysław', 'Wiśniewski', 'przemysław', 'przemysław', 2, 1, '987654321', 'przemysław@gmail.com', 180, 70);
INSERT INTO users (ID, Name, Surname, Login, Password, User_type, Gender, Phone_number, Email, Height, Weight) VALUES (NULL, 'Wiktor', 'Kowalczyk', 'wiktor', 'wiktor', 2, 1, '987654321', 'wiktor@gmail.com', 180, 70);



INSERT INTO body_parts VALUES (NULL, 'Arms');
INSERT INTO body_parts VALUES (NULL, 'Back');
INSERT INTO body_parts VALUES (NULL, 'Chest');
INSERT INTO body_parts VALUES (NULL, 'Core');
INSERT INTO body_parts VALUES (NULL, 'Legs');
INSERT INTO body_parts VALUES (NULL, 'Shoulders');
INSERT INTO body_parts VALUES (NULL, 'ABS');

INSERT INTO Exercise_types VALUES (NULL, 'Endurance');
INSERT INTO Exercise_types VALUES (NULL, 'Strength');
INSERT INTO Exercise_types VALUES (NULL, 'Balance');
INSERT INTO Exercise_types VALUES (NULL, 'Flexibility');

INSERT INTO Exercises VALUES (NULL, 'Crunches', 1, 400, 'None',1, NULL);
INSERT INTO Exercises VALUES (NULL, 'Situps', 1, 500, 'None',1, NULL);
INSERT INTO Exercises VALUES (NULL, 'Pushups', 2, 600, 'None',0, 1);
INSERT INTO Exercises VALUES (NULL, 'Pushups', 2, 600, 'None',0, 1);


INSERT INTO Exercises_Body_parts VALUES (NULL,1,7);
INSERT INTO Exercises_Body_parts VALUES (NULL,2,5);
INSERT INTO Exercises_Body_parts VALUES (NULL,3,1);
INSERT INTO Exercises_Body_parts VALUES (NULL,3,6);

INSERT INTO training_types VALUES(NULL,'Low Intensity, Long Duration');
INSERT INTO training_types VALUES(NULL,'Medium Intensity, Medium Duration');
INSERT INTO training_types VALUES(NULL,'High Intensity, Short Duration');
INSERT INTO training_types VALUES(NULL,'Aerobic Interval Training');
INSERT INTO training_types VALUES(NULL,'Anaerobic Interval Training');
INSERT INTO training_types VALUES(NULL,'Fartlek Training');
INSERT INTO training_types VALUES(NULL,'Circuit Training');

INSERT INTO series VALUES (NULL, 2,10,NULL,NULL);
INSERT INTO series VALUES (NULL, 1,30,NULL,NULL);

INSERT INTO Training_session VALUES(NULL, 'Training 1',1,'2017-12-31',2,5,'Gym','trolololo');

INSERT INTO Training_session_Series VALUES (NULL,1,1);
INSERT INTO Training_session_Series VALUES (NULL,1,2);