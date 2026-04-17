<?php
// music_app.php
// Run: php music_app.php
//Created using Indexed and assoiciative arrays

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
        $this->genre = $data['genre']??"Unknown genre";
        $this->duration = $data['duration']?? "Assumed 3:00";
    }

    public function __toString() {
        return "{$this->title} by {$this->artist} ({$this->album}, {$this->genre}, {$this->duration} mins)";
    }
}
//Insertion of linked lists
class PlaylistNode {
    public $title;
    public $next;
    public $prev;

    public function __construct($title) {
        $this->title = $title;
        $this->next = null;
        $this->prev = null;
    }
}
class LinkedPlaylist {
    public $name;
    private $head = null;
    private $tail = null;

    public function __construct($name) {
        $this->name = $name;
    }

    // Add song at the end
    public function addSong($title) {
        $newNode = new PlaylistNode($title);
        if ($this->head === null) {
            $this->head = $newNode;
            $this->tail = $newNode;
        } else {
            $this->tail->next = $newNode;
            $newNode->prev = $this->tail;
            $this->tail = $newNode;
        }
    }

    // Remove song by title
    public function removeSong($title) {
        $current = $this->head;
        while ($current !== null) {
            if (strcasecmp($current->title, $title) === 0) {
                if ($current->prev !== null) {
                    $current->prev->next = $current->next;
                } else {
                    $this->head = $current->next;
                }
                if ($current->next !== null) {
                    $current->next->prev = $current->prev;
                } else {
                    $this->tail = $current->prev;
                }
                echo "Removed '$title' from playlist '{$this->name}'.\n";
                return;
            }
            $current = $current->next;
        }
        echo "Song '$title' not found in playlist '{$this->name}'.\n";
    }

    // Show playlist songs
    public function showSongs() {
        $current = $this->head;
        echo "Playlist '{$this->name}':\n";
        while ($current !== null) {
            echo "- {$current->title}\n";
            $current = $current->next;
        }
    }

    // Traverse forward
    public function playForward() {
        $current = $this->head;
        while ($current !== null) {
            echo "Playing: {$current->title}\n";
            $current = $current->next;
        }
    }

    // Traverse backward
    public function playBackward() {
        $current = $this->tail;
        while ($current !== null) {
            echo "Playing: {$current->title}\n";
            $current = $current->prev;
        }
    }
    public function toArray() {
        $songs = [];
        $current = $this->head;
        while ($current !== null) {
            $songs[] = $current->title;
            $current = $current->next;
        }
        return $songs;
    }
    public static function fromArray($name, $songs) {
        $playlist = new LinkedPlaylist($name);
        foreach ($songs as $title) {
            $playlist->addSong($title);
        }
        return $playlist;
    }
    public function findSong($title) {
        $current = $this->head;
        while ($current !== null) {
            if (strcasecmp($current->title, $title) === 0) {
                return $current;
            }
            $current = $current->next;
        }
        return null;
    }
    
    
}

class MusicApp {
    private $songs = [];
    private $playlists = [];
    private $storageFile = "music_data.json"; //stores songs so that even if you exit, the songs are still there
    //automatically creates a place to store the songs automatically when you add a song
    
    //Fields to track playback
    private $currentSong = null;
    private $currentPlaylist = null;
    private $currentIndex = -1;
    private $isPaused = false;
    private $repeat = false;
    private $shuffle = false;
    private $playlistIndex = -1;
    private $elapsedBeforePause = 0;
    private $queue = [];
    private $currentNode = null; // track current song node in a playlist

    

    

