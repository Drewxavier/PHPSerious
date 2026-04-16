<?php
class ListNode {
    public $data;
    public $next;

    public function __construct($data) {
        $this->data = $data;
        $this->next = NULL;
    }
}

class LinkedList {
    private $head = NULL;

    // Insert at end
    public function insert($data) {
        $newNode = new ListNode($data);
        if ($this->head === NULL) {
            $this->head = $newNode;
        } else {
            $current = $this->head;
            while ($current->next !== NULL) {
                $current = $current->next;
            }
            $current->next = $newNode;
        }
    }

    // Traverse and print
    public function traverse() {
        $current = $this->head;
        while ($current !== NULL) {
            echo $current->data . " ";
            $current = $current->next;
        }
        echo "\n";
    }
    public function reverse() {
        $prev = NULL;
        $current = $this->head;
        $next = NULL;
        while ($current !== NULL) {
            //save the next node
            $next = $current->next;

            //Reverse the link
            $current->next = $prev;

            //Move foward
            $prev = $current;
            $current = $next;
        }

        // Update head to the new front
        $this->head = $prev;
    }
    public function delete(){
        if ($this->head === NULL){
            return; // Empty list
        }
        // If head itself holds the data
        if ($this->head->data === $data) {
            $this->head = $this->head->next;
            return;
        }
        // Search for the node to delete
        $current = $this->head;
        while ($current->next !==NULL && $current->next->data !== $data) {
            $current = $current->next;
        }
        //If found, skip over it
        if ($current->next !==NULL) {
            $current->next = $current->next->next;
        }
    }
}

// Usage
$list = new LinkedList();
$list->insert(1);
$list->insert(2);
$list->insert(3);

echo "Linked list: \n";
$list->traverse();
echo "\n";
$list->reverse();
echo "Reversed Linked List: \n";
$list->traverse();
