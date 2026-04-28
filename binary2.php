<?php
class Treenode{
    public $data;
    public $right;
    public $left;

    public function __construct($data)
    {
        $this->data = $data;
        $this->right = null;
        $this->left = null;
    }
    public function inorderrec($root){
        if ($root === null){
            return []; }
        return array_merge(
            $this->inorderrec($root->left), [$root->data], $this->inorderrec($root->right)
        );
    }
    public function invert($root){
        if($root === null){
            return null;
        }
        // Swap children
        $temp = $root->left;
        $root->left = $root->right;
        $root->right = $temp;

        //Recurse down on both sides
        $this->invert($root->left);
        $this->invert($root->right);

        return $root; 
    }
}
$root = new Treenode(5);
$root->left = new Treenode(3);
$root->right = new Treenode(7);
$root->left->left = new Treenode(2);
$root->left->right = new Treenode(4);
$root->right->left = new Treenode(6);
$root->right->right = new Treenode(8);

$tree = new Treenode(null); // just to call methods

// Test inorder (recursive)
echo "Inorder Recursive: ";
print_r($tree->inorderrec($root));

// Test invertion
echo "Inversion Recursive: ";
print_r($tree->invert($root));