    public function getSongs() {
        return $this->songs;
    }
    
    
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
                foreach ($data['playlists'] as $name => $songs) {
                    $this->playlists[$name] = LinkedPlaylist::fromArray($name, $songs);
                }
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
            'playlists' => array_map(function($plist) {
                return $plist->toArray();
            }, $this->playlists)
        ];
        file_put_contents($this->storageFile, json_encode($data, JSON_PRETTY_PRINT));
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

  public function searchSong($criteria) {
    // $criteria is an associative array from parseInput
    $results = array_filter($this->songs, function($s) use ($criteria) {
        $match = true;
        foreach ($criteria as $key => $value) {
            if (property_exists($s, $key)) {
                if (stripos($s->$key, $value) === false) {
                    $match = false;
                    break;
                }
            }
        }
        return $match;
    });
        if (empty($results)) {
            echo "No matches found.\n";
        } else {
            foreach ($results as $song) {
                echo $song . "\n";
            }
        }
    }
    public function queueSong($title) {
        if ($this->currentPlaylist) {
            // Playlist mode queue
            $this->queue[] = $title;
            echo "Queued '$title' in playlist '{$this->currentPlaylist}'.\n";
        } else {
            // Global catalogue queue
            foreach ($this->songs as $song) {
                if (strcasecmp($song->title, $title) === 0) {
                    $this->queue[] = $song->title;
                    echo "Queued '{$song->title}' to play next.\n";
                    return;
                }
            }
            echo "Song '$title' not found in catalogue.\n";
        }
    }
    
public function showQueue() {
    if (empty($this->queue)) {
        echo "Queue is empty.\n";
        return;
    }
    echo "Queued songs:\n";
    foreach ($this->queue as $i => $title) {
        echo ($i+1) . ". $title\n";
    }
}

public function clearQueue() {
    $this->queue = [];
    echo "Queue cleared.\n";
}

public function deleteSong($title, $artist) {
    if ($title === null && $artist === null) {
        echo "Error: You must provide at least a title or an artist.\n";
        return;
    }

    foreach ($this->songs as $i => $song) {
        $match = false;

        if ($title !== null && $artist !== null) {
            $match = (strcasecmp((string)$song->title, (string)$title) === 0 &&
                      strcasecmp((string)$song->artist, (string)$artist) === 0);
        } elseif ($title !== null) {
            $match = (strcasecmp((string)$song->title, (string)$title) === 0);
        } elseif ($artist !== null) {
            $match = (strcasecmp((string)$song->artist, (string)$artist) === 0);
        }

        if ($match) {
            // ✅ Ask for confirmation before deleting
            echo "Are you sure you want to delete '{$song->title}' by '{$song->artist}'? (Y/N): ";
            $confirmation = trim(fgets(STDIN));

            if (strcasecmp($confirmation, "Y") === 0) {
                unset($this->songs[$i]);
                $this->songs = array_values($this->songs);

                // ✅ Remove this song from all playlists
                foreach ($this->playlists as $pname => $plist) {
                    $plist->removeSong($song->title);
                }
                

                $this->saveData();
                echo "Deleted song: {$song->title} by {$song->artist}\n";
            } else {
                echo "Cancelled. Song '{$song->title}' was not deleted.\n";
            }
            return;
        }
    }

    echo "Song not found.\n";
}

    public function createPlaylist($name) {
        if (!isset($this->playlists[$name])) {
            $this->playlists[$name] = new LinkedPlaylist($name);
            $this->saveData(); // ✅ persist immediately
            echo "Playlist created: $name\n";
    }   else {
            echo "Playlist already exists.\n";
    }
}

    public function addToPlaylist($playlist, $title) {
        if (!isset($this->playlists[$playlist])) {
            echo "Playlist '$playlist' does not exist.\n";
        return;
        }

        foreach ($this->songs as $song) {
             if (strcasecmp($song->title, $title) === 0) {
                 $this->playlists[$playlist]->addSong($song->title);
                 $this->saveData(); // ✅ persist updated playlist
                 echo "Added '{$song->title}' to playlist '$playlist'\n";
                 return;
            }
         }
         echo "Song '$title' not found in catalogue.\n";
}


        foreach ($this->songs as $song) {
            if (strcasecmp($song->title, $title) === 0) {
                // ✅ Add the song title to the playlist
                $this->playlists[$playlist][] = $song->title;
                $this->saveData();
                echo "Added '{$song->title}' to playlist '$playlist'\n";
                return;
        }
    }
    echo "Song '$title' not found in catalogue.\n";
}

