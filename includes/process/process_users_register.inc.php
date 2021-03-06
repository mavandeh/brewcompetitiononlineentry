<?php 
/*
 * Module:      process_users_register.inc.php
 * Description: This module does all the heavy lifting for adding a user's info to the "users" and
 *              the "brewer" tables upon registration
 */


// Gather, convert, and/or sanitize info from the form
if (isset($_POST['brewerJudgeID'])) $brewerJudgeID = $_POST['brewerJudgeID'];
else $brewerJudgeID = "";

if (isset($_POST['brewerJudgeMead'])) $brewerJudgeMead = $_POST['brewerJudgeMead'];
else $brewerJudgeMead = "";

if (isset($_POST['brewerJudgeRank'])) $brewerJudgeRank = $_POST['brewerJudgeRank'];
else $brewerJudgeRank = "";

if (isset($_POST['brewerAHA'])) $brewerAHA = $_POST['brewerAHA'];
else $brewerAHA = "";

if (isset($_POST['brewerClubs'])) $brewerClubs = $_POST['brewerClubs'];
else $brewerClubs = "";

if (isset($_POST['brewerPhone2'])) $brewerPhone2 = $_POST['brewerPhone2'];
else $brewerPhone2 = "";

if (isset($_POST['brewerJudgeWaiver'])) $brewerJudgeWaiver = $_POST['brewerJudgeWaiver'];
else $brewerJudgeWaiver = "";

if (isset($_POST['brewerDropOff'])) $brewerDropOff = $_POST['brewerDropOff'];
else $brewerDropOff = "0";

$username = strtolower($_POST['user_name']);
$username2 = strtolower($_POST['user_name2']);

$first_name = ucwords(strtolower($_POST['brewerFirstName']));
$last_name = ucwords(strtolower($_POST['brewerLastName']));
$address = ucwords(strtolower($_POST['brewerAddress']));
$city = ucwords(strtolower($_POST['brewerCity']));

$brewerJudge = $_POST['brewerJudge'];
$brewerSteward = $_POST['brewerSteward'];

if ($brewerJudge == "Y") {
	if (($_POST['brewerJudgeLocation'] != "") && (is_array($_POST['brewerJudgeLocation']))) $location_pref1 = implode(",",$_POST['brewerJudgeLocation']);
	elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerJudgeLocation']))) $location_pref1 = $_POST['brewerJudgeLocation'];
}
else $location_pref1 = "";

if ($brewerSteward == "Y") {
	if (($_POST['brewerStewardLocation'] != "") && (is_array($_POST['brewerStewardLocation']))) $location_pref2 = implode(",",$_POST['brewerStewardLocation']);
	elseif (($_POST['brewerStewardLocation'] != "") && (!is_array($_POST['brewerStewardLocation']))) $location_pref2 = $_POST['brewerStewardLocation'];
}
else $location_pref2 = "";

