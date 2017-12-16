<?php
require_once(dirname(__file__)."/qqConnectAPI.php");
$qc = new QC();
$qc->qq_login();

session_start(120);

if(isset($_SESSION["backurl"]))
{
	unset($_SESSION['backurl']);
}
if($_GET['gourl']==""){
  $_GET['gourl']="/index.html";
}
$_SESSION["backurl"] = $_GET['gourl'];