public function playlistSession() {
    if (empty($this->playlists)) {
        echo "No playlists available.\n";
        return;
    }

    echo "Playlists:\n";
    foreach (array_keys($this->playlists) as $i => $name) {
        echo ($i+1) . ". $name\n";
    }

    echo "Type the playlist name to open it, or 'exit' to leave playlist mode.\n";

    while (true) {
        echo "playlist> ";
        $input = trim(fgets(STDIN));

        if (strcasecmp($input, "exit") === 0) {
            echo "Leaving playlist mode.\n";
            break;
        }

        if (!isset($this->playlists[$input])) {
            echo "Playlist not found. Try again.\n";
            continue;
        }

        $this->playlistMenu($input);
    }
}

private function playlistMenu($name) {
    $plist = $this->playlists[$name];

    echo "Opened playlist: $name\n";
    $plist->showSongs();   // traverses nodes and prints titles
    

    echo "Commands inside playlist:\n";
    echo " play <song title>   (play a song by its name)\n";
    echo " queue <song title>   (add a song to play next)\n";
    echo " show-queue           (list queued songs)\n";
    echo " clear-queue          (empty the queue)\n";

    echo " remove <song title> (remove a song from this playlist)\n";
    echo " show                (re-list songs in this playlist)\n";
    echo " back                (return to playlist list)\n";

    while (true) {
        echo "[$name]> ";
        $input = trim(fgets(STDIN));
        $parts = explode(" ", $input);
        $command = strtolower(array_shift($parts));

        switch ($command) {
            case 'play':
                $plist = $this->playlists[$name];
            
                if (!empty($this->queue)) {
                    $title = array_shift($this->queue);
                    $this->playSong($title);
                } elseif (empty($parts)) {
                    // play first song in playlist
                    $this->currentPlaylist = $name;
                    $this->currentNode = $plist->findSong($plist->toArray()[0]); 
                    $this->playSong($this->currentNode->title);
                } else {
                    $title = implode(" ", $parts);
                    $node = $plist->findSong($title);
                    if ($node) {
                        $this->currentPlaylist = $name;
                        $this->currentNode = $node;
                        $this->playSong($title);
                    } else {
                        echo "Song '$title' not found in playlist '$name'.\n";
                    }
                }
                break;       
            case 'queue':
                if (empty($parts)) {
                    echo "Usage: queue <song title>\n";
                    break;
                }
                $title = implode(" ", $parts);
                if (in_array($title, $songs)) {
                    $this->queueSong($title);
                } else {
                    echo "Song '$title' not found in playlist '$name'.\n";
                }
                break;
            
            case 'show-queue':
                $this->showQueue();
                break;
            
            case 'clear-queue':
                $this->clearQueue();
                break;
            case 'remove':
                if (empty($parts)) {
                    echo "Usage: remove <song title>\n";
                    break;
                }
                $title = implode(" ", $parts);
                $plist = $this->playlists[$name];
                $plist->removeSong($title);
                $this->saveData();
                break;
            case 'show':
                echo "Songs in '$name':\n";
                foreach ($songs as $t) {
                    echo "  - $t\n";
                }
                break;

            case 'back':
                return;

            default:
                echo "Unknown command inside playlist. Use 'play <song title>' or 'back'.\n";
        }
    }
}

public function listPlaylists() {
    if (empty($this->playlists)) {
        echo "No playlists available.\n";
        return;
    }

    foreach ($this->playlists as $name => $plist) {
        $plist->showSongs(); // ✅ linked list traversal
    }
}


public function deletePlaylist($name) {
    if (!isset($this->playlists[$name])) {
        echo "Playlist not found.\n";
        return;
    }

    echo "Are you sure you want to delete the playlist '$name'? (Y/N): ";
    $confirmation = trim(fgets(STDIN));

    if (strcasecmp($confirmation, "Y") === 0) {
        unset($this->playlists[$name]);
        $this->saveData();
        echo "Deleted playlist: $name\n";
    } else {
        echo "Cancelled. Playlist '$name' was not deleted.\n";
    }
}

