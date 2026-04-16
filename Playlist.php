<?php
class SongNode {
    public $title;
    public $next;
    public $prev;

    public function __construct($title) {
        $this->title = $title;
        $this->next = null;
        $this->prev = null;
    }
}

class Playlist {
    private $head = null;
    private $current = null;

    // Add song at the end
    public function addSong($title) {
        $newSong = new SongNode($title);

        if ($this->head === null) {
            $this->head = $newSong;
            $this->current = $newSong;
            return;
        }

        $last = $this->head;
        while ($last->next !== null) {
            $last = $last->next;
        }

        $last->next = $newSong;
        $newSong->prev = $last;
    }

    // Play current song
    public function playCurrent() {
        if ($this->current !== null) {
            echo "Playing: " . $this->current->title . "\n";
        } else {
            echo "No songs in playlist.\n";
        }
    }

    // Move to next song
    public function nextSong() {
        if ($this->current !== null && $this->current->next !== null) {
            $this->current = $this->current->next;
            $this->playCurrent();
        } else {
            echo "End of playlist.\n";
        }
    }

    // Move to previous song
    public function prevSong() {
        if ($this->current !== null && $this->current->prev !== null) {
            $this->current = $this->current->prev;
            $this->playCurrent();
        } else {
            echo "Start of playlist.\n";
        }
    }

    // Show all songs
    public function showPlaylist() {
        $song = $this->head;
        echo "Playlist:\n";
        while ($song !== null) {
            echo "- " . $song->title . "\n";
            $song = $song->next;
        }
    }
}

// Usage
$playlist = new Playlist();
$playlist->addSong("Song A");
$playlist->addSong("Song B");
$playlist->addSong("Song C");

$playlist->showPlaylist();

$playlist->playCurrent();   // Playing Song A
$playlist->nextSong();      // Playing Song B
$playlist->nextSong();      // Playing Song C
$playlist->prevSong();      // Back to Song B
