CREATE DATABASE IF NOT EXISTS library;
USE library;

CREATE TABLE IF NOT EXISTS users (
    email VARCHAR(100),
    password VARCHAR(255),
    name VARCHAR(100),
    avatar VARCHAR(255),
    CONSTRAINT pk_users PRIMARY KEY (email)
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT,
    name VARCHAR(100),
    description VARCHAR(255),
    CONSTRAINT pk_categories PRIMARY KEY (id)
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
    visits INT DEFAULT 0,
    CONSTRAINT pk_book PRIMARY KEY (id),
    CONSTRAINT fk_books_users FOREIGN KEY (user_email) REFERENCES users (email) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS books_categories (
    id_books INT,
    id_categories INT,
    CONSTRAINT pk_books_categories PRIMARY KEY (id_books, id_categories),
    CONSTRAINT fk_books_categories_books FOREIGN KEY (id_books) REFERENCES books (id) ON DELETE CASCADE,
    CONSTRAINT fk_books_categories_categories FOREIGN KEY (id_categories) REFERENCES categories (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_votes (
    id_books INT,
    user_email VARCHAR(100),
    vote INT,
    CONSTRAINT pk_users_votes PRIMARY KEY (id_books, user_email),
    CONSTRAINT fk_users_votes_books FOREIGN KEY (id_books) REFERENCES books (id) ON DELETE CASCADE,
    CONSTRAINT fk_users_votes_users FOREIGN KEY (user_email) REFERENCES users (email) ON DELETE CASCADE
); 

INSERT INTO categories (name, description) VALUES
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

INSERT INTO users VALUES 
('miguel@prueba.com', '$argon2id$v=19$m=131072,t=4,p=2$eXJ1M2h4bkRYUDA3MGQyLg$GSe7xt5kclUdmE0K6OicA7GCDFd8j77i1S6ElUd9RH4', 'Miguel', 'default-avatar.png'),
('pepe@prueba.com', '$argon2id$v=19$m=131072,t=4,p=2$ZDlGTjRKZHVaUi9yUnpYVw$9Q0ALDvX934t4uK/65bifoA3p91MrzmeGm3Je4+/gTY', 'Pepe', 'default-avatar.png');

INSERT INTO books (title, description, URL, year, user_email, author, cover) VALUES
('The Great Gatsby', 'A classic American novel set in the Jazz Age, exploring themes of wealth, love, and the American Dream through the mysterious Jay Gatsby.', 'https://example.com/great-gatsby', 1925, 'miguel@prueba.com', 'F. Scott Fitzgerald', 'default.png'),
('To Kill a Mockingbird', 'A powerful story of racial injustice and childhood innocence in the American South, told through the eyes of young Scout Finch.', 'https://example.com/mockingbird', 1960, 'miguel@prueba.com', 'Harper Lee', 'default.png'),
('1984', 'A dystopian social science fiction novel depicting a totalitarian society under constant surveillance and thought control.', 'https://example.com/1984', 1949, 'miguel@prueba.com', 'George Orwell', 'default.png'),
('Pride and Prejudice', 'A romantic novel of manners that chronicles the relationship between Elizabeth Bennet and Mr. Darcy in Regency England.', 'https://example.com/pride-prejudice', 1813, 'miguel@prueba.com', 'Jane Austen', 'default.png'),
('The Catcher in the Rye', 'A controversial coming-of-age novel following teenager Holden Caulfield as he navigates alienation and identity in New York City.', 'https://example.com/catcher-rye', 1951, 'miguel@prueba.com', 'J.D. Salinger', 'default.png'),
('One Hundred Years of Solitude', 'A landmark work of magical realism following seven generations of the Buendía family in the fictional town of Macondo.', 'https://example.com/hundred-years', 1967, 'miguel@prueba.com', 'Gabriel García Márquez', 'default.png'),
('The Hobbit', 'A fantasy adventure novel about Bilbo Baggins, a hobbit who embarks on an unexpected journey with dwarves to reclaim their mountain home.', 'https://example.com/hobbit', 1937, 'miguel@prueba.com', 'J.R.R. Tolkien', 'default.png'),
('Harry Potter and the Sorcerer''s Stone', 'The first book in the beloved series about a young wizard discovering his magical heritage and attending Hogwarts School.', 'https://example.com/harry-potter-1', 1997, 'miguel@prueba.com', 'J.K. Rowling', 'default.png'),
('The Lord of the Rings', 'An epic high fantasy trilogy about the quest to destroy the One Ring and defeat the dark lord Sauron.', 'https://example.com/lotr', 1954, 'miguel@prueba.com', 'J.R.R. Tolkien', 'default.png'),
('Don Quixote', 'A Spanish classic following the adventures of an aging nobleman who becomes convinced he is a knight-errant.', 'https://example.com/don-quixote', 1605, 'pepe@prueba.com', 'Miguel de Cervantes', 'default.png'),
('The Alchemist', 'A philosophical novel about a young shepherd''s journey to find treasure and discover his personal legend.', 'https://example.com/alchemist', 1988, 'pepe@prueba.com', 'Paulo Coelho', 'default.png'),
('Brave New World', 'A dystopian novel depicting a futuristic society based on technological advancement, conditioning, and social hierarchy.', 'https://example.com/brave-new-world', 1932, 'pepe@prueba.com', 'Aldous Huxley', 'default.png');


INSERT INTO books_categories (id_books, id_categories) VALUES
(1, 1),
(1, 9),
(2, 1),
(2, 9),
(3, 1),
(3, 3),
(3, 11),
(4, 1),
(4, 8),
(4, 9),
(5, 1),
(5, 12),
(6, 1),
(6, 4),
(7, 4),
(7, 10),
(8, 4),
(8, 12),
(8, 10),
(9, 4),
(9, 10),
(10, 1),
(10, 9),
(10, 10),
(11, 1),
(11, 10),
(11, 17),
(12, 1),
(12, 3),
(12, 11);