public function pause() {
    if ($this->currentSong && !$this->isPaused) {
        $this->isPaused = true;
        $this->elapsedBeforePause += time() - $this->startTime;
        $this->startTime = null; // stop the clock
        echo "Paused: {$this->currentSong->title}\n";
    } else {
        echo "No song is playing or already paused.\n";
    }
}
public function resume() {
    if ($this->currentSong && $this->isPaused) {
        $this->isPaused = false;
        $this->startTime = time(); // restart clock
        echo "Resumed: {$this->currentSong->title}\n";
    } else {
        echo "No song is paused.\n";
    }
}


public function stop() {
    if ($this->currentSong) {
        echo "Stopped: {$this->currentSong->title}\n";
        $this->currentSong = null;
        $this->currentIndex = -1;
    } else {
        echo "No song is playing.\n";
    }
}

public function nextSong() {
    if (!empty($this->queue)) {
        $title = array_shift($this->queue);
        $this->playSong($title);
        return;
    }

    if ($this->currentPlaylist) {
        // Playlist mode using linked list
        if ($this->shuffle) {
            // Shuffle: pick a random node
            $plist = $this->playlists[$this->currentPlaylist];
            $titles = $plist->toArray(); // snapshot of titles
            if (!empty($titles)) {
                $title = $titles[array_rand($titles)];
                $this->currentNode = $plist->findSong($title);
                $this->playSong($title);
            } else {
                echo "This playlist is empty.\n";
            }
        } else {
            // Sequential traversal
            if ($this->currentNode && $this->currentNode->next) {
                $this->currentNode = $this->currentNode->next;
                $this->playSong($this->currentNode->title);
            } else {
                if ($this->repeat) {
                    // loop back to head
                    $plist = $this->playlists[$this->currentPlaylist];
                    $this->currentNode = $plist->findSong($plist->toArray()[0]);
                    $this->playSong($this->currentNode->title);
                } else {
                    echo "End of playlist.\n";
                }
            }
        }
    } else {
        // Global catalogue mode (already correct)
        if (empty($this->songs)) {
            echo "No songs in catalogue.\n";
            return;
        }

        $this->currentIndex++;
        if ($this->currentIndex >= count($this->songs)) {
            if ($this->repeat) {
                $this->currentIndex = 0;
            } else {
                echo "End of catalogue.\n";
                return;
            }
        }
        $title = $this->songs[$this->currentIndex]->title;
        $this->playSong($title);
    }
}

public function previousSong() {
    if ($this->currentPlaylist) {
        // Playlist mode using linked list
        $plist = $this->playlists[$this->currentPlaylist];

        if ($this->shuffle) {
            // Shuffle: pick a random node
            $titles = $plist->toArray(); // snapshot of titles
            if (!empty($titles)) {
                $title = $titles[array_rand($titles)];
                $this->currentNode = $plist->findSong($title);
                $this->playSong($title);
            } else {
                echo "This playlist is empty.\n";
            }
        } else {
            // Sequential traversal backwards
            if ($this->currentNode && $this->currentNode->prev) {
                $this->currentNode = $this->currentNode->prev;
                $this->playSong($this->currentNode->title);
            } else {
                if ($this->repeat) {
                    // loop back to tail
                    $titles = $plist->toArray();
                    if (!empty($titles)) {
                        $lastTitle = end($titles);
                        $this->currentNode = $plist->findSong($lastTitle);
                        $this->playSong($this->currentNode->title);
                    } else {
                        echo "This playlist is empty.\n";
                    }
                } else {
                    echo "Start of playlist.\n";
                }
            }
        }
    } else {
        // Global catalogue mode (unchanged)
        if (empty($this->songs)) {
            echo "No songs in catalogue.\n";
            return;
        }

        $this->currentIndex--;
        if ($this->currentIndex < 0) {
            if ($this->repeat) {
                $this->currentIndex = count($this->songs) - 1;
            } else {
                echo "Start of catalogue.\n";
                return;
            }
        }
        $title = $this->songs[$this->currentIndex]->title;
        $this->playSong($title);
    }
}

