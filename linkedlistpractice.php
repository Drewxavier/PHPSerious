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
    public function delete($data){
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

echo "\n";
$list->delete(2);
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
<?php
class Node {
    public $data;
    public $next;

    public function __construct($data) {
        $this->data = $data;
        $this->next = null;
    }
}

class CircularLinkedList {
    private $head = null;

    // Append a new node at the end
    public function append($data) {
        $newNode = new Node($data);

        if ($this->head === null) {
            // First node points to itself
            $this->head = $newNode;
            $newNode->next = $this->head;
            return;
        }

        // Traverse to the last node
        $current = $this->head;
        while ($current->next !== $this->head) {
            $current = $current->next;
        }

        // Link new node
        $current->next = $newNode;
        $newNode->next = $this->head;
    }

    // Traverse the circular list
    public function traverse($count) {
        if ($this->head === null) {
            echo "List is empty\n";
            return;
        }

        $current = $this->head;
        $i = 0;
        // To avoid infinite loop, limit traversal by count
        while ($i < $count) {
            echo $current->data . " ";
            $current = $current->next;
            $i++;
        }
        echo "\n";
    }
}

// Usage
$list = new CircularLinkedList();
$list->append(1);
$list->append(2);
$list->append(3);

echo "Circular linked list (first 6 elements shown):\n";
$list->traverse(6); // prints 1 2 3 1 2 3
