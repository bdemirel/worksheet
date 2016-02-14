<?php
error_reporting(E_ALL & ~E_NOTICE);
include_once "template.php";
session_start();
ob_start();

if (!isset($_SESSION['username']))
{
  header("location:login.php");
}

function html($rows=null)
{
  $style = "
    <style>
      .qbuttons
      {
        background-color: hsl(217, 90%, 55%);
        color: white;
        border: none;
        border-radius: 3px;
        padding: 2px 5px;
        font-size: 1.1em;
        font-weight: bold;
        position: relative;
        align: right;
        top: 25%;
        margin: 0 5px;
      }
      #worksheets
      {
        background-color: hsl(217, 90%, 55%);
        color: white;
        border: none;
        border-radius: 3px;
        padding: 2px 5px;
        font-size: 1.1em;
        font-weight: bold;
        position: relative;
        top: 25%;
      }
      nav
      {
        text-align: justify;
      }
      nav:after
      {
        content: '';
        display: inline-block;
        width: 100%;
        height: 0;
        font-size:0;
        line-height:0;
      }
      div
      {
        display: inline-block;
      }
      #gotop
      {
        appearance: button;
        -moz-appearance: button;
        -webkit-appearance: button;
        text-decoration: none;
        font: menu;
        color: ButtonText;
        display: inline-block;
        padding: 0px 6px;
        background-color: white;
        color: hsl(217, 90%, 55%);
        border: none;
        border-radius: 3px;
        height: 50%;
        width: 8%;
        font-size: 1.1em;
        font-weight: bold;
        /*position: relative;
        left: 3%;
        top: 25%;*/
      }
    </style>
  ";

  $addition = "
  <a id='gotop' href='#top'>Go To Top</a>";

  $html = "
  <form method='post'>
    <nav>
      <div id='top'>
        <button type='button' id='worksheets' onclick='window.location.href=\"wslist.php\"'>Go To Worksheets</button>
      </div>
      <div>
        <input class='qbuttons' type='submit' name='add' value='Add'>
        <input class='qbuttons' type='submit' name='edit' value='Edit Selected'>
        <input class='qbuttons' type='submit' name='prepare' value='Prepare'>
      </div>
    </nav>
    <br>
    <table>
      <thead>
				<tr>
					<th>
    	      Select
    	    </th>
    	    <th>
    	      Question
    	    </th>
    	    <th>
    	      Topic
    	    </th>
				</tr>
			</thead>
      <tbody>
        $rows
      </tbody>
    </table>
  </form>";
  template($html, $style, 1, $addition);
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
  $rows = null;
  $stmt = $dbo -> prepare("SELECT `text`, `qid`, `topic`, `stopic` FROM questions, topics WHERE toid=topic_id");
  $stmt -> execute();
  $questions = $stmt -> fetchAll(PDO::FETCH_ASSOC);
  /*var_dump($questions);
	echo "tets";*/
	foreach ($questions as $row)
  {
    foreach ($row as $key => $value)
    {
      ${$key} = $value;
    }

    $rows .= "
    <tr>
			<td>
      	<input type='checkbox' name='selected[]' value='$qid'>
    	</td>
    	<td>
      	$text
    	</td>
    	<td>
      $topic / $stopic
			</td>
		</tr>";
  }
  html($rows);
}
else if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  if (isset($_POST['add']))
  {
    header("location:questions.php");
  }
  elseif (isset($_POST['edit'])) {
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
