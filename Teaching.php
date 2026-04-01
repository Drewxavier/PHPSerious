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