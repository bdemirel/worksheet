<?php

include_once "db_connect.php";
//include_once "exc_handler.php";

function template($html, $style=null, $log=1, $addition=null)
{
  echo "
<!doctype html>
<html>
  <head>
    <title>
      Worksheets
    </title>
    <meta charset='utf8'>
    <link rel='stylesheet' type='text/css' href='style.css'>
    $style
  </head>
  <body>
    $html
    <footer>";
    if ($log==1)
    {
      echo "
      <button type='button' id='logoff' onclick='window.location.href=\"logoff.php\"'>Log Off</button>";
    }
    else
    {
      throw new Exception("Unknown Log State!");
    }
    echo "
      $addition
      <img src='inanc.gif' alt='Inanc' id='logo'>
    </footer>
  </body>
</html>";
}

?>
