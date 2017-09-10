DROP DATABASE IF EXISTS twitter;
CREATE DATABASE twitter;
use twitter;

CREATE TABLE user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(40) UNIQUE,
  name VARCHAR(255),
  hashed_pass VARCHAR(255)
);

INSERT INTO user VALUES
  (null,"adam@spadam.pl","Ada","###");