/*
if ($go == "entrant") {
	$brewerSteward = "N";
	$brewerJudge = "N";
}

if ($go == "judge") {
	$brewerSteward = "N";
	$brewerJudge = "Y";
}

if ($go == "steward") {
	$brewerSteward = "Y";
	$brewerJudge = "N";
}

// Custom Code for AHA NHC
if (NHC) {
	
	include (DB.'common.db.php');
	function open_or_closed($now,$date1,$date2) {
		if ($now < $date1) $output = "0";
		elseif (($now >= $date1) && ($now <= $date2)) $output = "1";
		else $output = "2";
		return $output;
	}
	
	$registration_open = open_or_closed(strtotime("now"),$row_contest_dates['contestRegistrationOpen'],$row_contest_dates['contestRegistrationDeadline']);
	
	if ($registration_open == 1) {
		
	
		$query_user_exists = "SELECT * FROM nhcentrant WHERE email = '$email'";
		$user_exists = mysqli_query($connection,$query_user_exists) or die (mysqli_error($connection));
		$row_user_exists = mysqli_fetch_assoc($user_exists);
		$totalRows_user_exists = mysqli_num_rows($user_exists);
		
		// Email in the nhcentrants table. They have already been warned about its existance. Redirect.
		if ($totalRows_user_exists > 0) {
			//echo $totalRows_user_exists."<br>";
			header(sprintf("Location: %s", $nhc_landing_url."/index.php?msg=5"));
			exit();
		}
		
		
		$aha = $brewerAHA; 
		if ($aha != "") {
			$query_aha_exists = "SELECT COUNT(*) AS count FROM nhcentrant WHERE AHANumber = '$aha'";
			$aha_exists = mysqli_query($connection,$query_aha_exists) or die (mysqli_error($connection));
			$row_aha_exists = mysqli_fetch_assoc($aha_exists);
			
			if ($row_aha_exists['count'] > 0) $aha_exists = TRUE; else $aha_exists = FALSE;
		}
		
		if ($aha == "") $aha_exists = FALSE;
		//echo $aha_exists;
		//exit();
		// If the AHA number is in the DB, redirect.
		if ($aha_exists) {
			setcookie("user_name", $username, 0, "/");
			setcookie("user_name2", $username2, 0, "/");
			setcookie("password", $_POST['password'], 0, "/");
			setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
			setcookie("userQuestionAnswer", $_POST['userQuestionAnswer'], 0, "/");
			setcookie("brewerFirstName", $_POST['brewerFirstName'], 0, "/");
			setcookie("brewerLastName", $last_name, 0, "/");
			setcookie("brewerAddress", $address, 0, "/");
			setcookie("brewerCity", $city, 0, "/");
			setcookie("brewerState", $_POST['brewerState'], 0, "/");
			setcookie("brewerZip", $_POST['brewerZip'], 0, "/");
			setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
			setcookie("brewerPhone1", $_POST['brewerPhone1'], 0, "/");
			setcookie("brewerPhone2", $brewerPhone2, 0, "/");
			setcookie("brewerClubs", $brewerClubs, 0, "/");
			setcookie("brewerAHA", $brewerAHA, 0, "/");
			setcookie("brewerStaff", $_POST['brewerStaff'], 0, "/");
			setcookie("brewerSteward", $brewerSteward, 0, "/");
			setcookie("brewerJudge", $brewerJudge, 0, "/");
			setcookie("brewerDropOff", $_POST['brewerDropOff'], 0, "/");
			setcookie("brewerJudgeLocation", $location_pref1, 0, "/");
			setcookie("brewerStewardLocation", $location_pref2, 0, "/");
			
			//echo "AHA exists!";
			header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&msg=6"));
			exit();
			
		}
		
		// If AHA is blank or doesn't exist, perform other checks and redirect if needed.
		// if (!$aha_exists) {  }
		
		
	}
	
	// ...and proceed normally with registration at the Region level.
	}
	*/
/*
echo $username."<br>";
echo $username2."<br>";
echo $_POST['password']."<br>";
echo $_POST['userQuestion']."<br>";
echo $_POST['userQuestionAnswer']."<br>";
echo $first_name."<br>";
echo $last_name."<br>";
echo $address."<br>";
echo $city."<br>";
echo $_POST['brewerState']."<br>";
echo $_POST['brewerZip']."<br>";
echo $_POST['brewerCountry']."<br>";
echo $_POST['brewerPhone1']."<br>";
echo $brewerPhone2."<br>";
echo $brewerClubs."<br>";
echo $brewerAHA."<br>";
echo $_POST['brewerStaff']."<br>";
echo $brewerSteward."<br>";
echo $brewerJudge."<br>";
echo $_POST['brewerDropOff']."<br>";
echo $location_pref1."<br>";
echo $location_pref2."<br>";
exit;
*/
// CAPCHA check
if ($filter != "admin") {
require_once(INCLUDES.'recaptchalib.inc.php');
$privatekey = "6LdquuQSAAAAAHkf3dDRqZckRb_RIjrkofxE8Knd";
$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
}

