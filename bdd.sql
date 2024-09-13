CREATE DATABASE IF NOT EXISTS pendu;
USE pendu;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Table des scores
CREATE TABLE IF NOT EXISTS scores (
    username VARCHAR(50) PRIMARY KEY,
    score INT NOT NULL DEFAULT 0,
    FOREIGN KEY (username) REFERENCES utilisateurs(username) ON DELETE CASCADE
);