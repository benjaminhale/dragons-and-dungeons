<!DOCTYPE html>
<html>
    <?php
        include("head.php")
    ?>

    <body>
        <div class='content'>


            <p id="welcome"></p>
            <p id="dungeon"></p>
            <script>
                display = new Display();//Setup display class.
                //Execute the script upon loading the page.
                window.addEventListener("load", () => {
                    display.welcome();
                });
                window.addEventListener("keydown", checkKeyPressed, false);
                function checkKeyPressed(evt) {
                    let location = display.getLocation();
                    if (["ArrowLeft"].indexOf(evt.code) > -1)//If left arrow is pressed.
                    {
                        evt.preventDefault();//stop screen from scrolling
                        display.moveLeft(location[0], location[1]);
                    }
                    else if (["ArrowUp"].indexOf(evt.code) > -1) 
                    {
                        evt.preventDefault();
                        display.moveUp(location[0], location[1]);
                    }
                    else if (["ArrowRight"].indexOf(evt.code) > -1) 
                    {
                        evt.preventDefault();
                        display.moveRight(location[0], location[1]);
                    }
                    else if (["ArrowDown"].indexOf(evt.code) > -1) 
                    {
                        evt.preventDefault();
                        display.moveDown(location[0], location[1]);
                    }
                    else if (["Space"].indexOf(evt.code) > -1) 
                    {
                        evt.preventDefault();
                        display.dungeon();
                    }
                }

            </script>
            
        </div>

    </body>

</html>