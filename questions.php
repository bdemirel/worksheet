<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html($hidden=null, $text=null, $topic=null, $stopic=null, $choices=null, $answer=null)
{
  if ($choices == null)
  {
    $choices = "<input type='text' name='ch1'>
    <input type='button' name='add_ch' value='Add Choices'>";
  }
  $html = "
    <form method='POST'>
      $hidden
      <span>
        Question:
      </span>
      <br>
      <textarea name='text' rows='4' cols='40'>
        $text
      </textarea>
      <br>
      <span>
        Topic:
      </span>
      <input type='text' name='topic' value='$topic'>
      <span>
        Sub-Topic
      </span>
      <input type='text' name='stopic' value='$stopic'>
      <br>
      <span>
        Choice 1:
      </span>
      $choices
      <br>
      <span>
        Answer:
      </span>
      <textarea name='answer' rows='4' cols='40'>
        $answer
      </textarea>
      <input type='submit' name='submit' value='Submit'>
    </form>
  ";
  template($html);
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  html();
}
else if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  $qid = $_POST['qid'];
  $stmt = $dbo -> prepare("SELECT `text`, topic, stopic, answer, cid, choice FROM questions, choices WHERE qid=:qid, qid=question_id");
  $stmt -> bindParam(':qid', $qid);
  $stmt -> execute();
  $row = $stmt -> fetch(PDO::FETCH_ASSOC);
  foreach ($row as $key => $value)
  {
    ${$key} = $value;
  }
  $hidden = "<input type='hidden' name='qid' value='$qid'>";
  $choices = null;
  foreach ($choice as $value) {
    $choices .= "<input type='text' name='ch1' value='$value'>"
  }
  html($hidden, $text, $topic, $stopic, $choices, $answer);
}
else
{
  throw new Exception("Unknown request method!");
}

?>
