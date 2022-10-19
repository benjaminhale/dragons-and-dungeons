<?php
class Display 
{
    public function render($pixelsXY)
    {
        foreach ($pixelsXY as &$pixelY) 
        {
            foreach ($pixelY as &$pixel)
            {
                echo $pixel;
            }
            echo "</br>";
        }
    }
    public function welcome_user()
    {
        $row1 = "Welcome to Dragons and Dungeons!</br>";
        $row2 = "Adventure awaits....</br>";
        $row3 = "Press SPACEBAR to forge ahead.";

        $pixels = array (str_split($row1), 
                         str_split($row2), 
                         str_split($row3));

        $this->render($pixels);
    }
    
} 
?>