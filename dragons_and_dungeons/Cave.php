<?php
class Cave
{
    public $text = '';
    public $cells = [];
    public $bordered = [];
    public $horizBorder = '-';
    public $vertBorder = '|';
    public $restrictedChars = [];
    public $exits = [];
    public $dungeonLocation = [];
    function __construct()
    {
        $this->text = $this->generateCaveData();
        // $testData = 'mmmmm       ' .
        //           '/n   ' .
        //           '/n           ' .
        //           '/nmmmmmmm            ' .
        //           '/n    ';
        //$this->text = $testData;
        $this->cells = $this->arrify($this->text);
        $this->bordered = $this->addBorder($this->cells);
        //$this->dungeonLocation = $location;

        //add two (or one if they happen to be the same) exits to the border
        array_push($this->exits, $this->getRandExit());
        $randomExit = $this->getRandExit();
        if (!in_array($randomExit, $this->exits))
            array_push($this->exits, $randomExit);
    }

    
    function generateCaveData()
    {
        $randomCaveData = '';
        //random height of cave
        $caveHeight = rand(3, 20);
        for ($i = 0; $i < $caveHeight; $i++)
        {
            $indentLength = rand(0, 5);
            $rowLength = rand(15, 20);
            //random row indent length 
            for ($j = 0; $j < $indentLength; $j++)
            {
                $randomCaveData .= 'm';
            }
            //random row length
            for ($j = 0; $j < $rowLength; $j++)
            {
                $randomCaveData .= ' ';
            }
            if ($i < $caveHeight - 1) 
            {
                $randomCaveData .= '/n';
            }
        }
        return $randomCaveData;
    }


    function getBordMid($arr)
    {
        $beginning = array_search($this->horizBorder, $arr);
        $end = $this->getLastSequential($this->horizBorder, $arr, $beginning);
        return $beginning + intdiv(($end - $beginning), 2);
    }
    function getRandExit()
    {
        $randExit = [];
        $X = 0;
        $Y = 0;
        $caveHeight = count($this->bordered);
        $colMidIndex = intdiv($caveHeight, 2);
        switch (rand(0,3))
        {
            case 0://North: x = middle of border, y = first row
                $X = $this->getBordMid($this->bordered[0]);
                $Y = 0;
                break;
            case 1://South: x = middle of border, y = last row
                $X = $this->getBordMid($this->bordered[$caveHeight-1]);
                $Y = $caveHeight-1;
                break;
            case 2://East: x = last vertBorder marker, y = middle row
                $X = $this->array_search_last($this->vertBorder, $this->bordered[$colMidIndex]);
                $Y = $colMidIndex;
                break;
            case 3://West: x = first vertBorder marker, y = middle row
                $X = array_search($this->vertBorder, $this->bordered[$colMidIndex]);
                $Y = $colMidIndex;
                break;
        }
        $randExit['x'] = $X;
        $randExit['y'] = $Y;
        return $randExit;
    }

    function arrify($text)
    {
        $arr = [];
        $nestedArr = [];

        for ($i = 0; $i < strlen($text); $i++) {
            if ($i + 1 == strlen($text)) //is last character
            {
                $nestedArr[] = $text[$i]; //push final array onto multidimensional
                $arr[] = $nestedArr;
            }
            elseif ($text[$i] == '/' && $text[$i + 1] == 'n') //check for newline
            {
                $arr[] = $nestedArr; //add new array to multidimensional
                $nestedArr = [];
                $i++;
            }
            else //add character to array
            {
                $nestedArr[] = $text[$i];
            }
        }

        return $arr;
    }

    function isPre($arr, $index, $toFind)
    {
        if ($index < array_search($toFind, $arr))
            return true;
        else
            return false;
    }

    //Necessary for when vertBorder and horizBorder are the same character.
    function getLastSequential($char, $arr, $X)
    {
        if ($X >= (count($arr) - 1) ||
             $arr[$X + 1] != $char)
            return $X;
        return $this->getLastSequential($char, $arr, $X + 1);
    }

    function array_search_last($char, $arr)
    {
        $index = array_search($char, array_reverse($arr));
        if ($index === false) {
            $index = 0;
        }
        else {
            $index = count($arr) - 1 - $index;
        }
        return $index;
    }

