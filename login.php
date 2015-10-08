<?php

include_once "template.php";
session_start();

if (isset($_SESSION['username']))
{

}
else
{
  $html = "
<form method='post'>
  <input type='text' name='username' placeholder='username'>
  <br>
  <input type='password' name='password' placeholder='password'>
  <br>
  <input type='submit' name='submit' value='Sign In'>
</form>";
  template($html);
}

?>
