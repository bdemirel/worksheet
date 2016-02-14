<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html($rows)
{
  $html = "
  <style>
  .likeabutton {
      appearance: button;
      -moz-appearance: button;
      -webkit-appearance: button;
      text-decoration: none; font: menu; color: ButtonText;
      display: inline-block; padding: 0px 6px;
  }
  </style>
  <span>
    Worksheets:
  </span>
  <form method='POST'>
    <table>
      <thead>
        <tr>
          <th>
            Name
          </th>
          <th>
            Subject
          </th>
          <th>
            Grade
          </th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        $rows
      </tbody>
    </table>
  </form>";
  template($html);
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  $rows = null;
  $stmt = $dbo -> prepare("SELECT * FROM worksheets, topics WHERE wtoid = topic_id");
  $stmt-> execute();
  $worksheets = $stmt -> fetchAll(PDO::FETCH_ASSOC);
  //var_dump($worksheets);
  foreach ($worksheets as $row)
  {
    //var_dump($row);
    foreach ($row as $key => $value)
    {
      ${$key} = $value;
      //echo $key.':'.${$key};
    }
    //echo $wname;
    $rows .= "
      <tr>
        <td>
          $wname
        </td>
        <td>
          $stopic / $topic
        </td>
        <td>
          $grade
        </td>
        <td>
          <a href='pdf.php?id={$wid}' class='likeabutton' target='_blank'>
            Download/Print
          </a>
        </td>
        <td>
          <button type='submit' name='edit' value='{$wid}'>
            Copy&Edit
          </button>
        </td>
        <td>
          <button type='submit' name='del' value='{$wid}'>
            Delete
          </button>
        </td>
      </tr>
    ";
  }

  html($rows);
}
else if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  $id = current($_POST);
  $type = current(array_keys($_POST));
  //echo $id;
  if ($type == "edit")
  {
    header("location:worksheets.php?id={$id}");
  }
  else if ($type == "del")
  {
    $stmt = $dbo -> prepare("DELETE FROM worksheets WHERE wid=:id");
    $stmt -> bindParam(":id", $id);
    $stmt -> execute();
    header('refresh:0');
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
