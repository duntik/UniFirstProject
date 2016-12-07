<?php
/*
Developed by Vlad R
Created on 28 November 2016

Following php file displays user profile

TODO:
finalize style and layout
adjust queries so that unnecessary info is not passed (password, full club details for exmaple)
add viewer's status checking
display page based on viewer's status
recieve information from session
*/

//file contains methods for creating top and bottom
include('../inc/layouts/HTMLcomponents.php');
//file contains information and methods for DB
include('../inc/db/simpleDB.php');
//file contains function for trimming white-spaces and special characters when creating links
include('../php/functions.php');

//method for outputting approved contibutions - returns boolean which is used for message in case if no contributins made
//used methods are described in simpleDB and functions files
function getApprovedContributions ($title, $userID) {
	$db = new Connection();
	$db->open();
	
	//checking which table is needed and making according query, won't work if wrong table given
	if ($title == "events") {
		$queryResult = $db->runQuery("SELECT *, Clubs.name as clubname FROM ClubGenre, Clubs, ClubEvents WHERE user_id = ". $userID . " AND approved = 1 AND code = genreCode AND Clubs.club_id = ClubEvents.club_id");
	} else if ($title == "articles") {
		$queryResult = $db->runQuery("SELECT * FROM HealthNews WHERE user_id = ". $userID . " AND approved = 1");
	} else if ($title == "locations") {
		$queryResult = $db->runQuery("SELECT * FROM Locations WHERE user_id = ". $userID . " AND approved = 1");
	} else if ($title == "routes") {
		$queryResult = $db->runQuery("SELECT * FROM Routes WHERE user_id = ". $userID . " AND approved = 1");
	}
	
	$db->close();

	//outputting contributions and returning boolean value
	if ($queryResult->num_rows >= 1) {
		echo "<br>Added " . $title . ":<br>";
		echo '<div class="collection">';
		while ($row = $queryResult->fetch_assoc()) {
			if ($title == "events") {
				echo ("<a href='/sportlethen/" . url($row["category"]) . "/" . url($row["clubname"]) . "/event/" . "C" . url($row["club_id"]) . "E" . url($row["event_id"]) . "' class='collection-item'>" . $row["name"] . "</a>");
			}
			if ($title == "articles") {
				echo ("<a href='/health-wellbeing/" . url($row["title"]) . "-A" . url($row["news_id"]) . "' class='collection-item'>" . $row["title"] . "</a>");
			}
			if ($title == "locations") {
				echo ("<a href='/map/" . url($row["name"]) . "-L" . url($row["loc_id"]) . "' class='collection-item'>" . $row["name"] . "</a>");
			}
			if ($title == "routes") {
				echo ("<a href='/map/" . url($row["name"]) . "-R" . url($row["route_id"]) . "' class='collection-item'>" . $row["name"] . "</a>");
			}
		}
		echo '</div>';
		return TRUE;
	} else 
		return FALSE;
}

//to identify users usernames are used as they are unique
//getting username from link:
$person = $_GET["username"];

//getting sql query with user data using his/her username
$db = new Connection();
$db->open();
$user = $db->runQuery("SELECT * FROM Users WHERE username LIKE '" . $person . "'");
$db->close();

//displaying profile page only if such user exists
if ($user->num_rows >= 1) {
	//writing queried user data into variables
	//not all data will be used in final version
	while ($row = $user->fetch_assoc()) {
		$userID = $row["user_id"];
		$clubAdmin = $row["clubAdmin"];
		$nkpag = $row["nkpag"];
		$siteAdmin = $row["siteAdmin"];
		$userName = $row["username"];
		$email = $row["email"];
		//$password = $row["password"];
		$blocked = $row["blocked"];
	}

	// Navbar
	top($userName . "'s profile");

	//Other page content
	//Displaying username
	echo "<h2>" . $userName . "</h2>";

	//Displaying if user acount is blocked
	if ($blocked == 1) {
		echo "This account is disabled<br>";
	}

	//Displaying if user is one of site admins
	if ($siteAdmin == 1) {
		echo "Site Administrator<br>";
	} else if ($nkpag == 1) {
		echo "Map Admin<br>";
	}

	//Following 2 strings will be displayed only for admins
	echo "User ID: " . $userID . "<br>";
	echo "Registered email address: " . $email . "<br>";

	//getting administrated clubs (if any)
	if ($clubAdmin == 1) {
		echo ("Admin of following clubs: ");
		$db = new Connection();
		$db->open();
		$clubsAdministered = $db->runQuery("SELECT * FROM ClubAdmins, Clubs, ClubGenre WHERE Clubs.club_id = ClubAdmins.club_id AND user_id = ". $userID . " AND Clubs.genreCode = ClubGenre.code");
		$db->close();
		while ($row = $clubsAdministered->fetch_assoc()) {
			echo ("<a href=/sportlethen/" . url($row["category"]) . "/" . url($row["name"]) . "-C" . $row["club_id"] . ">" . $row["name"] . "</a>") . " ";
		}
	}

	//getting added events that are approved
	$events = getApprovedContributions("events", $userID);

	//getting added articles that are approved
	$news = getApprovedContributions("articles", $userID);

	//getting added locations that are approved
	$locations = getApprovedContributions("locations", $userID);

	//getting added routes that are approved
	$routes = getApprovedContributions("routes", $userID);

	//outputting message if user has no approved contributions
	if (($events == 0) and ($news == 0) and ($locations == 0) and ($routes == 0)) {
		echo "<br>This user has not made any contibutions yet<br>";
	}

} else {
	//following page is displayed when user is not found
	// Navbar
	top("Ooops!");

	//Other page content
	echo "There is no user named '" . $person . "'.";
}

// Footer
bottom();
	
?>