
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    html TEXT
) ;

CREATE INDEX idx_reference ON articles(id);
CREATE INDEX idx_titre ON articles(titre);


CREATE TABLE IF NOT EXISTS article_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT,
    chemin VARCHAR(255),
    alt TEXT,
    CONSTRAINT fk_article_images_article FOREIGN KEY (article_id) REFERENCES articles(id)
) ;

CREATE TABLE IF NOT EXISTS article_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT,
    details INT,
    CONSTRAINT fk_article_details_article FOREIGN KEY (article_id) REFERENCES articles(id),
    CONSTRAINT fk_article_details_details FOREIGN KEY (details) REFERENCES articles(id)
) ;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);

INSERT INTO users (username, password) VALUES ('admin', 'admin');

ALTER TABLE articles
ADD COLUMN date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN auteur INTEGER;

ALTER TABLE articles
ADD CONSTRAINT fk_article_auteur FOREIGN KEY (auteur) REFERENCES users(id);