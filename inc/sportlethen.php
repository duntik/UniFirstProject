<?php
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

// Navbar
top("Clubs and Societies");

if (isset($_GET["clubs_genre"])) {
    $clGenre = $_GET["clubs_genre"];
    $clGenreDecoded = urldecode($clGenre);

    $db = new Connection();
    $db->open();
    $clubs = $db->runQuery("SELECT * FROM clubs,clubgenre WHERE genreCode = code AND category = '". $clGenreDecoded."'");
    $db->close();

    echo '<div class="row">';
    while ($row = $clubs->fetch_assoc()) {
            echo '
            <a href="/sportlethen/club/'. urlencode($row["club_id"]) .'">
                <div class="sp-genre-list z-depth-1 col s12">
                    '. $row["name"] .'
                </div>
            </a>';
    }
    echo '</div>';

} else {

    $db = new Connection();
    $db->open();
    $clubs = $db->runQuery("SELECT category, code, genreCode, COUNT(*) AS num FROM clubgenre, clubs WHERE genreCode = code GROUP BY code");
    $db->close();

    echo '<div class="row">';
    while ($row = $clubs->fetch_assoc()) {
            echo '
            <a href="/sportlethen/genre/'. urlencode($row["category"]) .'">
                <div class="sp-genre z-depth-2 col s12 m6 l3">
                    '. $row["category"] .' ('. $row["num"] .')
                </div>
            </a>';
    }
    echo '</div>';

}

// Footer
bottom();

?>
