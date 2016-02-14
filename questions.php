<?php

include_once "template.php";
session_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html($topics, $hidden=null, $text=null, $choices=null, $answer=null)
{
  if ($choices == null)
  {
    $choices = "<input type='text' name='ch1'>
    <input type='button' name='add_ch' value='Add Choices'>";
  }
  $html = "
    <form method='POST' action='saveq.php'>
      $hidden
      <span>
        Question:
      </span>
      <br>
      <textarea name='text' rows='4' cols='40'>$text</textarea>
      <br>
      <span>
        Topic / Sub-Topic:
      </span>
      <select name='topic' id='topic'>
        <option value='0'>
          Select a Topic
        </option>
        $topics
        <option value='-1'>
          Other
        </option>
      </select>
      <br id='topicbr'>
      <span>
        Choices:
      </span>
      <br>
      $choices
      <br>
      <span>
        Answer:
      </span>
      <textarea name='answer' rows='4' cols='40'>$answer</textarea>
      <input type='submit' name='submit' value='Submit'>
    </form>
    <script>
      window.onload = function()
      {
        topic = document.getElementById('topic');
        if(topic.value==-1)
        {
          thtml = document.createElement('input');
          thtml.type = 'text';
          thtml.name = 'topic';
          topicbr = document.getElementById('topicbr');
          document.querySelector('form').insertBefore(thtml, topicbr);
          shtml = document.createElement('input');
          shtml.type = 'text';
          shtml.name = 'stopic';
          document.querySelector('form').insertBefore(shtml, topicbr);
        }
        topic.onchange = function()
        {
          console.log(topic.value);
          if(topic.value==-1)
          {
            html = document.createElement('input');
            html.type = 'text';
            html.name = 'topic';
            topicbr = document.getElementById('topicbr');
            document.querySelector('form').insertBefore(html, topicbr);
          }
          else
          {
            document.querySelector('input[type=text][name=topic]').remove();
          }
        }
      }
    </script>";
  template($html);
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

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  $topics = topics();
  html($topics);
}
else if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  $qid = $_POST['selected'][0];
  $stmt = $dbo -> prepare("SELECT `text`, topic_id, answer, choice FROM questions, choices, topics WHERE qid=:qid AND qid=question_id AND toid = topic_id");
  $stmt -> bindParam(':qid', $qid);
  $stmt -> execute();
  $rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);
  $ctexts = array();
  foreach($rows as $row)
  {
    foreach ($row as $key => $value)
    {
      ${$key} = $value;
      if ($choice != ""&&!in_array($choice, $ctexts))
      {
      	$ctexts[] = $choice;
      }
    }
  }
  $topics = topics($topic_id);
  $hidden = "<input type='hidden' name='qid' value='$qid'>";
  $choices = null;
  foreach ($ctexts as $value) {
    $choices .= "<input type='text' name='ch1' value='$value'><br>";
  }
  html($topics, $hidden, $text, $choices, $answer);
}
else
{
  throw new Exception("Unknown request method!");
}

?>
