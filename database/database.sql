CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('visitor', 'author', 'admin') NOT NULL,
    is_suspended BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    user_picture_path VARCHAR(255)
);

ALTER TABLE users 
CHANGE is_suspended statut ENUM('actif', 'suspendu') NOT NULL DEFAULT 'active';

ALTER TABLE users 
CHANGE created_at registration_date DATE NOT NULL;

CREATE TABLE categories (
    id_category INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE articles (
    id_article INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'refused') DEFAULT 'pending',
    user_picture_path VARCHAR(255),
    id_category INT NOT NULL REFERENCES categories(id_category) ON DELETE CASCADE,
    id_author INT NOT NULL REFERENCES users(id_user) ON DELETE CASCADE
);

INSERT INTO users (first_name, last_name, email, password, role) VALUES ('Mohamed Abdelhak', 'DADSSI', 'd4dssi@gmail.com', 'Admin@123', 'admin');


