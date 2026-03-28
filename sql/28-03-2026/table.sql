CREATE TABLE IF NOT EXISTS articles (
    id SERIAL PRIMARY KEY,
        reference VARCHAR,
    html TEXT,
);

CREATE TABLE IF NOT EXISTS article_images(
    id SERIAL PRIMARY KEY,
    article_id REFERENCES articles(id),
    chemin VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS article_details(
    id SERIAL PRIMARY KEY,
        article_id INTEGER REFERENCES articles(id),
        details INTEGER REFERENCES articles(id)
);

