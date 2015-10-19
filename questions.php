<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html()
{
  $html = "
    <span>
      Question:
    </span>
    <br>
    <textarea name="text" rows="4" cols="40">
    </textarea>
    <br>
    <span>
      Topic:
    </span>
    <input type="text" name="topic">
    <span>
      Sub-Topic
    </span>
    <input type="text" name="stopic">
    <br>
    <span>
      Choice 1:
    </span>
    <input type="text" name="ch1">
    <input type="button" name="add_ch" value="Add Choices">
    <br>
    <span>
      Answer:
    </span>
    <textarea name="answer" rows="4" cols="40">
    </textarea>
    <input type="submit" name="submit" value="Submit">
  ";
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
