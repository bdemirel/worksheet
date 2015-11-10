<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  foreach ($_POST as $key => $value) {
    ${$key} = $value;
  }
  $stmt = $dbo -> prepare("SELECT uid FROM users WHERE username = :uname");
  $stmt -> bindParam(':uname', $_SESSION['username']);
  $stmt -> execute();
  $uid = $stmt -> fetch();

  $stmt -> prepare("INSERT INTO worksheets VALUES (null, :name ,:topic, :quest, :grade, :type, :uid)");
  $stmt -> bindParam(':name', $name);
  $stmt -> bindParam(':topic', $topic);
  $stmt -> bindParam(':quest', $questions[]);
  $stmt -> bindParam(':grade', $grade;
  $stmt -> bindParam(':type', $type;
  $stmt -> bindParam(':uid', $uid);
  $stmt -> execute();
}
else
{
  throw new Exception("Unknown request method!");
}

?>
