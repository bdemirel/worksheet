<?php

function handler($Exception)
{
  die("Exception: ", $exception->getMessage(), "\n");
}

set_exception_handler('handler');

?>
