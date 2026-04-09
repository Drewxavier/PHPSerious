<?php
// music_app.php
// Run: php music_app.php

class Song {
    public $title;//declaring items
    public $artist;
    public $album;
    public $genre;
    public $duration;

    public function __construct($title, $artist, $album, $genre, $duration) {
        $this->title = $title;
        $this->artist = $artist;
        $this->album = $album;
        $this->genre = $genre;
        $this->duration = $duration;
    }

    public function __toString() {
        return "{$this->title} by {$this->artist} ({$this->album}, {$this->genre}, {$this->duration} mins)";
    }
}

class MusicApp {
    private $songs = [];
    private $playlists = [];
    private $storageFile = "music_data.json";

    public function __construct() {
        $this->loadData();
    }

    private function loadData() {
        if (file_exists($this->storageFile)) {
            $data = json_decode(file_get_contents($this->storageFile), true);
            if ($data) {
                foreach ($data['songs'] as $s) {
                    $this->songs[] = new Song($s['title'], $s['artist'], $s['album'], $s['genre'], $s['duration']);
                }
                $this->playlists = $data['playlists'];
            }
        }
    }

    private function saveData() {
        $data = [
            'songs' => array_map(function($s) {
                return [
                    'title' => $s->title,
                    'artist' => $s->artist,
                    'album' => $s->album,
                    'genre' => $s->genre,
                    'duration' => $s->duration
                ];
            }, $this->songs),
            'playlists' => $this->playlists
        ];
        file_put_contents($this->storageFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function addSong($title, $artist, $album, $genre, $duration) {
        $this->songs[] = new Song($title, $artist, $album, $genre, $duration);
        $this->saveData();
        echo "Song added: $title\n";
    }

    public function listSongs() {
        if (empty($this->songs)) {
            echo "No songs in catalogue.\n";
            return;
        }
        foreach ($this->songs as $i => $song) {
            echo ($i+1) . ". " . $song . "\n";
        }
    }

    public function searchSong($keyword) {
        $results = array_filter($this->songs, function($s) use ($keyword) {
            return stripos($s->title, $keyword) !== false ||
                   stripos($s->artist, $keyword) !== false ||
                   stripos($s->album, $keyword) !== false ||
                   stripos($s->genre, $keyword) !== false;
        });
        if (empty($results)) {
            echo "No matches found.\n";
        } else {
            foreach ($results as $song) {
                echo $song . "\n";
            }
        }
    }

    public function deleteSong($title) {
        foreach ($this->songs as $i => $song) {
            if (strcasecmp($song->title, $title) === 0) {
                unset($this->songs[$i]);
                $this->songs = array_values($this->songs);
                $this->saveData();
                echo "Deleted song: $title\n";
                return;
            }
        }
        echo "Song not found.\n";
    }

    public function createPlaylist($name) {
        if (!isset($this->playlists[$name])) {
            $this->playlists[$name] = [];
            $this->saveData();
            echo "Playlist created: $name\n";
        } else {
            echo "Playlist already exists.\n";
        }
    }

    public function addToPlaylist($playlist, $title) {
        foreach ($this->songs as $song) {
            if (strcasecmp($song->title, $title) === 0) {
                $this->playlists[$playlist][] = $song->title;
                $this->saveData();
                echo "Added $title to $playlist\n";
                return;
            }
        }
        echo "Song not found.\n";
    }

    public function listPlaylists() {
        foreach ($this->playlists as $name => $songs) {
            echo "Playlist: $name\n";
            foreach ($songs as $s) {
                echo "  - $s\n";
            }
        }
    }

    public function playSong($title) {
        foreach ($this->songs as $song) {
            if (strcasecmp($song->title, $title) === 0) {
                echo "Now playing: $song\n";
                return;
            }
        }
        echo "Song not found.\n";
    }

    public function help() {
        echo "Commands:\n";
        echo " add-song <title> <artist> <album> <genre> <duration>\n";
        echo " list-songs\n";
        echo " search-song <keyword>\n";
        echo " delete-song <title>\n";
        echo " create-playlist <name>\n";
        echo " add-to-playlist <playlist> <title>\n";
        echo " list-playlists\n";
        echo " play <title>\n";
        echo " help\n";
        echo " exit\n";
    }
}

// CLI loop
$app = new MusicApp();
echo "Welcome to CLI Music App!\nType 'help' for commands.\n";

while (true) {
    echo "> ";
    $input = trim(fgets(STDIN));
    $parts = explode(" ", $input);
    $command = strtolower(array_shift($parts));

    switch ($command) {
        case 'add-song':
            if (count($parts) < 5) { echo "Usage: add-song <title> <artist> <album> <genre> <duration>\n"; break; }
            $app->addSong($parts[0], $parts[1], $parts[2], $parts[3], $parts[4]);
            break;
        case 'list-songs': $app->listSongs(); break;
        case 'search-song': $app->searchSong(implode(" ", $parts)); break;
        case 'delete-song': $app->deleteSong(implode(" ", $parts)); break;
        case 'create-playlist': $app->createPlaylist(implode(" ", $parts)); break;
        case 'add-to-playlist':
            if (count($parts) < 2) { echo "Usage: add-to-playlist <playlist> <title>\n"; break; }
            $app->addToPlaylist($parts[0], implode(" ", array_slice($parts,1)));
            break;
        case 'list playlists': $app->listPlaylists(); break;
        case 'play': $app->playSong(implode(" ", $parts));
        case 'help': $app->help(); break;
        case 'exit': exit("See you next time!\n");
        default: echo "Unknown command. Type 'help'\n";
    }
}