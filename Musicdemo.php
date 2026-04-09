<?php
// music_app.php
// Run: php music_app.php

    function parseInput($line) {
    $data = [];
    // Match key=value pairs, values may be quoted
    preg_match_all('/(\w+)=(".*?"|\S+)/', $line, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $key = $match[1];
        $value = trim($match[2], '"');
        $data[$key] = $value;
    }
    return $data;
}

class Song {
    public $title;//declaring items
    public $artist;
    public $album;
    public $genre;
    public $duration;

    public function __construct($data = []) {
        $this->title = $data['title']?? "No title";
        $this->artist = $data['artist']?? "No Artist";
        $this->album = $data['album']?? "Single";
        $this->genre = $data['genre']??"Unknown";
        $this->duration = $data['duration']?? "Assumed 3:00";
    }

    public function __toString() {
        return "{$this->title} by {$this->artist} ({$this->album}, {$this->genre}, {$this->duration} mins)";
    }
}

class MusicApp {
    private $songs = [];
    private $playlists = [];
    private $storageFile = "music_data.json"; //stores songs so that even if you exit, the songs are still there
    //automatically creates a place to store the songs automatically when you add a song
    public function __construct() {
        $this->loadData();
    }


    private function loadData() {
        if (file_exists($this->storageFile)) {
            $data = json_decode(file_get_contents($this->storageFile), true);
            if ($data) {
                foreach ($data['songs'] as $s) {
                    $this->songs[] = new Song($s);
                }
                $this->playlists = $data['playlists']; //used to resotre the app's playlist array
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
        file_put_contents($this->storageFile, json_encode($data, JSON_PRETTY_PRINT));//takes data array(which contains all songs and playlists), converts it into a JSON string
    }

    public function addSong($data) {
        $song = new Song($data);
        $this->songs[] = $song;
        $this->saveData();
        echo "Song added: {$song->title}\n";
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

    public function deleteSong($title, $artist) {
        foreach ($this->songs as $i => $song) {
            if (strcasecmp($song->title, $title) === 0 &&
                strcasecmp($song->artist, $artist)=== 0) {
                unset($this->songs[$i]);
                $this->songs = array_values($this->songs);
                $this->saveData();
                echo "Deleted song: $title by $artist\n";
                return;
            }
        }
        echo "Song not found.\n";// to make it more advanced, it could go to a dustbin that will delete the song after thirty days to allow for quicker retrival
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
        echo " add-song title=\"Hello\" artist=\"Adele\" album=\"25\" genre=\"Pop\" duration=\"4:00\"\n";
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
echo "Welcome to DrewCLI Music App!\nType 'help' for commands.\n";

while (true) {
    echo "> ";
    $input = trim(fgets(STDIN));
    $parts = explode(" ", $input);
    $command = strtolower(array_shift($parts));

    switch ($command) {
        case 'add-song':
            $data = parseInput($input); // parse full line into key=value array
            $app->addSong($data);
            break;

        case 'list-songs': $app->listSongs(); break;
        case 'search-song': $app->searchSong(implode(" ", $parts)); break;
        case 'delete-song': 
            if (count($parts) < 2){
                echo "Usage: Delete-song <title> <artist>/n";
                break;
            }
            $title = $parts[0];
            $artist= implode(" ", array_slice($parts, 1));
            $app->deleteSong($title,$artist);
            break;
        case 'create-playlist': $app->createPlaylist(implode(" ", $parts)); break;
        case 'add-to-playlist':
            if (count($parts) < 2) { echo "Usage: add-to-playlist <playlist> <title>\n"; break; }
            $app->addToPlaylist($parts[0], implode(" ", array_slice($parts,1)));
            break;
        case 'list-playlists': $app->listPlaylists(); break;
        case 'play': $app->playSong(implode(" ", $parts));
        case 'help': $app->help(); break;
        case 'exit': exit("See you next time!\n");
        default: echo "Unknown command. Type 'help'\n";
    }
}