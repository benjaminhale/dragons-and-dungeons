<?php

function addNum($var)
{
    $toAdd = rand(0,100);
    return $var + $toAdd;
}

$heroLocation = array(10 => 'stuff', 20 => 'stuffy');
$exit = array('x' => 1234, 'y' => 7894);

switch (rand(0,3))
{
    case 0://North
        $exit['x'] = 3;
        $exit['y'] = 0;
        break;
    case 1://East
        $exit['x'] = 6;
        $exit['y'] = 3;
        break;
    case 2://South
        $exit['x'] = 3;
        $exit['y'] = 6;
        break;
    case 3://West
        $exit['x'] = 0;
        $exit['y'] = 3;
        break;
    default:
        $exit['x'] = 99;
        $exit['y'] = 99;
}


$first = array(
    array('data','moredata'),
    array('evenmore','muchmore'),
    array('lotsMore','plus'));

$number = 5312;
$number = addNum($number);

$unused = '  oeua.     ';

$dataArr = array(
    //Commenty comment
    "tag" => 'welcome',
    "first" => $first,
    "location" => $heroLocation,
    "exit" => $exit
);
$myJSON = json_encode($dataArr);
echo $myJSON;
?>