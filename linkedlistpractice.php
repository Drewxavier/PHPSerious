<?php
//Singly linked list
// Node for the main linked list
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
    public function delete($k) {
    $dummy = new \stdClass();
    $dummy->next = $this->head;
    $fast = $dummy;
    $slow = $dummy;
    $steps = 0;

    // Single loop: advance fast, then move slow once fast is k ahead
    while ($fast->next !== null) {
        $fast = $fast->next;
        $steps++;

        if ($steps > $k) {
            $slow = $slow->next;//when steps are greater than the kth number, that's when it moves
        }
    }

    // Now slow->next is the k-th node from the end
    if ($steps >= $k && $slow->next !== null) {
        $slow->next = $slow->next->next;
    }

    $this->head = $dummy->next;
}

}

$list = new LinkedList();
$list->insert(5);
$list->insert(7);
$list->insert(6);
$list->insert(9);
$list->insert(8);
$list->insert(3);
$list->insert(2);
$list->insert(87);
$list->insert(36);
$list->insert(11);

echo "Linked list: \n";
$list->traverse();
echo "\n";

//Delete the 1st node from end (last element)
$list->delete(1);
echo "After deleting 1st from end: \n";
$list->traverse();
//Delete the 3st node from end (last element)
$list->delete(3);
echo "After deleting 3st from end: \n";
$list->traverse();

//Delete the 5st node from end (last element)
$list->delete(5);
echo "After deleting 5st from end: \n";
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


