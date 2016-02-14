<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html($topics, $questions, $type=array(), $name=null, $grade=array(), $test=array())
{
  $style = "
  <style>
  .inl
  {
    display: inline-block;
    text-align: left;
  }
  #form
  {
    text-align: center;
  }
  select
  {
    width: 250px;
    margin: 5px 0;
  }
  input[type='text']
  {
    width: 240px;
  }
  input
  {
    margin: 5px 0;
  }
  </style>";

  $html = "
  <div id='form'>
  <form method='POST' action='savew.php'>
    $questions
    <div class='inl'>
      <input type='radio' name='type' value='ws' id='ws' {$type['ws']}>
      <label for='ws'>
        Worksheet
      </label>
      <br>
      <input type='radio' name='type' value='ct' id='ct' {$type['ct']}>
      <label for='ct'>
        Common Test
      </label>
    </div>
    <br>
    <div class='inl'>
    <input type='text' name='name' placeholder='Worksheet Name' value='$name'>
    </div>
    <br>
    <div class='inl'>
    <select name='grade'>
      <option value='0'>
        Select a Grade
      </option>
      <option value='pr' {$grade['pr']}>
        Prep
      </option>
      <option value='9' {$grade['9']}>
        9
      </option>
      <option value='10' {$grade['10']}>
        10
      </option>
      <option value='11' {$grade['11']}>
        11
      </option>
      <option value='12' {$grade['12']}>
        12
      </option>
    </select>
    <br>
    <select name='topic'>
      <option value='0'>
        Select a Topic
      </option>
      $topics
    </select>
    <br>
    <select name='testno' id='testno' style='display:none'>
      <option value='0'>
        Select Exam No
      </option>
      <option value='1.1' {$test['1.1']}>
        Term 1 Exam 1
      </option>
      <option value='1.2' {$test['1.2']}>
        Term 1 Exam 2
      </option>
      <option value='1.3' {$test['1.3']}>
        Term 1 Exam 3
      </option>
      <option value='2.1' {$test['2.1']}>
        Term 2 Exam 1
      </option>
      <option value='2.2' {$test['2.2']}>
        Term 2 Exam 2
      </option>
      <option value='2.3' {$test['2.3']}>
        Term 2 Exam 3
      </option>
    </select>
    </div>
    <br>
    <div class='inl'>
    <input type='submit' name='button' value='Save Document'>
    </div>
  </form>

  </div>
  <script>
    window.onload = function()
    {
      if(document.querySelector('input[name=\"type\"]:checked'))
      {
        if (document.querySelector('input[name=\"type\"]:checked').value == 'ct')
        {
          document.getElementById('testno').style.display = 'inline-block';
        }
      }

      document.getElementsByName('type')[1].onchange = function()
      {
        document.getElementById('testno').style.display = 'inline-block';
      };
      document.getElementsByName('type')[0].onchange = function()
      {
        document.getElementById('testno').style.display = 'none';
      };
    };
  </script>";
  template($html, $style);
}

function topics($selected=null)
{
	global $dbo;
  $options = null;
  $stmt = $dbo -> prepare("SELECT topic_id, topic, stopic FROM topics");
  $stmt -> execute();
  $topics = $stmt -> fetchAll();
  foreach ($topics as $row)
  {
    $options .= "
    <option value='{$row['topic_id']}'";
    if ($row['topic_id']===$selected)
    {
      $options .= " selected";
    }
    $options .= ">
      {$row['topic']} / {$row['stopic']}
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
  $stmt = $dbo -> prepare("SELECT * FROM worksheets WHERE wid = :id");
  $stmt -> bindParam(':id', $ws_id);
  $stmt -> execute();
  $wsheets = $stmt -> fetch();
  foreach ($wsheets as $key => $value)
  {
    ${$key} = $value;
  }
  $questions = explode(',', $questions);
  $topics = topics($wtoid);
  $hiddens = hiddens($questions);
  $types[${type}] = 'checked';
  $grades[${grade}] = 'selected';
  $tests[${testno}] = 'selected';
  html($topics, $hiddens, $types, $wname, $grades, $tests);
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
