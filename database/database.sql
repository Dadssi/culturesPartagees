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
INSERT INTO users (first_name, last_name, email, password, role) VALUES ('Mohamed Abdelhak', 'DADSSI', 'dadssi@gmail.com', 'Admin@123', 'admin');

ALTER TABLE users 
CHANGE is_suspended statut ENUM('actif', 'suspendu') NOT NULL DEFAULT 'actif';

ALTER TABLE users 
CHANGE created_at registration_date CURRENT_DATE NOT NULL;

CREATE TABLE categories (
    id_category INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL UNIQUE
);

INSERT INTO categories (label) VALUES ('médecine');

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
ALTER TABLE articles CHANGE user_picture_path article_picture_path VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;



CREATE TABLE tags (
    id_tag INT AUTO_INCREMENT PRIMARY KEY,
    tag_title VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE article_tags (
    id_article INT NOT NULL,
    id_tag INT NOT NULL,
    PRIMARY KEY (id_article, id_tag),
    FOREIGN KEY (id_article) REFERENCES articles(id_article) ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tags(id_tag) ON DELETE CASCADE
);

INSERT INTO articles (title, content, id_category, id_author, article_picture_path) 
VALUES ('article 1', 'Contenu de l\'article 1', 1, 2, '..assets/imgs/uploads/677ab9ac1e856-garde-royale.jpg');

SET @article_id = LAST_INSERT_ID();

INSERT INTO article_tags (id_article, id_tag) 
VALUES 
    (@article_id, 1), 
    (@article_id, 5), 
    (@article_id, 2);

    SELECT * FROM articles WHERE id_article = @article_id;


SELECT * FROM article_tags WHERE id_article = @article_id;


SELECT t.tag_title 
FROM tags t
JOIN article_tags at ON t.id_tag = at.id_tag
WHERE at.id_article = @article_id;





