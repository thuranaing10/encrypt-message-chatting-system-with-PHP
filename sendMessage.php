<?php

include "config.php";
session_start();
if($_POST)
{
	$name=$_SESSION['name'];
    $msg=trim($_POST['msg']);
	$password = $_POST['password'];
	if(!$msg=="" && $password== "12345"){
    
	$sql="INSERT INTO `chat`(`name`, `message`) VALUES ('".$name."', '".$msg."')";
	}
	else{
		echo '<script language="Javascript">
                alert("Your key is wrong....!")
                location.replace("chatpage.php")
                </script>';
	//header('Location: chatpage.php');
	}
	$query = mysqli_query($conn,$sql);
	if($query)
	{
		header('Location: chatpage.php');
	}
	else
	{
		echo "Something went wrong";
	}
	
	}
?>