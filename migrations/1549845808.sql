CREATE TABLE IF NOT EXISTS Users(
  ID INTEGER AUTO_INCREMENT,
  Email VARCHAR(255) UNIQUE NOT NULL,
  Password CHAR(60) BINARY NOT NULL,
  PRIMARY KEY (ID)
);
