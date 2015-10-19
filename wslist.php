<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html()
{
  $html = "";
  template($html);
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  html();
}
else
{
  throw new Exception("Unknown request method!");
}

?>
