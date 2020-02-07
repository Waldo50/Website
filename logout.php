<?php

	// start the session if it hasn't been already
	// using include_once to make sure it's not starting the session more than once
	include_once('session.php');
	
	// destroy the current session
	session_destroy();
		
	// redirect back to home page
	header('Location: index.php'); 
	
?>