if (($view == "default") && ($filter != "admin") && (!$resp->is_valid)) {
	setcookie("user_name", $username, 0, "/");
	setcookie("user_name2", $username2, 0, "/");
	setcookie("password", $_POST['password'], 0, "/");
	setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
	setcookie("userQuestionAnswer", $_POST['userQuestionAnswer'], 0, "/");
	setcookie("brewerFirstName", $first_name, 0, "/");
	setcookie("brewerLastName", $last_name, 0, "/");
	setcookie("brewerAddress", $address, 0, "/");
	setcookie("brewerCity", $city, 0, "/");
	setcookie("brewerState", $_POST['brewerState'], 0, "/");
	setcookie("brewerZip", $_POST['brewerZip'], 0, "/");
	setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
	setcookie("brewerPhone1", $_POST['brewerPhone1'], 0, "/");
	setcookie("brewerPhone2", $brewerPhone2, 0, "/");
	setcookie("brewerClubs", $brewerClubs, 0, "/");
	setcookie("brewerAHA", $brewerAHA, 0, "/");
	setcookie("brewerStaff", $_POST['brewerStaff'], 0, "/");
	setcookie("brewerSteward", $brewerSteward, 0, "/");
	setcookie("brewerJudge", $brewerJudge, 0, "/");
	setcookie("brewerDropOff", $_POST['brewerDropOff'], 0, "/");
	setcookie("brewerJudgeLocation", $location_pref1, 0, "/");
	setcookie("brewerStewardLocation", $location_pref2, 0, "/");
	
	$location = $base_url."index.php?section=".$section."&go=".$go."&msg=4";
	header(sprintf("Location: %s", $location));
	
}

