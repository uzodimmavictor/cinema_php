CREATE TABLE user_(
   userId VARCHAR(50),
   userPassword VARCHAR(255),
   userEmail VARCHAR(100),
   isAdmin BOOLEAN,
   PRIMARY KEY(userId)
);

CREATE TABLE film(
   filmId VARCHAR(50),
   filmTitle VARCHAR(50),
   filmAuthor VARCHAR(50),
   filmDetail VARCHAR(50),
   filmCategory VARCHAR(50),
   filmTime SMALLINT,
   filmPoster VARCHAR(50),
   PRIMARY KEY(filmId)
);

CREATE TABLE room(
   roomId INT,
   roomNumberOfSeats SMALLINT,
   roomCharacteristic VARCHAR(50),
   PRIMARY KEY(roomId)
);

CREATE TABLE seat(
   roomId INT,
   seatId INT,
   seatRow SMALLINT,
   seatColumn VARCHAR(1),
   PRIMARY KEY(roomId, seatId),
   FOREIGN KEY(roomId) REFERENCES room(roomId)
);

CREATE TABLE sceance(
   sceanceId VARCHAR(50),
   sceanceDate DATETIME,
   filmId VARCHAR(50) NOT NULL,
   roomId INT NOT NULL,
   PRIMARY KEY(sceanceId),
   FOREIGN KEY(filmId) REFERENCES film(filmId),
   FOREIGN KEY(roomId) REFERENCES room(roomId)
);

CREATE TABLE reservation(
   userId VARCHAR(50),
   roomId INT,
   seatId INT,
   sceanceId VARCHAR(50),
   PRIMARY KEY(userId, roomId, seatId, sceanceId),
   FOREIGN KEY(userId) REFERENCES user_(userId),
   FOREIGN KEY(roomId, seatId) REFERENCES seat(roomId, seatId),
   FOREIGN KEY(sceanceId) REFERENCES sceance(sceanceId)
);

