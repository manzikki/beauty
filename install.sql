CREATE TABLE Customer
(
  CustomerID INT NOT NULL,
  CustomerName VARCHAR(30) NOT NULL,
  PRIMARY KEY (CustomerID)
);

CREATE TABLE Artist
(
  ArtistID INT NOT NULL,
  ArtistName VARCHAR(30) NOT NULL,
  SpecialityName VARCHAR(30) NOT NULL,
  HourlyRate INT NOT NULL,
  PRIMARY KEY (ArtistID)
);

CREATE TABLE Booking
(
  Date DATE NOT NULL,
  Timeofday INT NOT NULL,
  ArtistID INT NOT NULL,
  CustomerID INT NOT NULL,
  FOREIGN KEY (ArtistID) REFERENCES Artist(ArtistID),
  FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

insert into Customer values (1,"Joan Jett");
insert into Customer values (2,"Lita Ford");

insert into Artist values (101,"John Lennon", "Hair", 300);
insert into Artist values (102,"Paul McCartney", "Manicure", 250);

insert into Booking values ("2020-03-25",11,101,1);

