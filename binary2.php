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
    public function viewRight($root) {
    if ($root === null) return [];

    $queue = [$root];
    $result = [];

    while (!empty($queue)) {
        $levelSize = count($queue);

        for ($i = 0; $i < $levelSize; $i++) {
            $node = array_shift($queue);

            // If it's the last node in this level, add to result
            if ($i == $levelSize - 1) {
                $result[] = $node->data;
            }

            if ($node->left !== null) $queue[] = $node->left;
            if ($node->right !== null) $queue[] = $node->right;
        }
    }

    return $result;
    }
    public function countNodes($root) {
        if ($root === null) return 0;
        $leftHeight = $this->getHeight($root->left);
        $rightHeight = $this->getHeight($root->right);
        if ($leftHeight === $rightHeight) {
            return (1 << $leftHeight) + $this->countNodes($root->right);
        } else {
            return (1 << $rightHeight) + $this->countNodes($root->left);
        }
    }
     public function getHeight($node) {
        $height = 0;
        while ($node !== null) {
            $height++;
            $node = $node->left;
        }
        return $height;
    }
    public function flatten($root) {
        if ($root === null) return;

        // Flatten left and right subtrees
        $this->flatten($root->left);
        $this->flatten($root->right);

        // If left subtree exists, insert it between root and right
        if ($root->left !== null) {
            $tempRight = $root->right;
            $root->right = $root->left;
            $root->left = null;

            // Find the tail of the new right subtree
            $tail = $root->right;
            while ($tail->right !== null) {
                $tail = $tail->right;
            }

            // Attach the original right subtree
            $tail->right = $tempRight;
        }
    }
    public function printList($root) {
        $result = [];
        while ($root !== null) {
            $result[] = $root->data;
            $root = $root->right;
        }
        return $result;
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
$rightView = $tree->viewRight($root); 
echo "Right side view of tree: " . implode(", ", $rightView);

echo "\n";
echo "Height of left subtree: " . $tree->getHeight($root->left) . "\n";
echo "Height of right subtree: " . $tree->getHeight($root->right) . "\n";

// Test countNodes
echo "Total number of nodes: " . $tree->countNodes($root) . "\n";

echo "\n";
$tree->flatten($root);

echo "Flattened tree (preorder): ";
print_r($tree->printList($root));