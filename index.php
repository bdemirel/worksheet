<?php
error_reporting(E_ALL & ~E_NOTICE);
include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html($rows=null)
{
  $html = "
  <form method='post'>
    <span>
      Questions:
    </span>
    <input type='submit' name='add' value='Add'>
    <input type='submit' name='edit' value='Edit Selected'>
    <input type='submit' name='prepare' value='Prepare'>
    <br>
    <table>
      <thead>
        <th>
          Select
        </th>
        <th>
          Question
        </th>
        <th>
          Topic
        </th>
      </thead>
      <tbody>
        $rows
      </tbody>
    </table>
  </form>
  ";
  template($html);
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  $row = null;
  $stmt = $dbo ->prepare("SELECT question FROM questions");
  $stmt -> execute();
  $questions = $stmt ->fetchAll(PDO::FETCH_ASSOC);
  foreach ($questions as $row) {
    foreach ($row as $key => $value) {
      ${$key} = $value;
    }

    $rows .= "
    <tr>
      <input type='checkbox' name='selected[]' value='$qid'>
    </tr>
    <tr>
      $text
    </tr>
    <tr>
      $topic / $stopic
    </tr>";
  }

  html($rows);
}
else if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  if (isset($_POST['add'])||isset($_POST['edit']))
  {
    header("HTTP/1.1 307 Temporary Redirect");
    header("location:questions.php");
  }
  else if (isset($_POST['prepare']))
  {
    header("HTTP/1.1 307 Temporary Redirect");
    header("location:worksheets.php");
  }
  else
  {
    throw new Exception('Unknown submit type!');
  }

}
else
{
  throw new Exception("Unknown request method!");
}

?>
