<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  foreach ($_POST as $key => $value)
  {
    ${$key} = $value;
  }

  $str = null;
  foreach($questions as $value)
  {
    $str .= "${value},";
  }
  $str = substr($str, 0, -1);

  $stmt = $dbo -> prepare("INSERT INTO worksheets VALUES (null, :name ,:topic, :quest, :grade, :type, :testno)");
  $stmt -> bindParam(':name', $name);
  $stmt -> bindParam(':topic', $topic);
  $stmt -> bindParam(':quest', $str);
  $stmt -> bindParam(':grade', $grade);
  $stmt -> bindParam(':type', $type);
  $stmt -> bindParam(':testno', $testno);
  $stmt -> execute();

  header('location:wslist.php');
}
else
{
  throw new Exception("Unknown request method!");
}

?>
