
-- Tabela usuário --
CREATE  TABLE IF NOT EXISTS user (
  id INT NOT NULL AUTO_INCREMENT ,
  name VARCHAR(45) NOT NULL ,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL ,
  token VARCHAR(255) NOT NULL,
  drink_counter INT(11) DEFAULT '0' NOT NULL,
  status INT(1) DEFAULT '1' NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id))
  CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Tabela drink_user --
CREATE  TABLE IF NOT EXISTS user_drink (
  user_id INT NOT NULL,
  drink_ml INT NOT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE CASCADE
  )