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
      <input type="checkbox" name="selected[]" value="$qid">
    </tr>
    <tr>
      $text
    </tr>
    <tr>
      $topic / $stopic
    </tr>";
  }
}
else
{
  throw new Exception("Unknown request method!");
}

?>
