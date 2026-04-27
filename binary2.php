<?php 
class bts{
    public $data;
    public $left;
    public $right;
    private $stack = [];
    public function __construct($root)
    {
        $this->pushleft($root);
    }
    private function pushleft($node){
        while ($node !== null){
            $this->stack[] = $node;
            $node = $node->left;
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
class TreeNode {
    public $data;
    public $left;
    public $right;

    public function __construct($data) {
        $this->data = $data;
        $this->left = null;
        $this->right = null;
    }
}

function bstFromPreorder($preorder) {
    $index = 0;
    return buildBST($preorder, $index, PHP_INT_MIN, PHP_INT_MAX);
}

function buildBST(&$preorder, &$index, $lower, $upper) {
    if ($index >= count($preorder)) return null;

    $val = $preorder[$index];
    if ($val < $lower || $val > $upper) return null;

    $index++;
    $node = new TreeNode($val);
    $node->left = buildBST($preorder, $index, $lower, $val);
    $node->right = buildBST($preorder, $index, $val, $upper);

    return $node;
}

// Example
$preorder = [8, 5, 1, 7, 10, 12];
$root = bstFromPreorder($preorder);

print_r($root);
echo "\n";

// Build a sample tree
$root = new TreeNode(5);
$root->left = new TreeNode(3);
$root->right = new TreeNode(7);
$root->left->left = new TreeNode(2);
$root->left->right = new TreeNode(4);
$root->right->left = new TreeNode(6);
$root->right->right = new TreeNode(8);

// Create iterator
$iterator = new bts($root);

// Traverse inorder
echo "Inorder traversal: ";
while ($iterator->hasNext()) {
    echo $iterator->next() . " ";
}
