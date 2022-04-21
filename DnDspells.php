<?php
session_start();     //     echo "<script>alert(\"\");</script>";

$servername = "localhost";
$username = "id18184005_charlie";
$password = "6$!Gk]wrZEL+T<Sh";
$database = "id18184005_spells";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "<script>alert(Connection failed: " . $e->getMessage().")</script>";
}

if($_SESSION['cookies'] == "")
    setcookie('name', "value", time() + (86400 * 30), "/");
if(!isset($_COOKIE['name'])) {
    echo "<script>alert(\"Cookies are not enabled on your browser, please turn them on or the site will not function\");</script>";
}

if(!isset($class)){
    $class = "all";
}

$spellName = $spellLevel = $castingTime = $spellTags = $spellNameStatement = $spellLevelStatement = $castingTimeStatement =  "";
$generateSpellForm = $errorFlagged = false;

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['filtersSubmit'])) {
    if($_GET['filtersSubmit'] == "filterSpells"){
        $class = $_GET['classIcon'];
        $spellName = $_GET['spellName'];
        $spellLevel = $_GET['spellLevel'];
        $castingTime = $_GET['castingTime'];
        $spellTags = explode(",", $_GET['spellTags']);
        for ($i = 0; $i < count($spellTags); $i++) {
            $spellTags[$i] = trim($spellTags[$i]);
        }

        if(empty($spellName) || $spellName == "") {
            $spellNameStatement = " WHERE 1 ";
        } else {
            $spellNameStatement = " WHERE name = '".$spellName."'";
        }

        if(empty($castingTime) || $castingTime == "") {
            $castingTimeStatement = "";
        } else {
            $castingTimeStatement = " AND casting_time = '".$castingTime."'";
        }

        if(!isset($spellLevel) || $spellLevel == "") {
           $spellLevelStatement = "";
        } else if ($spellLevel == "0") {
            $spellLevelStatement = " AND level = 'Cantrip'";
        } else {
            $spellLevelStatement = " AND level = '".$spellLevel."'";
        }

        $generateSpellForm = true;

    }else if($_GET['filtersSubmit'] == "resetFilters"){
        unset($_GET['classIcon'], $_GET['filtersSubmit'], $_GET['spellName'],$_GET['spellLevel'], $_GET['castingTime'], $_GET['spellTags']);
        $_SESSION['class']="all";
    }else{
        echo "<script>alert(\"Error Filtering Spells.\");</script>";
        $errorFlagged = true;
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}  // if(isset($spellTags)){echo "value='".$spellTags."'"
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DnD Spell Filter</title>
    <link rel="stylesheet" href="DnDspells.css">
    <meta name="viewport" content="width=device-width; initial-scale=1.0;">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/15ccf98433.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="filters">
        <form autocomplete="off"  action="DnDspells.php" method="get">
            <div class="classFilters">
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="all" <?php if($class === "all"){echo "checked";}?>>
                    <img src="images/all%20icon.png" style="width:50px;height:auto;background-color: mediumpurple">
                    <div>Any Class</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Artificer" <?php if($class === "Artificer"){echo "checked";}?>>
                    <img src="images/artificer%20icon.png" style="width:50px;height:auto;background-color:darkgoldenrod">
                    <div>Artificer</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Bard" <?php if($class === "Bard"){echo "checked";}?>>
                    <img src="images/bard%20icon.png" style="width:50px;height:auto;background-color:hotpink">
                    <div>Bard</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Cleric" <?php if($class === "Cleric"){echo "checked";}?>>
                    <img src="images/cleric%20icon.png" style="width:50px;height:auto;background-color:silver">
                    <div>Cleric</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Druid" <?php if($class === "Druid"){echo "checked";}?>>
                    <img src="images/druid%20icon.png" style="width:50px;height:auto;background-color:olive">
                    <div>Druid</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Paladin" <?php if($class === "Paladin"){echo "checked";}?>>
                    <img src="images/paladin%20icon.png" style="width:50px;height:auto;background-color:yellow">
                    <div>Paladin</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Ranger" <?php if($class === "Ranger"){echo "checked";}?>>
                    <img src="images/ranger%20icon.png" style="width:50px;height:auto;background-color:forestgreen">
                    <div>Ranger</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Sorcerer" <?php if($class === "Sorcerer"){echo "checked";}?>>
                    <img src="images/sorcerer%20icon.png" style="width:50px;height:auto;background-color:indianred">
                    <div>Sorcerer</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Warlock" <?php if($class === "Warlock"){echo "checked";}?>>
                    <img src="images/warlock%20icon.png" style="width:50px;height:auto;background-color:rebeccapurple">
                    <div>Warlock</div>
                </label>
                <label class="classLabel">
                    <input type="radio" name="classIcon" class="classIcon" value="Wizard" <?php if($class === "Wizard"){echo "checked";}?>>
                    <img src="images/wizard%20icon.png" style="width:50px;height:auto;background-color:dodgerblue">
                    <div>Wizard</div>
                </label>
            </div>
            <div class="otherFilters">
                <label class="filterLabel">
                    Spell Name
                    <div><input type="text" name="spellName" placeholder="Search Spell Names" <?php if(isset($_GET['spellName'])){echo "value='".$_GET['spellName']."'";} ?>></div>
                </label>
                <label class="filterLabel">
                    Spell Level
                    <div><input id="spellLevelInput" type="text" name="spellLevel" placeholder="Select Spell Level" <?php if(isset($_GET['spellLevel'])){echo "value='".$_GET['spellLevel']."'";} ?>></div>
                </label>
                <label class="filterLabel">
                    Casting Time
                    <div><input id="castingTimeInput" type="text" name="castingTime" placeholder="Select Casting Times" <?php if(isset($_GET['castingTime'])){echo "value='".$_GET['castingTime']."'";} ?>></div>
                </label>
                <label class="filterLabel">
                    Spell Tags<br>
                    <div><input id="spellTagsInput" type="text" name="spellTags" placeholder="Select Spell Tags" <?php if(isset($_GET['spellTags'])){echo "value='".$_GET['spellTags']."'";} ?>></div>
                </label>
            </div>
            <div class="submitButton">
                <input type="submit" name="filtersSubmit" class="filterButton" value="filterSpells"><br>
                <input type="submit" name="filtersSubmit" class="resetButton" value="resetFilters">
            </div>
        </form>
    </div>
    <div class="contact">
        Created by Charlie Stapley<br>
        Any problems, feedback or queries? Contact me on:<br>
        <a href="https://twitter.com/computercks" style="text-decoration:none">
            <i class="fab fa-twitter-square fa-lg" style="color: dodgerblue;"></i>
        </a> @computercks
        <br>
        <a href="mailto:computercks232@gmail.com?Subject=DnDspells%20Feedback." style="text-decoration:none">
            <i class="far fa-envelope fa-lg" style="color: black;"></i>
        </a> computercks232@gmail.com
        <br>
        <a href="https://discordapp.com/users/535065206385016842" style="text-decoration:none">
            <i class="fab fa-discord fa-lg" style="color: dimgrey;"></i>
        </a> CharlieStapley#3004
    </div>

<?php

if($generateSpellForm && !$errorFlagged){   //        $filter2 = array_keys(array_column($students, "Year"), 2);
    $sql = "SELECT * FROM spells".$spellNameStatement.$spellLevelStatement.$castingTimeStatement;
    $statement = $conn->query($sql);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $keysArray = array_keys($result[0]);
    $newResult = array();
    for ($i = 0; $i < count($result); $i++){
        $classFound = false;
        $tagsFound = true;
        if(isset($class) && $class !== "all"){
            $classList = json_decode($result[$i]["classes"]);
            if (in_array($class, $classList)){
                $classFound = true;
            }
        } else {
            $classFound = true;
        }
        if(!empty($_GET['spellTags'])){
            foreach ($spellTags as $tag){
                //if(!empty($tag))
                $tagsList = json_decode($result[$i]["tags"]);
                if (!in_array($tag, $tagsList)){
                    $tagsFound = false;
                }
            }
        }
        //var_dump($classFound);
        if($classFound === true && $tagsFound === true){
            array_push($newResult, $result[$i]);
        }
    }

    echo "<div id='Table'>";
    echo "<table><tr>";
    echo "<th style='text-align: center'>Spell Name</th>";
    echo "<th style='text-align: center'>Level</th>";
    echo "<th style='text-align: center'>Casting Time</th>";
    echo "<th style='text-align: center'>Duration</th>";
    echo "<th style='text-align: center'>Range/Area</th>";
    echo "<th style='text-align: center'>Attack/Save</th>";
    echo "<th style='text-align: center'>Damage/Effect</th>";
    echo "</tr>";
    for($x = 0; $x < count($newResult); $x++){
        $decodedComponents = json_decode($newResult[$x][$keysArray[10]]);
        $components = "";
        for ($i = 0; $i < count($decodedComponents); $i++){
            if($i == 0){
                $components = $components . $decodedComponents[$i];
            } else {
                $components = $components . ", " . $decodedComponents[$i];
            }
        }
        $decodedClasses = json_decode($newResult[$x][$keysArray[11]]);
        $classes = "";
        for ($i = 0; $i < count($decodedClasses); $i++){
            if($i == 0){
                $classes = $classes . $decodedClasses[$i];
            } else {
                $classes = $classes . ", " . $decodedClasses[$i];
            }
        }
        $decodedTags = array_filter(json_decode($newResult[$x][$keysArray[8]]));
        $tags = "";
        for ($i = 0; $i < count($decodedTags); $i++){
            if($i == 0){
                $tags = $tags . $decodedTags[$i];
            } else {
                $tags = $tags . ", " . $decodedTags[$i];
            }
        }
        $newResult[$x][$keysArray[4]] = str_replace("{", "", $newResult[$x][$keysArray[4]]);
        $newResult[$x][$keysArray[4]] = str_replace("}", "", $newResult[$x][$keysArray[4]]);

        echo "<tr onclick=showHiddenRow('hidden_row".$x."_1');showHiddenRow('hidden_row".$x."_2')>";   //showHiddenRow('hidden_row".$x."_2')
        for($y = 0; $y < 7; $y++){
            echo "<td style='text-align: center'>".$newResult[$x][$keysArray[$y]]."</td>";
        }
        echo "</tr>";
        echo "<tr id=\"hidden_row".$x."_1\" class=\"hidden_row\">";
        echo "<td style='font-weight: bold;text-align: center'>Description:</td>";
        echo "<td colspan=6>".$newResult[$x][$keysArray[7]]."</td>";
        echo "</tr>";
        echo "<tr id=\"hidden_row".$x."_2\" class=\"hidden_row\">";
        echo "<td style='font-weight: bold;text-align: center'>Components:</td>";
        echo "<td>" . $components . "</td>";
        echo "<td style='font-weight: bold;text-align: center'>Classes:</td>";
        echo "<td>" . $classes . "</td>";
        echo "<td style='font-weight: bold;text-align: center'>Tags:</td>";
        echo "<td>" . $tags . "</td>";
        echo "</tr>";
        echo "<tr class=\"spacer\"></tr>";
    }

    echo "</tr></table>";
    echo "</div>";
    mysqli_close($conn);
}

?>
</div>
<script>
    function autocomplete(inp, arr) {      //function for autocompletion is a lightly modified version of: https://www.w3schools.com/howto/howto_js_autocomplete.asp
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        let currentFocus;

        inp.addEventListener("dblclick", function(){
            let a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                    /*insert the value for the autocomplete text field:*/
                    if(inp === document.getElementById("spellLevelInput") || inp === document.getElementById("castingTimeInput")){
                        inp.value = this.getElementsByTagName("input")[0].value;
                    }
                    else {
                        if(inp.value !== ""){
                            inp.value = inp.value + ", " + this.getElementsByTagName("input")[0].value;
                        }
                        else {
                            inp.value = this.getElementsByTagName("input")[0].value;
                        }
                    }

                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        })

        inp.addEventListener("touchstart", function(){
            let a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        })

        inp.addEventListener("input", function(e){
            let a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            let x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (let i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            let x = document.getElementsByClassName("autocomplete-items");
            for (let i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    /*An array containing all the country names in the world:*/
    let spellLevels = ["Cantrip","1st Level","2nd Level","3rd Level","4th Level","5th Level","6th Level","7th Level","8th Level","9th Level"];
    let castingTime = ["1 Action","1 Bonus Action","1 Reaction","1 Minute","10 Minutes","1 Hour","8 Hours","12 Hours","24 Hours"];
    let spellTags = ["Affliction","Buff: Ability Check","Buff: Damage","Buff: Saving Throw","Buff: AC","Buff: Movement","Buff: Attack Roll","Buff: Movement Speed","Charm","Communication","Damage","Detection","Debuff: Ability Check","Debuff: Damage","Debuff: Saving Throw","Debuff: AC","Debuff: Movement","Debuff: Attack Roll","Debuff: Movement Speed","Debuff: Resistance","Evasion","Familiar","Healing","Incapacitation","Protection","Restraining","Resistance","Restoration","Shapeshifting","Utility"];
    /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
    autocomplete(document.getElementById("spellLevelInput"), spellLevels);
    autocomplete(document.getElementById("castingTimeInput"), castingTime);
    autocomplete(document.getElementById("spellTagsInput"), spellTags);
</script>
<script type="text/javascript">
    function showHiddenRow(row) {
        $("#" + row).toggle();
    }
</script>
</body>
</html>