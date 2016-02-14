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
  var_dump($_POST);
  if(isset($stopic))
  {
    $topic = ucwords(strtolower($topic));
    $stopic = ucwords(strtolower($stopic));
    $stmt = $dbo -> prepare("INSERT INTO topics VALUES (null, :topic, :stopic)");
    $stmt -> bindParam(':topic', $topic);
    $stmt -> bindParam(':stopic', $stopic);
    $stmt -> execute();
    $stmt = $dbo -> prepare("SELECT MAX(topic_id) FROM topics");
    $stmt -> execute();
    $toid = $stmt -> fetch();
    $toid = $toid[0];
  }

  if (!isset($qid))
  {
    $stmt = $dbo -> prepare("INSERT INTO questions VALUES(null, :text, :answer, :toid)");
    $stmt -> bindParam(':text', $text);
    $stmt -> bindParam(':answer', $answer);
    $stmt -> bindParam(':toid', $toid);
    $stmt -> execute();
    $stmt = $dbo -> prepare("SELECT MAX(qid) FROM questions");
    $stmt -> execute();
    $qid = $stmt -> fetch();
    $qid = $qid[0];
  }
  else
  {
    $stmt = $dbo -> prepare("UPDATE questions SET `text`=:text WHERE `qid`=:qid");
    $stmt -> bindParam(':id', $qid);
    $stmt -> bindParam(':text', $text);
  /*  $stmt -> bindParam(':answer', $answer);
    $stmt -> bindParam(':toid', $toid);*/
    $stmt -> execute();
    echo "qid".$qid;
    echo "text".$text;
    echo "answer".$answer;
    echo "toid".$toid;
  }
  //save choices
  for ($chno=1; ; $chno++)
  {
    $n = "ch$chno";
    if (!isset(${$n}))
    {
      //echo $qid;
      break;
    }
    //echo 'am';
    $stmt = $dbo -> prepare("INSERT INTO choices VALUES(null, :qid, :choice)");
    $stmt -> bindParam(':qid', $qid);
    $stmt -> bindParam(':choice', ${$n});
    $stmt -> execute();
  }

  //header('location:index.php');
}
else
{
  throw new Exception("Unknown request method!");
}

?>
