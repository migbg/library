CREATE DATABASE IF NOT EXISTS library;
USE library;

CREATE TABLE IF NOT EXISTS users (
    email VARCHAR(100),
    password VARCHAR(255),
    name VARCHAR(100),
    CONSTRAINT pk_users PRIMARY KEY (email)
);

CREATE TABLE IF NOT EXISTS categories (
    name VARCHAR(100),
    description VARCHAR(255),
    CONSTRAINT pk_categories PRIMARY KEY (name)
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT,
    title VARCHAR(100),
    description TEXT,
    URL VARCHAR(255),
    year INT,
    user_email VARCHAR(100),
    author VARCHAR(100),
    cover VARCHAR(255),
    CONSTRAINT pk_book PRIMARY KEY (id),
    CONSTRAINT fk_books_users FOREIGN KEY (user_email) REFERENCES users (email)
);

CREATE TABLE IF NOT EXISTS books_categories (
    id_books INT,
    name_categories VARCHAR(100),
    CONSTRAINT pk_books_categories PRIMARY KEY (id_books, name_categories),
    CONSTRAINT fk_books_categories_books FOREIGN KEY (id_books) REFERENCES books (id) ON DELETE CASCADE,
    CONSTRAINT fk_books_categories_categories FOREIGN KEY (name_categories) REFERENCES categories (name) ON DELETE CASCADE
);

INSERT INTO categories VALUES
('Fiction', 'Imaginative narratives and stories'),
('Non-Fiction', 'Factual and informative books based on real events'),
('Science Fiction', 'Futuristic stories involving technology, space, and scientific concepts'),
('Fantasy', 'Magical worlds with mythical creatures and supernatural elements'),
('Horror', 'Scary stories designed to frighten and create suspense'),
('Mystery', 'Crime-solving narratives with detectives and puzzles'),
('Thriller', 'Fast-paced stories with tension, suspense, and action'),
('Romance', 'Love stories focusing on romantic relationships'),
('Historical Fiction', 'Stories set in the past with historical settings'),
('Adventure', 'Action-packed tales of exploration and excitement'),
('Dystopian', 'Dark visions of future societies gone wrong'),
('Young Adult', 'Books written for teenage readers'),
('Biography', 'True stories about real people''s lives'),
('Autobiography', 'Personal life stories written by the subject'),
('History', 'Books about historical events and periods'),
('Science', 'Popular science books explaining scientific concepts'),
('Philosophy', 'Books exploring fundamental questions about existence and knowledge'),
('Psychology', 'Books about the human mind and behavior'),
('Self-Help', 'Books offering guidance for personal improvement'),
('Essay', 'Collections of analytical or reflective writings'),
('Travel', 'Books about places, journeys, and travel experiences'),
('Cooking', 'Recipe books and culinary guides'),
('Art', 'Books about visual arts, artists, and artistic techniques'),
('Business', 'Books about commerce, entrepreneurship, and management'),
('Economics', 'Books about economic theory and financial systems'),
('Technology', 'Books about technological innovations and digital trends'),
('Computing', 'Books about computers, programming, and software'),
('Poetry', 'Collections of poems and verse'),
('Drama', 'Theatrical plays and dramatic works'),
('Graphic Novel', 'Long-form narrative comics with literary depth'),
('Comics', 'Sequential art storytelling in illustrated format'),
('Children''s Books', 'Books written for young children'),
('Educational', 'Textbooks and instructional materials'),
('Religion', 'Books about faith, spirituality, and religious practices'),
('Other', 'Books that don''t fit standard categories');