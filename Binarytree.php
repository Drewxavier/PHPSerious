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
        if ($root === null){
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
    public function maxDepth($root){
        if($root===null){
            return 0;
        }
        $left_depth = $this->maxDepth($root->left);
        $right_depth = $this->maxDepth($root->right);

        return 1 + max($left_depth, $right_depth);
    }
    //maxDepth using bfs
    public function bfsMaxDepth($root){
        if($root===null){
            return 0;
        }
        $queue=[$root];
        $depth = 0;
        while(!empty($queue)){
            $levelSize = count($queue);
            $depth++;
            for($i = 0; $i<$levelSize;$i++){
                $node = array_shift($queue);
                if($node->left !== null){
                    $queue[] = $node->left;
                }
                if($node->right !== null){
                    $queue[] = $node->right;
                }
            }
        }
        return $depth;     
    }

    
    private function isMirror($left, $right){
            if ($left=== null&& $right === null){
                return true;
            }
            if($left=== null || $right===null){
                return false;
            }
            return ($left->data == $right->data && $this->isMirror($left->left, $right->right) && $this->isMirror($left->right, $right->left));
        }
    public function isSymmetric($root){
        if($root === null) return true;
        return $this->isMirror($root->left, $root->right);

    }
    public function levelorder2($root){
        if($root === null){
            return [];
        }
        $result = [];
        $queue = [$root];
        while (!empty($queue)){
            $level = [];
            $levelSize = count($queue);
            for($i= 0; $i < $levelSize; $i++){
                $node = array_shift($queue);
                $level[] = $node->data;
                if (!empty($node->left)){
                    $queue[] = $node->left;
                }
                if(!empty($node->right)){
                    $queue[] = $node->right;
                }
                $result[] = $level;

            }
            return $result;
        }

    }
    public function lowestcommon($root, $p, $q)
    #Base case: empty tree or found one of the targets

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

// Test inorder (iterative)
echo "Inorder Iterative: ";
print_r($tree->inorder($root));

// Test preorder (recursive)
echo "Preorder Recursive: ";
print_r($tree->preorder($root));

// Test preorder (iterative)
echo "Preorder Iterative: ";
print_r($tree->iterpreorder($root));

// Test postorder
echo "Postorder Recursive: ";
print_r($tree->postorder($root));

// Test level order (BFS)
echo "Level Order (BFS): ";
print_r($tree->levelOrder($root));

// Test maxDepth
echo "maxDepth: ";
print_r($tree->maxDepth($root));

echo "\n";

// Test maxDepth uisng bfs
echo "maxDepthBFS: ";
print_r($tree->bfsMaxDepth($root));
// Build symmetric tree
$root1 = new Treenode(1);
$root1->left = new Treenode(2);
$root1->right = new Treenode(2);
$root1->left->left = new Treenode(3);
$root1->left->right = new Treenode(4);
$root1->right->left = new Treenode(4);
$root1->right->right = new Treenode(3);

$tree = new Treenode(null); // just to call methods
echo "\n";

echo "Symmetric Tree: ";
var_export($tree->isSymmetric($root1)); // should print true

// Build asymmetric tree
$root2 = new Treenode(1);
$root2->left = new Treenode(2);
$root2->right = new Treenode(2);
$root2->left->right = new Treenode(3);
$root2->right->right = new Treenode(3);

echo "\nAsymmetric Tree: ";
var_export($tree->isSymmetric($root2)); // should print false

echo "\n";
// Test level order
echo "level order: ";
print_r($tree->levelorder2($root));

echo "\n";
