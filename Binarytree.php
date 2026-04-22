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
        $result[] = $curr->data;

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

}
