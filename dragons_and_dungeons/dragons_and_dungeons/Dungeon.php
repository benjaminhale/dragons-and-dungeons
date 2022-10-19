<?php
echo $_REQUEST; 

require 'Cave.php';
class Dungeon
{
    public $caves = [[]];
    public $heroLoc = [];
    
    function __construct()
    {
        //setup first cave
        $first = '     '.
               '/n     '.
               '/n  R  '.
               '/n     '.
               '/n     ';
        $caveLoc = array('x' => 0, 'y' => 0);
        $this->heroLoc = $caveLoc;
        array_push($this->caves, new Cave($first, $caveLoc));
    }

    function addCave($currDungeonLoc, $currCaveLoc)
    {//need to figure out whether I need to move the hero's dungeon location here
        $currCave = $this->caves[$currDungeonLoc['y']['z']];

        $direction = '';

        //if 
        if ($currCaveLoc['y'] == 0)//hero's y is 0
        {
            //add a row above to dungeon if none exists
                //increase y indexes of all caves by 1
            //if it does exist, add nulls before target index on row after existing caves
            //append a new cave to new row 
        }
        elseif ($currCaveLoc['y'] == count($this->caves) - 1)//hero's y is last index
        {
            //append a new row to dungeon if none exists
            //if it does exist, add nulls before target index on row after existing caves
            //append a new cave to new row
        }
        elseif ($direction == 'E')//hero's x is last index
        {
            //append a new cave to the current row

        }
        elseif ($direction == 'W') //hero's x is first index
        {
            //add a null cell before all rows if dungeon x index is 0
            //increase x indices of all caves by 1 
            //add cave to left of curr location
        }

    }

    function generateCaveData()
    {
        $randomCaveData = '';
        //random height of cave
        $caveHeight = rand(3, 18);
        for ($i = 0; $i < $caveHeight; $i++)
        {
            $indentLength = rand(0, 5);
            $rowLength = rand(4, 22);
            //random row indent length 
            for ($j = 0; $j < $indentLength; $j++)
            {
                $randomCaveData .= '—';
            }
            //random row length
            for ($j = 0; $j < $rowLength; $j++)
            {
                $randomCaveData .= ' ';
            }
            if ($i <= $caveHeight - 1) 
            {
                $randomCaveData .= '/n';
            }
        }
        return $randomCaveData;
    }

    function generateExits($prevCaveLoc, $direction)
    {
        //Add entrance from previous cave
        //Do not add exits to lead to a cave that already exists
            //Unless that cave already has an exit that leads to this cave
        if ($direction == 'N')
        {

        }
        elseif ($direction == 'S')
        {
            
        }
        elseif ($direction == 'E')
        {
            
        }
        elseif ($direction == 'W')
        {
            
        }
        
    }

    //User file
    //Cave file
    //



}

?>