<?php 
class bts{
    public $data;
    public $left;
    public $right;
    public function __construct($data)
    {
        $this->data = $data;
        $this->left = null;
        $this->right = null;
    }
       
    public function btsIterator($root){
        return $root->data;
    }
    public function next($root){
        if($root === null){
            return null;
        }
    }
}
function add($num1, $num2){
        $result = $num1 + $num2;
        return $result;

    }

$num1 = 4;
$num2 = 10;
$result = add($num1, $num2);
echo "The sum of " . $num1 . " And ". $num2 . "  is: ".$result.  "\n";