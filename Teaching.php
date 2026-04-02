<?php
function test(){
    static $count = 0;
    $count++;
    echo $count." , ";
    if($count<15){
        test();
    }
    $count--;
}
test();
$arr = array("Yes", "no");
var_dump($arr);

//example of named arguments
function foo($a, $b, $c = 3,$d = 4){
    return $a + $b + $c + $d;
}
var_dump(foo(a:1, b: 2, d: 40));//can't conbine unpacking with named arguments
var_dump(foo(b :2, a : 1, d: 40));
var_dump(foo(...[1, 2]));
//anonymous function using callable
$double1= function($a){
    return $a * 2;
};

//using first-class callable syntax
function double_function($a){
    return $a * 2;
};

$double2 = double_function(...);
//using arrow function
$double3 = fn($a) => $a * 2;

//using closure
$double4 = Closure::fromCallable('double_function');
echo "<br>";

// use the closure as a callback here to
//double the size of each element in our range
echo "Examples of use of closure";
echo "<br>";
$new_numbers = array_map($double1, range(1, 5));
print implode(' ', $new_numbers) . PHP_EOL;
echo "<br>";
$new_numbers = array_map($double2, range(1, 6));
print implode(' ', $new_numbers) . PHP_EOL;
echo "<br>";
$new_numbers = array_map($double3, range(1, 7));
print implode(' ', $new_numbers) . PHP_EOL;
echo "<br>";
$new_numbers = array_map($double4, range(1, 8));
print implode(' ', $new_numbers) . PHP_EOL;
echo "<br>";

//examples of variadic variables
function varid(...$a){
    return array_sum($a);
}
$sum= [ 1, 2, 3, 4, 5];
$sum2 = [13, 2, 6];
$result =varid(...$sum);//reason it isn't like the second one is due to passing one value, the $sum rather than multiple
echo "The sum of the array " . implode(" ",$sum) . " is " . $result;
echo "<br>";

//Example two of variadic variables
function sum(...$number){
    $acc = 0;
    foreach($number as $n){
        $acc += $n;
    }
    return $acc;
     }
echo sum(1,2,3,4);
echo "<br>";

//Json file handling( processing)
$arr1 = [1, 2, 3, 4, 5];
$json = json_encode($arr1);
echo $json;
echo "<br>";
//JSON DECODE 
$decoded = json_decode($json, true);
print_r($decoded);
echo "<br>";
// Hashing the password
$hash = password_hash('mypassword', PASSWORD_DEFAULT);

// Verifying the password
if (password_verify('mypassword', $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
echo "<br>";
//Class defination
class Hello{
    public $greeting = "Hello There";

    public function displaygreeting(){
        echo $this->greeting;
    }

}
$hello = new Hello();//instance of a class
$hello ->displaygreeting();