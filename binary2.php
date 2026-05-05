<?php
class Treenode {
    public $data;
    public $right;
    public $left;
    public $next;

    
    private $maxDiameter;

    public function __construct($data) {
        $this->data = $data;
        $this->right = null;
        $this->left = null;
        $this->next = null;
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

        return ($root1->data === $root2->data) &&// if both nodes exist, check if their values are equal
               self::verify($root1->left, $root2->left) && //recursively check if the left children of both nodes are identical
               self::verify($root1->right, $root2->right);//recursively check if the right children of both nodes are identical
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
        //fuckass Medium questions
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
     // Function to calculate diameter
    public function diameter($root) {
        $this->maxDiameter = 0; //ensures that every time you call diameter(), you start fresh
        $this->depth($root); //calls the helper function
        return $this->maxDiameter; //returns the largest diameter discovered during traversal i
    }
    // Helper function to calculate depth
    private function depth($node) {
        if ($node === null) return 0;//if depth is zeor, stop recursion

        $leftDepth = $this->depth($node->left);
        $rightDepth = $this->depth($node->right);//recursively calculate the depth of the left and right subtrees

        // Update diameter at this node
        $this->maxDiameter = max($this->maxDiameter, $leftDepth + $rightDepth);
        //The above, at every node, the longest path through that node is leftDepth + rightDepth

        return 1 + max($leftDepth, $rightDepth); //The depth of the current node is 1 (itself) plus the larger of its two children’s depths.
//This value is passed back up to the parent node so the parent can compute its own diameter.
    }
    public static function mergeTrees(?Treenode $t1, ?Treenode $t2): ?Treenode {//?Treenode means the parameter can either be: treenode object or null
        // If one of them is null, return the other
        if ($t1 === null) return $t2;
        if ($t2 === null) return $t1;

        // Add overlapping node values
        $t1->data += $t2->data;

        // Recurse on children
        $t1->left = self::mergeTrees($t1->left, $t2->left);
        $t1->right = self::mergeTrees($t1->right, $t2->right);

        return $t1;
    }
    public static function rangeSum($root, $low, $high){
        if ($root === null) return null;

        if($root->data < $low){
            return self::rangeSum($root->right, $low, $high);
        }
        if($root->data > $high){
            return self::rangeSum($root->left, $low, $high);

        }
        return $root->data + self::rangeSum($root->left, $low, $high) + self::rangeSum($root->right, $low, $high);
    }
    public function delete($root, $target) {
    if ($root === null) return null;

    if ($target < $root->data) {
        $root->left = $this->delete($root->left, $target);
    } elseif ($target > $root->data) {
        $root->right = $this->delete($root->right, $target);
    } else {
        // Node found
        if ($root->left === null) return $root->right;
        if ($root->right === null) return $root->left;

        // Two children: find inorder successor
        //used to borrow the next valid value(successor,)overwrite the current
        //node, and then delete the borrowed node(guaranteed to have at most one child, making deletion easy)
        $minNode = $this->findMin($root->right);//find the smallest node in the right subtree
        $root->data = $minNode->data;//copy it's value into the node we are deleting
        $root->right = $this->delete($root->right, $minNode->data);//Delete that successor node from the  right subtree
    }
    return $root;
}

private function findMin($node) {
    while ($node->left !== null) {
        $node = $node->left;
    }
    return $node;
}
public function connect($root){
    if($root === null) return null;
    $queue = [$root];

    while (!empty($queue)) {
        $size = count($queue);
        $prev = null;

        for ($i= 0; $i < $size; $i++){
            $node = array_shift($queue);
            if($prev !== null){
                $prev->next = $node;
            }
            $prev = $node;

            if($node->left !== null) $queue[] = $node->left;
            if($node->right !== null) $queue[] = $node->right;
        }

        // Last node in the level points to null
        $prev->next = null;
    }
    return $root;
}
    public function recoverTree($root) {
    $this->first = null;
    $this->second = null;
    $this->prev = null;

    $this->inorderRecover($root);

    // Swap values of the two misplaced nodes
    if ($this->first !== null && $this->second !== null) {
        $temp = $this->first->data;
        $this->first->data = $this->second->data;
        $this->second->data = $temp;
    }
}

private $first = null;
private $second = null;
private $prev = null;

private function inorderRecover($node) {
    if ($node === null) return;

    // 1. Traverse left subtree
    $this->inorderRecover($node->left);
    //check for violation
    if ($this->prev !== null && $this->prev->data > $node->data) {
        if ($this->first === null) {
            $this->first = $this->prev; // first wrong node
        }
        $this->second = $node; // second wrong node
    }
    $this->prev = $node; // update previous pointer
    //traverse right subtree
    $this->inorderRecover($node->right);
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
echo "\n";

//Provide diameter of the treenode
echo "Tree diameter = ";
echo $root->diameter($root); 
echo "\n";
// Tree 1
$t1 = new Treenode(1);
$t1->left = new Treenode(3);
$t1->right = new Treenode(2);
$t1->left->left = new Treenode(5);

// Tree 2
$t2 = new Treenode(2);
$t2->left = new Treenode(1);
$t2->right = new Treenode(3);
$t2->left->right = new Treenode(4);
$t2->right->right = new Treenode(7);

// Merge
$merged = Treenode::mergeTrees($t1, $t2);

// Simple print to check
function printTree($root) {
    if ($root === null) return;
    echo $root->data . " ";
    printTree($root->left);
    printTree($root->right);
}

printTree($merged); 
// Output: 3 4 5 4 5 7
echo "\n";

// Build BST
$root = new Treenode(10);
$root->left = new Treenode(5);
$root->right = new Treenode(15);
$root->left->left = new Treenode(3);
$root->left->right = new Treenode(7);
$root->right->right = new Treenode(18);

// Range sum [7, 15]
echo "Tree root range sum: ";  
echo Treenode::rangeSum($root, 7, 15); // Output: 32
echo "\n";

echo "Before deletion: ";
print_r($tree->inorderrec($root));

$root = $tree->delete($root, 15);

echo "\nAfter deleting 15: ";
print_r($tree->inorderrec($root));

echo "Connecting next pointers...\n";
$root = $tree->connect($root);

// Quick check: print the next pointer of root->left (node 5’s left child)
echo "Next of node 5->left (3) is: ";
echo isset($root->left->next) ? $root->left->next->data : "null";
echo "\n";

// Deliberately swap two nodes to simulate the error
$temp = $root->left->data;   // swap 5’s left child (3) with root (10)
$root->left->data = $root->data;
$root->data = $temp;

echo "Before recovery (inorder): ";
print_r($tree->inorderrec($root));

// Run recovery
$tree->recoverTree($root);

echo "\nAfter recovery (inorder): ";
print_r($tree->inorderrec($root));
