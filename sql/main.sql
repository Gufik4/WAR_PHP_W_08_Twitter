DROP DATABASE IF EXISTS twitter;
CREATE DATABASE twitter;
use twitter;

CREATE TABLE user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(40) UNIQUE,
  name VARCHAR(255),
  hashed_pass VARCHAR(255)
);

CREATE TABLE tweet (
  id INT PRIMARY KEY AUTO_INCREMENT,
  content VARCHAR(140),
  user_id INT NOT NULL,
  created_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES user(id)
  ON DELETE CASCADE
);

INSERT INTO user VALUES
  (null,"adam@spadam.pl","Ada","###");

INSERT INTO tweet VALUES
  (null,"Jestem adamem",1,NOW()),
  (null,"Ciągle spadam",1,NOW());

