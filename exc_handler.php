<?php

function handler($Exception)
{
  die("Uncaught Exception: ", $exception->getMessage(), "\n");
}

set_exception_handler('handler');

?>
