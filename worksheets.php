<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html($topics, $questions, $type=array('ws'=>null,'ct'=>null), $name=null, $grade=array('pr'=>null,'9'=>null,'10'=>null,'11'=>null,'12'=>null))
{
  $html = "
  <form method='POST' action='save.php'>
    <input type='radio' name='type' value='ws' id='ws' $type['ws']>
    <label for='ws'>
      Worksheet
    </label>
    <br>
    <input type='radio' name='type' value='ct' id='ct' $type['ct']>
    <label for='ct'>
      Common Test
    </label>
    <br>
    <input type='text' name='name' placeholder='Name' value='$name'>
    <br>
    <select name='grade'>
      <option value='pr' $grade[0]>
        Prep
      </option>
      <option value='9' $grade[1]>
        9
      </option>
      <option value='10' $grade[2]>
        10
      </option>
      <option value='11' $grade[3]>
        11
      </option>
      <option value='12' $grade[4]>
        12
      </option>
    </select>
    <br>
    <select name='topic'>
      $topics
    </select>
    <select name='testno' id='testno' style='display:none'>
      <option value='1.1'>
        Term 1 Exam 1
      </option>
      <option value='1.2'>
        Term 1 Exam 2
      </option>
      <option value='1.3'>
        Term 1 Exam 3
      </option>
      <option value='2.1'>
        Term 2 Exam 1
      </option>
      <option value='2.2'>
        Term 2 Exam 2
      </option>
      <option value='2.3'>
        Term 2 Exam 3
      </option>
    </select>
    $questions
    <input type='submit' name='button' value='Save Document'>
  </form>
  <script>
    document.ready(function()
    {
      if (document.getElementsByName('type')[1].checked)
      {
        document.getElementById('testno').style.display = 'inline-block';
      }
    });
  </script>
  ";
  template($html);
}

function topics($selected=null)
{
  $options = null;
  $stmt = $dbo -> prepare("SELECT topic, stopic FROM topics");
  $stmt -> execute();
  $topics = $stmt -> fetchAll();
  foreach ($topics as $row)
  {
    $options .= "
    <option value='{$row['toid']}'";
    if ($row['toid']==$selected)
    {
      $options .= " selected";
    }
    $options .= ">
      $row['topic'] / $row['stopic']
    </option>";
  }
  return $options;
}

function hiddens($questions)
{
  $hiddens = null;
  foreach ($questions as $value)
  {
    $hiddens .= "<input type='hidden' name='questions[]' value='{$value}'>";
  }
  return $hiddens;
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  //edit
  $ws_id = $_GET['id'];
  $stmt = $dbo -> prepare("SELECT * FROM worksheets WHERE wsid = :id");
  $stmt -> bindParam(':id', $ws_id);
  $stmt -> execute();
  $wsheets = $stmt -> fetchAll();
  foreach ($wsheets as $key => $value)
  {
    ${$key} = $value;
  }
  $topics = topics($topic_id);
  $hiddens = hiddens($questions);
  $types[{$type}] = 'checked';
  $grades[{$grade}] = 'selected';
  html($topics, $hiddens, $types, $name, $grades);
}
else if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  //empty page for creating new ws
  $options = topics();
  //data posted from index
  $hiddens = hiddens($_POST['selected']);
  html($options, $hiddens);
}
else
{
  throw new Exception("Unknown request method!");
}

?>
