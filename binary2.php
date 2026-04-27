<?php 
class bts{
    public $data;
    public $left;
    public $right;
    public function __construct($root)
    {
        $this->pushleft($root);
    }
    private function pushleft($node){
        while ($node !== null){
            $this->stack[] = $node;
            $node = $node->left
        }
    }
    
    //return the next smallest element
    public function next() {
        $node = array_pop($this->stack);
        $val = $node->data;

        // If there's a right child, push its left path
        if ($node->right !== null) {
            $this->pushLeft($node->right);
        }

        return $val;
    }
    // Check if there are more elements
    public function hasNext() {
        return !empty($this->stack);
    }
}
$root = new bts(5);
$root->left = new bts(3);
$root->right = new bts(7);
$root->left->left = new bts(2);
$root->left->right = new bts(4);
$root->right->left = new bts(6);
$root->right->right = new bts(8);

$tree = new bts(null);

// Test inorder (recursive)
echo "Test: ";
print_r($tree->next($root));