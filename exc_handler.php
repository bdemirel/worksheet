<?php

function handler($Exception)
{
  die("Exception: " . $Exception->getMessage() . "\n");
}

set_exception_handler('handler');

?>
