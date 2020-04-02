<?php
// start the sessions
session_start();


//Global Variables
$Session_name_counter = "E9Dnz4zRqqdrhPZ3hTGY4Kry0OfcNi2NeuXZGpQdZhqe1Plas8emEp3RaYiX7IO1fARE5h3I02y9rl9RlLtvRWhAMyPC3poj91Gz";
$Session_name_user = "zRIQdtKLvAUWhmc46CpusfQrnpWR2vLHMAnzsgLhlyF7lW6KToPD0A674JWokJ7DxxuKnnGls28nH5jn0WGMCDgpcbnzxoCYGR6h";
$Session_id_user = "AiS7M5emJjrZw3YlWvPwzKsxwMI6wt07kBgjnMwxenaFI9U0Oc15E9dl1DCEL0CNnmwsM6bxpnVUFWQ3gna5TAEAelMwFTN2oXpI";
$Session_banned = "GE9Rr1eyAz3HyyYrUPhZHwMXZenSU78Wobgu2b4kIWwMpFRGASIfEOBAmVVV7cE0ayZ0JafbDaOzlsRSBRHP4XmCTPCMaEyHSUj7";
$page = $_SERVER['REQUEST_URI'];
$token_session = "token";

// Variable 10 
$FailedAttemps = 10;
// Set global ip variable
$IP = GetIP();
// Set global mac variable
$MAC = GetMAC();