    function addBorder($textArr)
    {
        //TODO: come back to this algorithm and start by setting up top and bottom borders
        //only have the meaty part go through the inside rows
        //You've already started this with the bottom row
        $horizBorder = $this->horizBorder;
        $vertBorder = $this->vertBorder;
        $spaceHolder = 'm';
        $untouchable = ' ';
        $this->restrictedChars = array($horizBorder, $vertBorder, $spaceHolder);
        array_unshift($textArr, []);

        //setup bottom row first because it's causing problems later
        $bottomRow = $textArr[count($textArr) - 1];
        $bottomBorder[] = $untouchable;
        for ($i = 0; $i < count($bottomRow); $i++)
        {
            if ($bottomRow[$i] == $spaceHolder)
                $bottomBorder[] = $untouchable;
        }
        $textArr[] = $bottomBorder;

        for ($i = 0; $i < count($textArr); $i++) {
            $prevRow = [];
            if ($i != 0)
                $prevRow = $textArr[$i - 1];
            //if first index is a —,
            if (count($textArr[$i]) != 0 && $textArr[$i][0] == $spaceHolder) {
                //Prepend a space
                array_unshift($textArr[$i], $untouchable);
                // loop through rest of —
                $spaceCounter = 1;
                $currCell = $textArr[$i][$spaceCounter];
                while ($currCell == $spaceHolder) {
                    //Except for the last one, 
                    if ($textArr[$i][$spaceCounter + 1] == $spaceHolder) {
                        $firstBoundary = $vertBorder;
                        if (!in_array($firstBoundary, $prevRow))
                        {
                            $firstBoundary = $horizBorder;
                        }
                        //if the cell above AND below are a |, -, spaceholder, or pre-space,
                        if ((in_array($prevRow[$spaceCounter], $this->restrictedChars) ||
                        (($prevRow[$spaceCounter] == $untouchable) &&
                        $this->isPre($prevRow, $spaceCounter, $firstBoundary)))
                        &&
                        in_array(($textArr[$i + 1][$spaceCounter - 1]), $this->restrictedChars))
                        {
                            // replace with ' '
                            $textArr[$i][$spaceCounter] = $untouchable;
                        }
                        //otherwise replace with '-'. 
                        else
                            $textArr[$i][$spaceCounter] = $horizBorder;
                    }
                    //replace the last one with a |
                    else {
                        $textArr[$i][$spaceCounter] = $vertBorder;
                    }
                    $spaceCounter++;
                    $currCell = $textArr[$i][$spaceCounter];
                }
            }
            //and if it's first row, prepend a space
            elseif ($i == 0)
                array_unshift($textArr[$i], $untouchable);
            //if it's not last one, prepend |
            elseif ($i != count($textArr) - 1)
                array_unshift($textArr[$i], $vertBorder);


            //Except for LAST row, loop through BELOW row and append the necessary borders
            if ($i != count($textArr) - 1) {
                if (count($textArr[$i]) != 1) {
                    $textArr[$i][] = $vertBorder;
                }
                while (count($textArr[$i]) < count($textArr[$i + 1]) + 1) 
                {//NOT SURE IF THIS WORKS....
                    if (in_array($textArr[$i + 1][count($textArr[$i]) - 1], $this->restrictedChars)) 
                    {
                        $textArr[$i][] = $untouchable;
                    }
                    else 
                    {
                        $textArr[$i][] = $horizBorder;
                    }
                }
            }
            //Except for FIRST row, loop through ABOVE row and append the necessary borders
            if ($i != 0) {
                $prevLastBorderIndex = $this->array_search_last($vertBorder, $prevRow);

                while (count($textArr[$i]) < $prevLastBorderIndex) {
                    //Handle when current column is earlier in the row than the previous row's "beginning"
                    $realBeginning = $this->getLastSequential($vertBorder, $prevRow, array_search($vertBorder, $prevRow));
                    if ($realBeginning > count($textArr[$i]) - 1)
                        $textArr[$i][] = $untouchable;
                    else
                        $textArr[$i][] = $horizBorder;
                }
            }
        }
        return $textArr;
    }
}


$first = '     '.
'/n     '.
'/n  R  '.
'/n     '.
'/n     ';

$cave = new Cave();
$heroLocation = $cave->exits[0];
$cave->bordered[$heroLocation['y']][$heroLocation['x']] = 'R';

//this is for seeing cave tests in a quick way
// $borderedText = '<br>';
// for ($i = 0; $i < count($cave->bordered); $i++)
// {
//     for ($j = 0; $j < count($cave->bordered[$i]); $j++)
//     {
//         $borderedText .= $cave->bordered[$i][$j];
//     }
//     $borderedText .= "<br>";
// }

$dataArr = array(
    "tag" => 'welcome',
    "cave" => $cave->bordered,
    "location" => $heroLocation,
    "exits" => $cave->exits,
    "restricted" => $cave->restrictedChars
);
$myJSON = json_encode($dataArr);
echo $myJSON;
?>
