<?php
$dataArr = array(
    "text" => 'R                '.
              '/n         I      '.
              '/n—             '.
              '/n——      {{{     '.
              '/n——            '.
              '/n—       W      '.
              '/n    Z            '.
              '/n————————  Noemie is the best  ',
              
    // "text" => "R /n          /n ",
    "tag" => "welcome"
);
$myJSON = json_encode($dataArr);
echo $myJSON;
?>