// This function checks if the user is logged in and ifnot redirect to login.php
function CheckIfLoggedIn($Session_name_user,$page)
{
	// Checks the users current page
	if($page == "/Project2.3/login.php")
	{
		// Checks if the session exists and is not empty
		if(isset($_SESSION[$Session_name_user]) && !empty($_SESSION[$Session_name_user])) 
		{
			// Redirect to dashboard.php
			header("Location: Dashboard.php");
		}
	}
	// Checks the users current page
	elseif($page == "/Project2.3/register.php")
	{
		// Checks if the session exists and is not empty
		if(isset($_SESSION[$Session_name_user]) && !empty($_SESSION[$Session_name_user])) 
		{
			// Redirect to dashboard.php
			header("Location: dashboard.php");
		}
	}
	// Checks the users current page
	elseif($page == "/Project2.3/dashboard.php")
	{
		// Checks if the session exists and is not empty
		if(!isset($_SESSION[$Session_name_user]) && empty($_SESSION[$Session_name_user])) 
		{
			// Redirect to dashboard.php
			header("Location: login.php");
		}
	}
	elseif($page == "/Project2.3/request_morgage.php")
	{
		// Checks if the session exists and is not empty
		if(!isset($_SESSION[$Session_name_user]) && empty($_SESSION[$Session_name_user])) 
		{
			// Redirect to dashboard.php
			header("Location: login.php");
		}
	}
	elseif($page == "/Project2.3/review.php")
	{
		// Checks if the session exists and is not empty
		if(!isset($_SESSION[$Session_name_user]) && empty($_SESSION[$Session_name_user])) 
		{
			// Redirect to dashboard.php
			header("Location: login.php");
		}
	}
}
// This function bannes the user when called.
function Ban($IP,$MAC,$Session_banned)
{
	$Encrypted_true = base64_encode("True");
	// Set Banned session to true
	$_SESSION[$Session_banned] = $Encrypted_true;
	// Open banned.txt and write IP to it and new line
	file_put_contents("Banned.txt", PHP_EOL .  $IP, FILE_APPEND);
	// Open banned.txt and write MAC to it and new line
	file_put_contents("Banned.txt", PHP_EOL .  $MAC, FILE_APPEND);
	// Redirect to banned.php
    header("Location: Banned.php");
}
// This function returns the mac adres from the user.
function GetMAC()
{
	// Get mac addres and put it in the $mac variable
	$MAC = exec('getmac');
	$MAC = strtok($MAC, ' ');
	return $MAC;
}
// This function returns ip adres form the user.
function GetIP()
{
	// Get the users ip and put it in the $IP variable
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
	{
    $IP = $_SERVER["HTTP_CLIENT_IP"];
	}
	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{
    $IP = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else
 	{
    $IP = $_SERVER["REMOTE_ADDR"];
	} 
	return $IP;
}
// This checks if ip or mac addres from the user are in the banned.txt file if so then redirect the user to banned.php.
function CheckIfBanned($IP,$MAC,$Session_banned)
{
	if(isset($_SESSION[$Session_banned]) && !empty($_SESSION[$Session_banned])) 
	{
		// Pull encrypted data from session
		$Encrypted_status = $_SESSION[$Session_banned];
		// Decrypt the data
		$Decrypted_status = base64_decode($Encrypted_status);
		// Check if data = true if so then redirect to banned.php
	 if($Decrypted_status == "True")
	 {
	 	header("Location: Banned.php");
	 }
	}
	// Declare file
	$contents = file("Banned.txt");

	// Loop through the file line by line
	foreach($contents as $line) {
		// Check if line is the same as the ip of the user if yes then redirect
		if($line == $IP)
		{
			
			// Redirect to banned.php
			header("Location: Banned.php");
		}
		// Check if line is the same as the mac of the user if yes then redirect
		elseif($line == $MAC)
		{
			// Redirect to banned.php
			header("Location: Banned.php");
		}
	}
}
// This function creates the database
function DatabaseCreation($conn)
{
	// calls data baseconnect function
	$conn = DatabaseConnect();
	//query for database creation
	$result = pg_query($conn, "CREATE TABLE Bank (
		UserId serial PRIMARY KEY,
		Username VARCHAR (50) NOT NULL,
		EMail VARCHAR (50) NOT NULL,
		Password VARCHAR (50) NOT NULL)");
	// Show if query was succesfull
	echo var_dump($result);
}

// This function creates the morgage database 
function DatabaseMortgage($conn)
{
	// calls data baseconnect function
	$conn = DatabaseConnect();
	//query for database creation
	$result = pg_query($conn, "CREATE TABLE Hypotheken (
		HypotheekID serial PRIMARY KEY,
		userid VARCHAR (50) NOT NULL,
		Adres VARCHAR (100) NOT NULL,
		Bedrag VARCHAR (100) NOT NULL,
		Rente VARCHAR (100) NOT NULL,
		Werknemer VARCHAR (100) NOT NULL,
		Rekeningnummer VARCHAR (25) NOT NULL,
		Hypotheek_status VARCHAR (100) NOT NULL)");
	// Show if query was succesfull
	echo var_dump($result);
}

// This function closes a connection to the datababase
function DatabaseClose($conn)
{
	pg_close($conn);
}
// This function opens a connection to the datababase
function DatabaseConnect()
{
	// Declare connection variables
	$conn_string = "host=localhost port=5432 dbname=test user=postgres password=Xyppyp99";
	// Execute connection string
	$conn = pg_connect($conn_string);
	// Return $conn variable
	return $conn;
}
// This function pulls the users username form the session
function GetUsername($Session_name_user)
{
	if(isset($_SESSION[$Session_name_user]) && !empty($_SESSION[$Session_name_user])) 
	{
	// Get encrypted username form session
	$EncryptedUsername = $_SESSION[$Session_name_user];
	// Decrypt the encrypted username
	$DecryptedUsername = base64_Decode($EncryptedUsername);
	// Return the Decrypted username
	return $DecryptedUsername;
	}
	else
	{
		// Redirect to login.php
		header("Location: login.php");
	}
}
// This function checks if the users a admin
Function CheckIfAdmin()
{
	// Set users mac addres into a variable
	$MAC = GetMAC();
	// Set users ip addres into a variable
	$IP = GetIP();
	// Set the file name into a variable
	$filename = 'Admins.txt';
	$contents = file($filename);
	// Set a variable found to false
	$found = false;
	// Loop through the file line by line
	foreach ($contents as $admin)
	{
	// If the current line is the same as the users ip or mac set the found variable then break out of the loop
   	if($admin == $MAC Or  $admin == $IP)
    {
		// set the found variable to true
		$found = true;
		// Break out of the loop
        break;
	}
	}
	// Check if the found variable is true
	if($found == true)
	{
		// if found is true then pop up window with the text welkom
		echo '<script type="text/javascript">alert("Welkom");</script>';
	}
	else
	{
		// if found isnt true then pop up window with the text U bent geen admin vraag dit aan bij een van onze beheerders and then redirect to index.php
		echo '<script type="text/javascript">
		alert("U bent geen admin vraag dit aan bij een van onze beheerders.");
		window.location.href = "index.php";
		</script>';
	}
}
// This function Logs user out
function LogOut($Session_name_user)
{
	// Delete the session username
	unset($_SESSION[$Session_name_user]);
	// Redirect to index.php
	header("Location: index.php");
}
// This function checks if the user is banned or not
function BannedCheckForBannedPage($IP,$MAC,$Session_banned)
{
	// If users session isnt set and empty then redirect to index.php
	if(!isset($_SESSION[$Session_banned]) && empty($_SESSION[$Session_banned])) 
	{
		header("Location: index.php");
	}
	// Declare file
	$contents = file("Banned.txt");
	$found = false;
	// Loop through the file line by line
	foreach($contents as $line) {
		// Check if line is the same as the ip of the user if yes then redirect
		if($line == $IP)
		{
			$found = true;
		}
		// Check if line is the same as the mac of the user if yes then redirect
		elseif($line == $MAC)
		{
			$found = true;
		}
	}
	// If found = true meaning user isnt banned then redirect to index.php
	if($found = false)
	{
		header("Location: index.php");
	}
}
// This function generates
function generate_token($token_session)
{
	if(!isset($_SESSION[$token_session]))
	{
		$_SESSION[$token_session] = md5(uniqid(mt_rand(), true));
	}
}
// This fubction compares the token fomr hidden field with session token
function CompareToken($userid,$Address,$bedrag,$Rekeningnummer,$token_session)
{
	$token1 = $_POST['token'];
	$token2 = $_SESSION[$token_session];
	$token3 = str_replace(' ', '', $token1);
	if(!isset($_POST['token']))
	{
		header("Location: index.php");
	}
	if($token3 == $token2)
	{
		AddMortgage($userid,$Address,$bedrag,$Rekeningnummer);
		header("Location: dashboard.php");
	}
	else
	{
		header("Location: index.php");
	}
}

?>