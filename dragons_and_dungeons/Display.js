
class Display
{
    constructor()
    {
        this.room = new Array();
        this.location = {x:0, y:0};
        this.restrictedChars = new Array();
    }

    render(pixelsXY, tag, error)
    {
        console.log(error);
        if (error != undefined)
        {
            document.getElementById(tag).innerHTML = pixelsXY;
        }
        else
        {
            if (pixelsXY == 'loading')
            {
                document.getElementById(tag).innerHTML = 'Loading....';
            }
            else
            {
                document.getElementById(tag).innerHTML = '';
                for(var i=0; i<pixelsXY.length; i++)
                {
                    this.printLine(pixelsXY[i], tag);
                }
            }
        }
    }
    printLine(line, tag)
    {
        for(var i=0; i<line.length; i++)
        {
            this.printChar(line[i], tag);
        }
        document.getElementById(tag).innerHTML += "</br>";
    }
    printChar(char, tag)
    {
        document.getElementById(tag).innerHTML += char;
    }
    arrify(text)
    {
        let arr = new Array();
        let nestedArr = new Array();

        for(var i=0; i<text.length; i++)
        {
            if (i + 1 < text.length)//not last character
            {
                if (text[i] == '/' && text[i + 1] == 'n')//check for newline
                {
                    arr.push(nestedArr);
                    nestedArr = new Array();
                    i++;
                }
                else
                {
                    nestedArr.push(text[i]);
                }
            }
            else//push final array onto meta array
            {
                nestedArr.push(text[i]);
                arr.push(nestedArr);
            }
        }

        return arr;
    }

    isPre(arr, index, toFind)
    {
        if (index < arr.indexOf(toFind)) return true;
        else return false;
    }

    //Necessary for when vertBorder and horizBorder are the same character.
    getLastSequential(char, arr, X)
    {
        if (arr[X+1] != char) return X;
        return this.getLastSequential(char, arr, X+1);
    }

    addBorder(textArr)
    {
        const horizBorder = '-';
        const vertBorder = '|';
        const spaceHolder = '—';
        const untouchable = ' ';
        this.restrictedChars = [horizBorder, vertBorder, spaceHolder];
        textArr.unshift(new Array());
        textArr.push(new Array());

        for(var i=0; i<textArr.length; i++)
        {
            //if first index is a —,
            if(textArr[i][0] == spaceHolder)
            {
                //Prepend a space
                textArr[i].unshift(untouchable);
                // loop through rest of —
                let spaceCounter = 1;
                let currCell = textArr[i][spaceCounter];
                while (currCell == spaceHolder)
                {
                    //Except for the last one, 
                    if (textArr[i][spaceCounter+1] == spaceHolder)
                    {
                        //if the cell above AND below are a |, -, spaceholder, or pre-space,
                        if ((this.restrictedChars.includes(textArr[i-1][spaceCounter]) ||
                            ((textArr[i-1][spaceCounter] == untouchable) &&
                             this.isPre(textArr[i-1], spaceCounter, vertBorder)))
                             &&
                            (this.restrictedChars.includes(textArr[i+1][spaceCounter-1])))
                        {
                            // replace with ' '
                            textArr[i][spaceCounter] = untouchable;
                        }
                        //otherwise replace with '-'. 
                        else textArr[i][spaceCounter] = horizBorder;
                    }
                    //replace the last one with a |
                    else
                    {
                        textArr[i][spaceCounter] = vertBorder;
                    }
                    spaceCounter++;
                    currCell = textArr[i][spaceCounter]
                }
            }
            //and if it's first row, prepend a space
            else if (i == 0)
                textArr[i].unshift(untouchable);
            //if it's not last one, prepend |
            else if (i != textArr.length - 1) textArr[i].unshift(vertBorder)


            //Except for LAST row, loop through BELOW row and append the necessary borders
            if (i != textArr.length - 1)
            {
                if (textArr[i].length != 1)
                {
                    textArr[i].push(vertBorder);
                }
                while (textArr[i].length < textArr[i+1].length + 1)
                {
                    textArr[i].push(horizBorder);
                }
            }
            //Except for FIRST row, loop through ABOVE row and append the necessary borders
            if (i != 0)
            {
                while (textArr[i].length < textArr[i-1].lastIndexOf(vertBorder))
                {
                    //Handle when current column is earlier in the row than the previous row's "beginning"
                    let realBeginning = this.getLastSequential(vertBorder, textArr[i-1], textArr[i-1].indexOf(vertBorder));
                    if (realBeginning > textArr[i].length - 1)
                        textArr[i].push(untouchable);
                    else
                        textArr[i].push(horizBorder);
                }
            }
        }
        return textArr;
    }

    async getJson(url)
    {
        const JSON = await fetch(url)
            .then(async function (response) {
                return await response.json();
            })
            .catch(function (error) {
                console.log("Error: " + error);
            });
        return JSON;
    }

    async dungeon()
    {
        const dungeonJson = await this.getJson("dungeon.php");
        let textArr = this.arrify(dungeonJson.text);
        textArr = this.addBorder(textArr);
        this.render(textArr, "dungeon");
        
        this.room = textArr;
        this.location = {x:1, y:1};
    }

    async welcome()
    {
        this.render('loading', 'welcome');

        const welcomeJSON = await this.getJson("welcome.php");
        this.render(this.arrify(welcomeJSON.text), "welcome");
    }

    getLocation()
    {
        const X = this.location.x;
        const Y = this.location.y;
        return [X, Y];
    }

    moveHero(targX, targY)
    {
        const currLocation = this.getLocation();
        const X = currLocation[0];
        const Y = currLocation[1];

        const targetLocationVal = this.room[targY][targX];
        this.room[targY][targX] = this.room[Y][X];
        this.room[Y][X] = targetLocationVal;
        this.location = {x:targX, y:targY};
        this.render(this.room, "dungeon");
    }
    
    moveLeft(X, Y)
    {
        if (!this.restrictedChars.includes(this.room[Y][X-1])) 
        { this.moveHero(X-1, Y); }
    }

    moveUp(X, Y)
    {
        if (!this.restrictedChars.includes(this.room[Y-1][X])) 
        { this.moveHero(X, Y-1); }
    }

    moveRight(X, Y)
    {
        if (!this.restrictedChars.includes(this.room[Y][X+1])) 
        { this.moveHero(X+1, Y); }
    }

    moveDown(X, Y)
    {
        if (!this.restrictedChars.includes(this.room[Y+1][X]))
        { this.moveHero(X, Y+1); }
    }









}