<?php
//Singly linked list
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
    private $next;

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
    public function delete() {
    $current = $this->head;

    // Outer loop-pick each node one by one
    while ($current !== null) {
        $runner = $current;

        // Inner loop scans ahead for duplicates of current->data
        while ($runner->next !== null) {
            if ($runner->next->data === $current->data) {
                // Duplicate found, should skip it
                $runner->next = $runner->next->next;
            } else {
                $runner = $runner->next;
            }
        }

        // Move to next distinct value
        $current = $current->next;
    }
}


}



// Usage
$list = new LinkedList();
$list->insert(1);
$list->insert(1);
$list->insert(1);
$list->insert(2);
$list->insert(2);
$list->insert(2);
$list->insert(3);
$list->insert(3);
$list->insert(3);
$list->insert(1);

echo "Linked list: \n";
$list->traverse();
echo "\n";
$list->delete(1);
$list->delete(2);
$list->delete(3);
echo "Deleted Linked List: \n";
$list->traverse();

//Double linked list
class Doublenode {
    public $data;
    public $next;
    public $prev;

    public function __construct($data){
        $this->data = $data;
        $this->prev = NULL;
        $this->next = NULL;
    }
}
class DoublyLinkedList {
    private $head;

    public function __construct() {
        $this->head = null;
    }

    public function insert($data) {
        $newNode = new Doublenode($data);
        $newNode->next = $this->head;
        if ($this->head !== null) {
            $this->head->prev = $newNode;
        }
        $this->head = $newNode;
    }

    public function append($data) {
        $newNode = new Doublenode($data);
        $newNode->next = null; //set like that since it will be the last node

        if ($this->head === null) {
            $newNode->prev = null;
            $this->head = $newNode;
            return; //checks if list is empty, if there is no head, then the new node becomes the head
        }

        $last = $this->head;
        while ($last->next !== null) {
            $last = $last->next; //find the last node
        }

        $last->next = $newNode;
        $newNode->prev = $last;//attach the new node at the end
    }

    public function delete($data) {
        $current = $this->head;

        //Seach for the node with matching data
        while ($current !== null && $current->data !== $data) {
            $current = $current->next;
        }

        //If we reached the end without finding it, just return
        if ($current === null) {
            return;
        }
        //fix the link from the previous node
        if ($current->prev !== null) {
            $current->prev->next = $current->next;
        } else {
            //If there's no previous,it means we're deleteing the head.
            $this->head = $current->next;
        }
         // Step 4: Fix the link from the next node
        if ($current->next !== null) {
            $current->next->prev = $current->prev;
        }
    }

    public function display() {
        $current = $this->head;
        while ($current !== null) {
            echo $current->data . " ";
            $current = $current->next;
        }
    }
}

$doublyList = new DoublyLinkedList();
$doublyList->insert(3);
$doublyList->insert(7);
$doublyList->insert(10);
$doublyList->append(5);
echo "\n";
$doublyList->display(); // Output: 10 7 3 5
$doublyList->delete(7);
echo "\n";
$doublyList->display(); // Output: 10 3 5

//Circular linked lists
class Node {
    public $data;
    public $next;

    public function __construct($data) {
        $this->data = $data;
        $this->next = null;
    }
}