public function toggleShuffle() {
    $this->shuffle = !$this->shuffle;
    echo "Shuffle " . ($this->shuffle ? "ON" : "OFF") . "\n";
}

public function toggleRepeat() {
    $this->repeat = !$this->repeat;
    echo "Repeat " . ($this->repeat ? "ON" : "OFF") . "\n";
}

private function nowPlayingSession() {
    if (!$this->currentSong) {
        echo "No song is currently playing.\n";
        return;
    }

    echo "Commands: progress | pause | resume | stop | next | queue | previous | shuffle | repeat | exit\n";

    // Enable non-blocking input
    stream_set_blocking(STDIN, false);

    while (true) {
        // Update progress every second
        $this->simulateProgress();
  


        // Check for user input without blocking
        $input = fgets(STDIN);
        if ($input !== false) {
            $input = trim($input);
            $parts = explode(" ", $input);
            $command = strtolower(array_shift($parts));

            switch ($command) {
                case 'progress': $this->simulateProgress(true); break;
                case 'pause': $this->pause(); break;
                case 'resume': $this->resume(); break;
                case 'stop': $this->stop(); stream_set_blocking(STDIN, true); return;
                case 'next': $this->nextSong(); break;
                case 'previous': $this->previousSong(); break;
                case 'shuffle': $this->toggleShuffle(); break;
                case 'repeat': $this->toggleRepeat(); break;
                case 'queue':
                    if (!empty($parts)) {
                        $this->queueSong(implode(" ", $parts));
                    } else {
                        echo "Usage: queue <title>\n";
                    }
                    break;
                case 'exit': echo "Leaving Now Playing.\n"; stream_set_blocking(STDIN, true); return;
                default: if ($command !== '') echo "Unknown command.\n";
            }
        }

        sleep(1); // refresh every second
    }
}


private $startTime = null;

private function simulateProgress($force = false) {
    if (!$this->currentSong) return;

    // Extract duration safely
    $duration = $this->currentSong->duration ?? "3:00";
    if (preg_match('/^(\d+):(\d+)$/', $duration, $matches)) {
        $min = (int)$matches[1];
        $sec = (int)$matches[2]; // fallback 3:00
    } else {
        $min = 3;
        $sec = 0;
    }
    $totalSeconds = ($min * 60) + $sec;

    // Calculate elapsed
    $elapsed = $this->elapsedBeforePause;
    if (!$this->isPaused && $this->startTime) {
        $elapsed += time() - $this->startTime;
    }

    if ($elapsed > $totalSeconds) {
        echo "\nSong finished: {$this->currentSong->title}\n";
        $this->elapsedBeforePause = 0;
        $this->currentSong = null;
    
        // Auto‑advance if in a playlist
        if ($this->currentPlaylist) {
            $this->nextSong();
        }
        return;
    }
    

    $m = floor($elapsed / 60);
    $s = str_pad($elapsed % 60, 2, "0", STR_PAD_LEFT);

    echo "\r♪ $m:$s / {$this->currentSong->duration}";
    if ($force) echo "\n";
}





public function playSong($title = null) {
    if ($title === null && !empty($this->queue)) {
        $title = array_shift($this->queue);
    }
    foreach ($this->songs as $i => $song) {
        if (strcasecmp($song->title, $title) === 0) {
            $this->currentSong = $song;
            $this->currentIndex = $i;
            $this->isPaused = false;
            $this->startTime = time();
            $this->elapsedBeforePause = 0;

            // If we’re inside a playlist, sync playlistIndex
            if ($this->currentPlaylist) {
                $songs = $this->playlists[$this->currentPlaylist];
                $this->playlistIndex = array_search($title, $songs);
            }
            echo "♪ Now Playing: $song\n";
            $this->nowPlayingSession();
            return;
        }
    }
    echo "Song not found.\n";
}



