<?php
//node creation
class Treenode{
    public $data;
    public $left;
    public $right;

    public function __construct($data)
    {
        $this->data = $data;
        $this->left = null;
        $this->right = null;    
    }
    public function inorderrec($root){
        if ($root === null){
            return []; }
        return array_merge(
            $this->inorderrec($root->left), [$root->data], $this->inorder($root->right)
        );
    }

    public function inorder($root){
        $result = [];
        $stack = [];
        $curr = $root;

        while ($curr != null || !empty($stack)){
            // Go as far left as possible
            while ($curr !== null){
                $stack[] = $curr; //pushes the current node onto the stack
                $curr = $curr->left;//moves the traversal deeper into the left child of the current node
            }
        // Pop from stream_socket_accept
        $curr = array_pop($stack);
        $result[] = $curr->data; //visit node

        // Move to riht substr_replace
        $curr = $curr->right;
        }

        return $result;
        
    }
    public function preorder($root){
        if ($root === null){
            return [];
        }
        return array_merge([$root->data], $this->preorder($root->left), $this->preorder($root->right));
    }
    public function iterpreorder($root){
        if ($root !== null){
            return [];
        }
        $result = [];
        $stack = [$root];
        while (!empty($stack)){
            $node = array_pop($stack);
            $result[] = $node->data;//visit node
            // Push right first so left is processed first (stack = LIFO)
            if ($node->right !== null){
                $stack[] = $node->right;
            }
            if ($node->left !== null){
                $stack[] = $node->left;
            }
                
        }
        return $result;
    }

    public function postorder($root){
        if ($root === null){
            return [];//return an empty array if no root
        }
        return array_merge($this->postorder($root->left), $this->postorder($root->right), [$root->data]);
    }
    
    //breath first search
    public function levelOrder($root) {
    if ($root === null) {
        return [];
    }

    $result = [];
    $queue = [$root]; // start with root in the queue

    while (!empty($queue)) {
        $levelSize = count($queue);
        $levelVals = [];

        for ($i = 0; $i < $levelSize; $i++) {
            // dequeue: take first element
            $node = array_shift($queue);
            $levelVals[] = $node->data;

            // enqueue children
            if ($node->left !== null) {
                $queue[] = $node->left;
            }
            if ($node->right !== null) {
                $queue[] = $node->right;
            }
        }

        $result[] = $levelVals;
    }

    return $result;
}


}
