<?php
class Treenode {
    public $data;
    public $right;
    public $left;

    public function __construct($data) {
        $this->data = $data;
        $this->right = null;
        $this->left = null;
    }

    // Inorder traversal (recursive)
    public function inorderrec($root) {
        if ($root === null) return [];
        return array_merge(
            $this->inorderrec($root->left),
            [$root->data],
            $this->inorderrec($root->right)
        );
    }

    // Invert binary tree
    public function invert($root) {
        if ($root === null) return null;

        // Swap children
        $temp = $root->left;
        $root->left = $root->right;
        $root->right = $temp;

        // Recurse down both sides
        $this->invert($root->left);
        $this->invert($root->right);

        return $root;
    }

    // Verify if two trees are identical
    public static function verify($root1, $root2) {
        if ($root1 === null && $root2 === null) return true;
        if ($root1 === null || $root2 === null) return false;

        return ($root1->data === $root2->data) &&
               self::verify($root1->left, $root2->left) &&
               self::verify($root1->right, $root2->right);
    }
    public function pathSum($root, $target) {
        if ($root === null) return false;

        // Leaf node check
        if ($root->left === null && $root->right === null) {
            return ($target == $root->data);
        }

        $remaining = $target - $root->data;// goes decreasing and if the node shows in either side then it goes there

        return $this->pathSum($root->left, $remaining) ||
               $this->pathSum($root->right, $remaining);
    }
    public function viewright($root){
        if($root === null) return null;

        return $this->viewright($root->right);
    }
}

// Build a sample tree
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

// Test inversion
$inverted = $tree->invert($root);
echo "Inorder After Inversion: ";
print_r($tree->inorderrec($inverted));

echo "\n";

// Test verify (same tree)
$a = new Treenode(1);
$a->left = new Treenode(2);
$a->right = new Treenode(3);

$b = new Treenode(1);
$b->left = new Treenode(2);
$b->right = new Treenode(3);

echo Treenode::verify($a, $b) ? "Trees are identical" : "Trees are not identical";
echo $tree->pathSum($root, 22) ? "Path exists\n" : "No path\n";
echo "Right side value of trees ". $tree->viewright($root);