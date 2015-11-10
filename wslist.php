<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  //header("location:login.php");
}

function html()
{
  $html = "
  <span>
    Worksheets:
  </span>
  <form method='POST'>
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
    </table>
  </form>";
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
          <button type='submit' name='pdf' value='{$ws_id}'>
            Download/Print
          </button>
        </td>
        <td>
          <button type='submit' name='edit' value='{$ws_id}'>
            Copy&Edit
          </button>
        </td>
        <td>
          <button type='submit' name='del' value='{$ws_id}'>
            Delete
          </button>
        </td>
      </tr>
    ";
  }

  html();
}
else if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  $id = current($_POST);
  $type = current(array_keys($_POST));

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
    throw new Exception("Unknown worksheet command!");
  }
}
else
{
  throw new Exception("Unknown request method!");
}

?>
