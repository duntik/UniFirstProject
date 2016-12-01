<?php
/*
Developer: Arnis Zelcs
2016
*/

/*
Clubs page

Default: ./clubs.php (no varaible passed in url)
page display all existing clubs sorted by genre

Genre selected by user: ./clubs.php?clubs_genre=genre(varaible passed in url)
user redirected to the same script with a clubs_genre variable passed in url
page display clubs of the specific genre

Example:

if (isset($_GET['clubs_genre'])) {
	$genre = $_GET['clubs_genre'];
}

$db = new Connection();
$db->open();

if (isset($genre)) {
	$result = $db->runQuery("SELECT * FROM clubs WHERE genre = ".$genre);
} else {
	$result = $db->runQuery("SELECT * FROM clubs);
}

$db->close();

*/

include('db/simpleDB.php');
include('layouts/HTMLcomponents.php');
include('php/functions.php');

// Navbar
top("Clubs and Societies");

/* LOAD a page content depending on if a 'club genre' passed to the script or not (GET) */
/* Stage one: this script receive a 'genre type' and load a list of the clubs what are of that type (can be send from 'Stage two' of this script) */

// if passed
if (isset($_GET["clubs_genre"])) {
    // save it
    $clGenreDecoded = str_replace("-", " ", $_GET["clubs_genre"]);

    // fetch clubs of the passed type
    $db = new Connection();
    $db->open();
    $clubs = $db->runQuery("SELECT * FROM clubs,clubgenre WHERE genreCode = code AND category = '". $clGenreDecoded."'");
    $db->close();

    echo '<h4 class="sp-title">Discover Portlethen '. $clGenreDecoded .' clubs</h4>';

    echo '<div class="row">';
    while ($row = $clubs->fetch_assoc()) {
            // build a link to a club template and pass a club name using GET
            // check .htaccess file for routing rules - /sportlethen/genre/club name
            echo '
            <a href="/sportlethen/'. url($row["category"]) .'/'. url($row["name"]) .'-C'.$row["club_id"].'">
                <div class="sp-genre-list z-depth-1 waves-effect waves-dark col s12 l8 offset-l2">
                    '. $row["name"] .'
                </div>
            </a>';
    }
    echo '</div>';


/* Stage two: this script doesn't receive a 'genre type' and load a list of the all club genres from a db (amount of clubs of each genre as well)*/

} else {

    $db = new Connection();
    $db->open();
    $clubs = $db->runQuery("SELECT category, code, genreCode, COUNT(*) AS num FROM clubgenre, clubs WHERE genreCode = code GROUP BY code");
    $db->close();

    echo '<h4 class="sp-title">Discover Portlethen clubs</h4>';

    echo '<div class="row">';
    while ($row = $clubs->fetch_assoc()) {
            // can pass a 'genre type' to this script to display a list of the clubs of that genre
            echo '
            <a href="/sportlethen/'. url($row["category"]) .'">
                <div class="col s12 m6 l3">
                    <p class="sp-genre z-depth-2">'. $row["category"] .' <br>('. $row["num"] .')</p>
                </div>
            </a>';
    }
    echo '</div>';

}

// Footer
bottom("public");

?>
