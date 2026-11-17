CREATE TABLE Users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    user_name VARCHAR(25) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    pass_hash TEXT NOT NULL,
    datetime_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Musics (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    artist VARCHAR(150) NOT NULL,
    cover_path VARCHAR(255) UNIQUE,
    music_path VARCHAR(255) NOT NULL UNIQUE,
    views INT NOT NULL DEFAULT 0,
    datetime_uploaded TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fk_user_upload INT NOT NULL REFERENCES Users(id)
);

CREATE TABLE Playlist (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    datetime_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fk_id_owner INT NOT NULL REFERENCES Users(id)
);

CREATE TABLE Subscriptions (
    id SERIAL PRIMARY KEY,
    fk_id_followed INT NOT NULL REFERENCES Users(id),
    fk_id_following INT NOT NULL REFERENCES Users(id)
);

CREATE TABLE Likes (
    id SERIAL PRIMARY KEY,
    fk_id_music INT NOT NULL REFERENCES Musics(id),
    fk_id_user INT NOT NULL REFERENCES Users(id)
);

CREATE TABLE PlaylistMusic (
    id SERIAL PRIMARY KEY,
    fk_id_playlist INT NOT NULL REFERENCES Playlist(id),
    fk_id_music INT NOT NULL REFERENCES Musics(id)
);