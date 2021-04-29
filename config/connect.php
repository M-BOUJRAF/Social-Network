<?php
ob_start(); //Turns on output buffering 
session_start();
$timezone = date_default_timezone_set("Europe/Paris");
//Connection variables
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'socialNet';
$port = 3308;

try{
	// Connection to data base
	$bdd = new PDO("mysql:host=$servername;port=$port;dbname=$database;charset=utf8",$username,$password);
}catch(Exception $e){
	die('Erreur : '.$e->getMessage());
}
//echo "Connected successfully<br/>";
// $req = $bdd->query("INSERT INTO test VALUES(NULL,'COUCOU')");
// $req->closeCursor();
?>