public function deleteAllSongs() {
    echo "Are you sure you want to delete ALL songs? (Y/N): ";
    $confirmation = trim(fgets(STDIN));

    if (strcasecmp($confirmation, "Y") === 0) {
        $count = count($this->songs);
        $this->songs = []; // clear the array
        $this->playlists = [];//clear playlist too
        $this->saveData(); // update the JSON file
        echo "Deleted all $count songs from catalogue.\n";
    } else {
        echo "Cancelled. No songs were deleted.\n";
    }
}


    public function help() {
        echo "Commands:\n";
        echo "\n";
        echo " add-song title=\"Hello\" artist=\"Adele\" album=\"25\" genre=\"Pop\" duration=\"4:00\"\n";
        echo "\n";
        echo " list-songs\n";
        echo "\n";
        echo " queue <title> (add a song to play next)\n";
        echo " show-queue           (list queued songs)\n";
        echo " clear-queue          (empty the queue)\n";
        echo "\n";
        echo " search-song title=\"<title>\" artist=\"<artist>\" album=\"<album>\" genre=\"<genre>\"\n";
        echo "   (You can search by one or multiple fields)\n";

        echo "\n";
        echo " delete-song title=\"<title>\" artist=\"<artist>\"\n";
        echo "   (You can also delete by just title OR just artist if one is missing)\n";
        echo "\n";
        echo " create-playlist <name>\n";
        echo " add-to-playlist <playlist> <title>\n";
        echo " list-playlists\n";
        echo " delete-playlist <name>   (remove a playlist, asks for Y/N confirmation)\n";
        echo "\n";
        echo " play <title>\n";
        echo "\n";
        echo " help\n";
        echo " exit\n";
        echo "\n";
        echo " delete-all-songs   (remove every song from the catalogue,  asks for Y/N confirmation)\n";
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
        case 'search-song':
            $data = parseInput($input);
            if (empty($data)) {
                echo "Usage: search-song title=\"<title>\" artist=\"<artist>\" album=\"<album>\" genre=\"<genre>\"\n";
                echo "   (You can provide one or more fields)\n";
                break;
            }
            $app->searchSong($data);
            break;

        case 'delete-song':
            $data = parseInput($input);
            $title = $data['title'] ?? null;
            $artist = $data['artist'] ?? null;

            if ($title === null && $artist === null) {
                echo "Usage: delete-song title=\"<title>\" artist=\"<artist>\"\n";
                echo "   (You can also delete by just title OR just artist)\n";
                break;
            }

            $app->deleteSong($title, $artist);
            break;
        case 'queue':
              if (empty($parts)) {
                    echo "Usage: queue <title>\n";
                    break;
              }
              $app->queueSong(implode(" ", $parts));
              break;
        case 'show-queue': $app->showQueue(); break;
        case 'clear-queue': $app->clearQueue(); break;
              
            
        case 'create-playlist': $app->createPlaylist(implode(" ", $parts)); break;
        case 'add-to-playlist':
            if (count($parts) < 2) { echo "Usage: add-to-playlist <playlist> <title>\n"; break; }
            $app->addToPlaylist($parts[0], implode(" ", array_slice($parts,1)));
            break;
        case 'list-playlists': 
            $app->playlistSession(); 
            break;

        case 'play':
            if (empty($parts)) {
                $songs = $app->getSongs();
                if (!empty($songs)) {
                    $firstTitle = $songs[0]->title;
                    $app->playSong($firstTitle);
                } else {
                    echo "No songs in catalogue.\n";
                }
            } else {
                $app->playSong(implode(" ", $parts));
            }
            break;
            
            
        case 'delete-all-songs':
           $app->deleteAllSongs();
           break;
        case 'delete-playlist':
            if (empty($parts)) {
                echo "Usage: delete-playlist <name>\n";
                break;
            }
            $app->deletePlaylist(implode(" ", $parts));
            break;
        case 'help': $app->help(); break;
        case 'exit': exit("See you next time!\n");
        default: echo "Unknown command. Type 'help'\n";
    }
}