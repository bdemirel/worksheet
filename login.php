<?php

include_once "template.php";
session_start();

if (isset($_SESSION['username']))
{
  header("location:index.php");
}

function html($message = null)
{
  $html = "
  <form method='post'>
    <input type='text' name='username' placeholder='username'>
    <br>
    <input type='password' name='password' placeholder='password'>
    <br>
    <input type='submit' name='submit' value='Sign In'>
  </form>
  $message
  ";
  template($html);
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  foreach($_POST as $name => $data)
  {
    ${$name} = $data;
  }
  if ($password == "admin"&&$username== "admin")
  {
    $_SESSION['username'] = $username;
    header('location:index.php');
  }
  else
  {
    $message = 'Problem with username or password';
    html($message);
  }
}
else if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  html();
}
else
{
  throw new Exception("Unknown request method!");
}

?>
