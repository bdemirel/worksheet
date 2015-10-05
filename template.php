<?php

include_once "db_connect.php";
//include_once "exc_handler.php";

function template($html)
{
  echo "
  <html>
    <head>
      <title>
        Worksheets
      </title>
      <meta charset='UTF-8'>
      <link rel='stylesheet' type='text/css' href='style.css'>
    </head>
    <body>
      $html
    </body>
  </html>
  ";
}

?>
