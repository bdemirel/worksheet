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
    Worksheets:
  </span>
  <table>
    <thead>
      <th>
        Name
      </th>
      <th>
        Subject
      </th>
      <th>
        Teacher
      </th>
      <th></th>
      <th></th>
      <th></th>
    </thead>
    <tbody>

    </tbody>
  </table>";
  template($html);
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  $rows = null;
  $stmt = $dbo -> prepare("SELECT * FROM worksheets");
  $stmt-> execute();
  $worksheets = $stmt -> fetchAll();
  foreach ($worksheets as $row)
  {
    foreach ($row as $hey => $value)
    {
      ${$key} = $value;
    }
    $rows .= "
      <tr>
        <td>
          $name
        </td>
        <td>
          $stopic / $topic
        </td>
        <td>
          $teacher
        </td>
        <td>
          <input type='submit' name='pdf_{$ws_id}' value='Download/Print'>
        </td>
        <td>
          <input type='submit' name='edit_{$ws_id}' value='Copy&Edit'>
        </td>
        <td>
          <input type='submit' name='del_{$ws_id}' value='Delete'>
        </td>
      </tr>
    ";
  }

  html();
}
else if ($_SERVER[REQUEST_METHOD] == "POST")
{
  foreach ($_POST as $key => $value)
  {
    $type = explode($key, "_")[0];
    $id = explode($key, "_")[1];

    if ($type == "pdf")
    {
      header("location:pdf.php?id={$id}");
    }
    else if ($type == "edit")
    {
      header("location:worksheets.php?id={$id}");
    }
    else if ($type == "del")
    {
      $stmt = $dbo -> prepare("DELETE FROM worksheets WHERE ws_id=:id");
      $stmt -> bindParam(":id", $id);
      $stmt -> execute();
    }
    else
    {
      throw new Exception("Unkown worksheet command!");
    }
  }
}
else
{
  throw new Exception("Unknown request method!");
}

?>