elseif (($view == "default") && ($username != $username2)) {
	setcookie("user_name", $username, 0, "/");
	setcookie("user_name2", $username2, 0, "/");
	setcookie("password", $_POST['password'], 0, "/");
	setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
	setcookie("userQuestionAnswer", $_POST['userQuestionAnswer'], 0, "/");
	setcookie("brewerFirstName", $first_name, 0, "/");
	setcookie("brewerLastName", $last_name, 0, "/");
	setcookie("brewerAddress", $address, 0, "/");
	setcookie("brewerCity", $city, 0, "/");
	setcookie("brewerState", $_POST['brewerState'], 0, "/");
	setcookie("brewerZip", $_POST['brewerZip'], 0, "/");
	setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
	setcookie("brewerPhone1", $_POST['brewerPhone1'], 0, "/");
	setcookie("brewerPhone2", $brewerPhone2, 0, "/");
	setcookie("brewerClubs", $brewerClubs, 0, "/");
	setcookie("brewerAHA", $brewerAHA, 0, "/");
	setcookie("brewerStaff", $_POST['brewerStaff'], 0, "/");
	setcookie("brewerSteward", $brewerSteward, 0, "/");
	setcookie("brewerJudge", $brewerJudge, 0, "/");
	setcookie("brewerDropOff", $_POST['brewerDropOff'], 0, "/");
	setcookie("brewerJudgeLocation", $location_pref1, 0, "/");
	setcookie("brewerStewardLocation", $location_pref2, 0, "/");
	
	if ($filter == "admin") $location =  $base_url."index.php?section=admin&go=entrant&action=register&msg=27";
	else $location = $base_url."index.php?section=".$section."&go=".$go."&msg=5";
	header(sprintf("Location: %s", $location));

} else {

// Check to see if email address is already in the system. If so, redirect.


if (strstr($username,'@'))  {
	
	// Sanity check from AJAX widget
	$query_userCheck = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
	$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
	$row_userCheck = mysqli_fetch_assoc($userCheck);
	$totalRows_userCheck = mysqli_num_rows($userCheck);

	if ($totalRows_userCheck > 0) {
		
		setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
		setcookie("userQuestionAnswer", $_POST['userQuestionAnswer'], 0, "/");
		setcookie("brewerFirstName", $first_name, 0, "/");
		setcookie("brewerLastName", $last_name, 0, "/");
		setcookie("brewerAddress", $address, 0, "/");
		setcookie("brewerCity", $city, 0, "/");
		setcookie("brewerState", $_POST['brewerState'], 0, "/");
		setcookie("brewerZip", $_POST['brewerZip'], 0, "/");
		setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
		setcookie("brewerPhone1", $_POST['brewerPhone1'], 0, "/");
		setcookie("brewerPhone2", $brewerPhone2, 0, "/");
		setcookie("brewerClubs", $brewerClubs, 0, "/");
		setcookie("brewerAHA", $brewerAHA, 0, "/");
		setcookie("brewerStaff", $_POST['brewerStaff'], 0, "/");
		setcookie("brewerSteward", $brewerSteward, 0, "/");
		setcookie("brewerJudge", $brewerJudge, 0, "/");
		setcookie("brewerDropOff", $_POST['brewerDropOff'], 0, "/");
		setcookie("brewerJudgeLocation", $location_pref1, 0, "/");
		setcookie("brewerStewardLocation", $location_pref2, 0, "/");
		
		if ($filter == "admin") header(sprintf("Location: %s", $base_url."index.php?section=admin&go=".$go."&action=register&msg=10"));
		else header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=2"));
		
	  	} else  {
		
		// Add the user's creds to the "users" table
		$password = md5($_POST['password']);
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($password);
		
		if ($filter == "admin") {
			
		}
		
		$insertSQL = sprintf("INSERT INTO $users_db_table (user_name, userLevel, password, userQuestion, userQuestionAnswer, userCreated) VALUES (%s, %s, %s, %s, %s, %s)", 
                       GetSQLValueString($username, "text"),
					   GetSQLValueString($_POST['userLevel'], "text"),
                       GetSQLValueString($hash, "text"),
					   GetSQLValueString($_POST['userQuestion'], "text"),
					   GetSQLValueString($_POST['userQuestionAnswer'], "text"),
					   "NOW( )"					   
					   );
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		//echo $insertSQL."<br />";
		// Get the id from the "users" table to insert as the uid in the "brewer" table
		$query_user= "SELECT * FROM $users_db_table WHERE user_name = '$username'";
		$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
		$row_user = mysqli_fetch_assoc($user);
	
		// Add the user's info to the "brewer" table
	  	// Numbers 999999994 through 999999999 are reserved for NHC applications.
		if (($brewerAHA < "999999994") || ($brewerAHA == "")) {
			
			$insertSQL = sprintf("INSERT INTO $brewer_db_table (
			  uid,
			  brewerFirstName, 
			  brewerLastName, 
			  brewerAddress, 
			  brewerCity,
			  
			  brewerState, 
			  brewerZip,
			  brewerCountry,
			  brewerPhone1, 
			  brewerPhone2,
			   
			  brewerClubs, 
			  brewerEmail, 
			  brewerSteward, 
			  brewerJudge,
			  brewerJudgeID,
			  
			  brewerJudgeMead,
			  brewerJudgeRank,
			  brewerJudgeLocation,
			  brewerStewardLocation,
			  brewerAHA,
			  
			  brewerJudgeWaiver,
			  brewerDropOff,
			  brewerStaff
			  
			) VALUES (
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s
			)",
						   GetSQLValueString($row_user['id'], "int"),
						   GetSQLValueString($first_name, "text"),
						   GetSQLValueString($last_name, "text"),
						   GetSQLValueString($address, "text"),
						   GetSQLValueString($city, "text"),
						   GetSQLValueString($_POST['brewerState'], "text"),
						   GetSQLValueString($_POST['brewerZip'], "text"),
						   GetSQLValueString($_POST['brewerCountry'], "text"),
						   GetSQLValueString($_POST['brewerPhone1'], "text"),
						   GetSQLValueString($brewerPhone2, "text"),
						   GetSQLValueString($brewerClubs, "text"),
						   GetSQLValueString($username, "text"),
						   GetSQLValueString($brewerSteward, "text"),
						   GetSQLValueString($brewerJudge, "text"),
						   GetSQLValueString($brewerJudgeID, "text"),
						   GetSQLValueString($brewerJudgeMead, "text"),
						   GetSQLValueString($brewerJudgeRank, "text"),
						   GetSQLValueString($location_pref1, "text"),
						   GetSQLValueString($location_pref2, "text"),
						   GetSQLValueString($brewerAHA, "int"),
						   GetSQLValueString($brewerJudgeWaiver, "text"),
						   GetSQLValueString($brewerDropOff, "text"),
						   GetSQLValueString($_POST['brewerStaff'], "text")
						   );
		
		} else {
			
			$insertSQL = sprintf("INSERT INTO $brewer_db_table (
			  uid,
			  brewerFirstName, 
			  brewerLastName, 
			  brewerAddress, 
			  brewerCity,
			   
			  brewerState, 
			  brewerZip,
			  brewerCountry,
			  brewerPhone1, 
			  brewerPhone2,
			  
			  brewerClubs, 
			  brewerEmail, 
			  brewerSteward, 
			  brewerJudge,
			  brewerJudgeID,
			  
			  brewerJudgeMead,
			  brewerJudgeRank,
			  brewerJudgeLocation,
			  brewerStewardLocation,
			  brewerJudgeWaiver,
			  
			  brewerDropOff,
			  brewerStaff
			  
			) VALUES (
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s
			)",
						   GetSQLValueString($row_user['id'], "int"),
						   GetSQLValueString($first_name, "text"),
						   GetSQLValueString($last_name, "text"),
						   GetSQLValueString($address, "text"),
						   GetSQLValueString($city, "text"),
						   GetSQLValueString($_POST['brewerState'], "text"),
						   GetSQLValueString($_POST['brewerZip'], "text"),
						   GetSQLValueString($_POST['brewerCountry'], "text"),
						   GetSQLValueString($_POST['brewerPhone1'], "text"),
						   GetSQLValueString($brewerPhone2, "text"),
						   GetSQLValueString($brewerClubs, "text"),
						   GetSQLValueString($username, "text"),
						   GetSQLValueString($brewerSteward, "text"),
						   GetSQLValueString($brewerJudge, "text"),
						   GetSQLValueString($brewerJudgeID, "text"),
						   GetSQLValueString($brewerJudgeMead, "text"),
						   GetSQLValueString($brewerJudgeRank, "text"),
						   GetSQLValueString($location_pref1, "text"),
						   GetSQLValueString($location_pref2, "text"),
						   GetSQLValueString($brewerJudgeWaiver, "text"),
						   GetSQLValueString($brewerDropOff, "text"),
						   GetSQLValueString($_POST['brewerStaff'], "text")
						   );
		}
		
		/*
		if(NHC) {
			$updateSQL =  sprintf("INSERT INTO nhcentrant (
			uid, 
			firstName, 
			lastName, 
			email,
			AHAnumber,
			regionPrefix
			) 
			VALUES 
			(%s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($row_user['id'], "int"),
							   GetSQLValueString($first_name, "text"),
							   GetSQLValueString($last_name, "text"),
							   GetSQLValueString($username, "text"),
							   GetSQLValueString($brewerAHA, "text"),
							   GetSQLValueString($prefix, "text"));
			
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}
		*/
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		// Stop Gap for random staff assignments
		$updateSQL = sprintf("UPDATE %s  SET  staff_judge='0', staff_judge_bos='0', staff_steward='0', staff_organizer='0', staff_staff='0' WHERE uid=%s",$prefix."staff",$row_user['id']);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		// If email registration info option is yes, email registrant their info...
		if ($_SESSION['prefsEmailRegConfirm'] == 1) {
			
			// Build vars
			$url = str_replace("www.","",$_SERVER['SERVER_NAME']);
			$to_recipient = $first_name." ".$last_name;
			$to_email = $username;
			$subject = $_SESSION['contestName'].": Registration Confirmation";
			
			$message = "<html>" . "\r\n";
			$message .= "<body>" . "\r\n";
			if (isset($_SESSION['contestLogo'])) $message .= "<p align='center'><img src='".$base_url."/user_images/".$_SESSION['contestLogo']."' height='150'></p>";
			$message .= "<p>".$first_name.",</p>";
			if ($filter == "admin") $message .= "<p>An administrator has registerd you for an account on the ".$_SESSION['contestName']."  website. The following is confirmation of the information input:</p>";
			else $message .= "<p>Thank you for registering an account on the ".$_SESSION['contestName']."  website. The following is confirmation of the information you provided:</p>";
			$message .= "<table cellpadding='5' border='0'>";
			$message .= "<tr><td valign='top'><strong>Name:</strong></td><td valign='top'>".$first_name." ".$last_name."</td></tr>";
			$message .= "<tr><td valign='top'><strong>Username (Email):</strong></td><td valign='top'>".$username."</td></tr>";
			//$message .= "<tr><td valign='top'><strong>Password:</strong></td><td valign='top'>".$_POST['password']."</td></tr>";
			$message .= "<tr><td valign='top'><strong>Security Question:</strong></td><td valign='top'>".$_POST['userQuestion']."</td></tr>";
			$message .= "<tr><td valign='top'><strong>Security Question Answer:</strong></td><td valign='top'>".$_POST['userQuestionAnswer']."</td></tr>";
			$message .= "<tr><td valign='top'><strong>Address:</strong></td><td valign='top'>".$address."<br>".$city.", ".$_POST['brewerState']." ".$_POST['brewerZip']."</td></tr>";
			$message .= "<tr><td valign='top'><strong>Phone 1:</strong></td><td valign='top'>".$_POST['brewerPhone1']."</td></tr>";
			if (isset($brewerPhone2)) 		$message .= "<tr><td valign='top'><strong>Phone 2:</strong></td><td valign='top'>".$brewerPhone2."</td></tr>";
			if (isset($brewerClubs)) 		$message .= "<tr><td valign='top'><strong>Club:</strong></td><td valign='top'>".$brewerClubs."</td></tr>";
			if (isset($brewerAHA)) 			$message .= "<tr><td valign='top'><strong>AHA Number:</strong></td><td valign='top'>".$brewerAHA."</td></tr>";
											$message .= "<tr><td valign='top'><strong>Available as Staff?</strong></td><td valign='top'>".$_POST['brewerStaff']."</td></tr>";
			if (isset($brewerJudge)) 		$message .= "<tr><td valign='top'><strong>Available to Judge?</strong></td><td valign='top'>".$brewerJudge."</td></tr>";
			if (isset($brewerSteward)) 		$message .= "<tr><td valign='top'><strong>Available to Steward?</strong></td><td valign='top'>".$brewerSteward."</td></tr>";
			$message .= "</table>";
			$message .= "<p>If any of the above information is incorrect, <a href='".$base_url."index.php?section=login'>log in to your account</a> and make the necessary changes. Best of luck in the competition!</p>";
			$message .= "<p><small>Please do not reply to this email as it is automatically generated. The originating account is not active or monitored.</small></p>";
			$message .= "</body>" . "\r\n";
			$message .= "</html>";
			
			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
			$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
			if (strpos($url, 'brewcomp.com') !== false) $headers .= "From: ".$_SESSION['contestName']." Server <noreply@brewcomp.com>\r\n";
			elseif (strpos($url, 'brewcompetition.com') !== false) $headers .= "From: ".$_SESSION['contestName']." Server <noreply@brewcompetition.com>\r\n";
			else $headers .= "From: ".$_SESSION['contestName']." Server <noreply@".$url. ">\r\n";
			
			$emails = $to_email;
			mail($emails, $subject, $message, $headers);
			
			/*
			echo $url;
			echo $headers."<br>";
			echo $subject."<br>";
			echo $message;
			exit;
			*/
			
		}
		
	
	if ($filter == "default") {
	    // Log in the user and redirect
		
		session_name($prefix_session);
		session_start();
		$_SESSION['loginUsername'] = $username;
		
		// Redirect to Judge Info section if willing to judge
		if (($brewerJudge == "Y") && ($go == "judge")) {
			$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);
			header(sprintf("Location: %s", $base_url."index.php?section=brewer&action=edit&go=judge&psort=judge&id=".$row_brewer['id']));
		}
		else {
			header(sprintf("Location: %s", $base_url."index.php?section=list&msg=7"));
		}
	  } // end if ($filter == "default")
	
	if ($filter == "admin") {
		
		// Redirect to Judge Info section if willing to judge
		if ($brewerJudge == "Y") {
			$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);
			if ($view == "quick") $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=28";
			else $insertGoTo = $base_url."index.php?section=participants=edit&go=admin&filter=".$row_brewer['id']."&psort=judge&id=".$row_brewer['id'];
			header(sprintf("Location: %s", stripslashes($insertGoTo)));
		}
		else { 
			$pattern = array('\'', '"');
			$insertGoTo = $base_url."index.php?section=admin";
			$insertGoTo = str_replace($pattern, "", $insertGoTo); 
			header(sprintf("Location: %s", stripslashes($insertGoTo)));
		}
		//echo $insertGoTo;
	  } // end if ($filter == "admin")
	}
  }
  //if ($filter == "admin") header(sprintf("Location:  %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=3"));
 // else header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=3"));
} // End CAPCHA check

?>