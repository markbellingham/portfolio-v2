CREATE DATABASE music;

USE music;

CREATE TABLE IF NOT EXISTS albums (
    album_id INT(7) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    album_artist VARCHAR(255),
    title VARCHAR(255),
    YEAR VARCHAR(255),
    image VARCHAR(255),
    genre_id INT(5),
    artist_id INT(5),
    top50 INT,
    playcount INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS artists (
    artist_id INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    artist VARCHAR(255) NOT NULL,
    top50 INT,
    playcount INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS genres (
    genre_id INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    genre VARCHAR(255) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS tracks (
    trackId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    track_no CHAR(5),
    track_name VARCHAR(255),
    duration VARCHAR(10),
    filename VARCHAR(255),
    album_id INT,
    artist_id INT,
    top50 INT,
    playcount INT,
    KEY fk_album_id1 (album_id),
    CONSTRAINT fk_album_id1 FOREIGN KEY (album_id) REFERENCES albums (album_id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE DATABASE contact;

USE contact;

CREATE TABLE IF NOT EXISTS icons (
    icon_id INT(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    icon VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    colour VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO icons (icon, name)
VALUES
    ('<i class="fas fa-desktop"></i>', 'Monitor'),
    ('<i class="fas fa-mobile-alt"></i>', 'Mobile'),
    ('<i class="far fa-keyboard"></i>','Keyboard'),
    ('<i class="fas fa-mouse"></i>','Mouse'),
    ('<i class="fas fa-headphones"></i>','Headphones'),
    ('<i class="fas fa-laptop"></i>','Laptop'),
    ('<i class="fas fa-robot"></i>','Robot'),
    ('<i class="fas fa-code"></i>','Code'),
    ('<i class="far fa-file-alt"></i>','File'),
    ('<i class="far fa-folder"></i>','Folder'),
    ('<i class="fas fa-bug"></i>','Bug'),
    ('<i class="fas fa-battery-half"></i>','Battery'),
    ('<i class="fas fa-wifi"></i>','Wifi');
