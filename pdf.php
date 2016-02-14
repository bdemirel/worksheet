<?php

require_once "db_connect.php";
require_once "mpdf60/mpdf.php";
$mpdf = new mPDF('');
$mpdf -> debug = true;
//error_reporting(0);

$id = $_GET['id'];
$stmt = $dbo -> prepare("SELECT * FROM worksheets, topics WHERE wid = :id AND topic_id = wtoid");
$stmt -> bindParam(':id', $id);
$stmt -> execute();
$result = $stmt -> fetch();
foreach ($result as $key => $value)
{
  ${$key} = $value;
}

if ($type == 'ct')
{
  $testno = explode('.', $testno);
  $term = $testno[0];
  $exam = $testno[1];
  $html = "
<html>
<body>
<style>
table
{
  border-collapse: colapse;
  width: 100%;
}
td
{
  border: 1px solid black;
}
th
{
  width:50%;
}
span
{
  display:inline-block;
  text-align:right;
}
img
{
  width:10%;
}
.toleft
{
  text-align:left;
}
#last
{
  height:20px;
}
.ct_a
{
  height: 400px;
}
</style>
<table>
  <thead>
    <tr>
      <th rowspan='3'>
        <span><img src='inanc.gif' alt='Tevitol Logo'></span>
      </th>
      <th class='toleft'>
        TEV Inanc Turkes High School
      </th>
    </tr>
    <tr>
      <th class='toleft'>
        Year {$grade}, Term {$term}, Exam {$exam}
      </th>
    </tr>
    <tr>
      <th class='toleft'>
        {$topic}
      </th>
    </tr>
    <tr>
      <th id='last'>
      </th>
      <th>
      </th>
    </tr>
  </thead>
  <tbody>";
  $mpdf -> WriteHTML($html);
  //echo $html;

  $stmt = $dbo -> prepare("SELECT `text` FROM questions WHERE qid=:id");
  $questions = explode(',', $questions);
  for ($i=0; $i < count($questions); $i++)
  {
    if ($i%2==0&&$i!=0)
    {
      $html = "</tbody>
      </table>";
      $mpdf -> WriteHTML($html);
      $mpdf -> AddPage();
      $html = "<table>
        <thead>
          <tr>
            <th rowspan='3'>
              <span><img src='inanc.gif' alt='Tevitol Logo'></span>
            </th>
            <th class='toleft'>
              TEV Inanc Turkes High School
            </th>
          </tr>
          <tr>
            <th class='toleft'>
              Year {$grade}, Term {$term}, Exam {$exam}
            </th>
          </tr>
          <tr>
            <th class='toleft'>
              {$topic}
            </th>
          </tr>
          <tr>
            <th id='last'>
            </th>
            <th>
            </th>
          </tr>
        </thead>
        <tbody>";
      $mpdf -> WriteHTML($html);
      echo $html;
    }

    $id = $questions[$i];
    $stmt -> bindParam(':id', $id);
    $stmt -> execute();
    $qtext = $stmt -> fetch();
    $html = "<tr>
      <td colspan='2' class='ct_q'>
        $qtext[0]
      </td>
    </tr>
    <tr>
      <td colspan='2' class='ct_a'>

      </td>
    </tr>";
    $mpdf -> WriteHTML($html);
    //echo $html;
  }

  $html = "  </tbody>
  </table>
  </body>
  </html>";
  $mpdf -> WriteHTML($html);
  $mpdf -> Output();
  //echo $html;
}
else if ($type == 'ws')
{
  $html = "
<html>
<body>
<style>
table
{
  border-collapse: colapse;
  width: 100%;
}
th
{
  width:50%;
}
span
{
  display:inline-block;
  text-align:right;
}
img
{
  width:10%;
}
.toleft
{
  text-align:left;
}
#last
{
  height:20px;
}
.answer
{
  height:150px;
}
.left
{
  border-right: 1px solid black;
}
.right
{
  border-left: 1px solid black;
}
</style>
<table>
  <thead>
    <tr>
      <th rowspan='2'>
        <span><img src='inanc.gif' alt='Tevitol Logo'></span>
      </th>
      <th class='toleft'>
        TEV Inanc Turkes High School
      </th>
    </tr>
    <tr>
      <th class='toleft'>
        Year {$grade} {$topic} Worksheet
      </th>
    </tr>
    <tr>
      <th id='last'>
      </th>
      <th>
      </th>
    </tr>
  </thead>";

  $mpdf -> WriteHTML($html);
  //echo $html;
  $questions = explode(',', $questions);
  //$log = fopen('log.txt', 'w');
  //fwrite($log, 'Log started  ');
  for ($i=0; $i <= count($questions); $i++)
  {
    //fwrite($log, 'Loop started  ');
    $ii = $i+1;
    $temp = $questions[$i];
    $p = $i+4;
    $pp = $p+1;
    $temp2 = $questions[$p];
    $stmt = $dbo -> prepare("SELECT `text` FROM questions WHERE qid=:id");
    $stmt -> bindParam(':id', $temp);
    $stmt -> execute();
    $qtext1 = $stmt -> fetch();
    $stmt -> bindParam(':id', $temp2);
    $stmt -> execute();
    $qtext2 = $stmt -> fetch();
    //fwrite($log, 'DB Query  ');
    $qtext1 = ($qtext1[0]==null)?null:"$ii - $qtext1[0]";
    $qtext2 = ($qtext2[0]==null)?null:"$pp - $qtext2[0]";
    $html = "<tr class='ct_q'>
      <td class='left question'>
        $qtext1
      </td>
      <td class='right question'>
        $qtext2
      </td>
    </tr>
    <tr class='ct_a'>
      <td class='left answer'>
      </td>
      <td class='right answer'>
      </td>
    </tr>";
    $mpdf -> WriteHTML($html);
    //fwrite($log, 'Write two rows  ');
    //echo $html;

    if($i%4==3)
    {
      $i = $i+4;
      if ($i<count($questions))
      {
        //fwrite($log, 'Add page  ');
        $html =   $html = "  </tbody>
          </table>";
        $mpdf -> WriteHTML($html);
        //echo $html;
        $mpdf -> AddPage();
        $html = "<table>
          <thead>
            <tr>
              <th rowspan='2'>
                <span><img src='inanc.gif' alt='Tevitol Logo'></span>
              </th>
              <th class='toleft'>
                TEV Inanc Turkes High School
              </th>
            </tr>
            <tr>
              <th class='toleft'>
                Year {$grade} {$topic} Worksheet
              </th>
            </tr>
            <tr>
              <th id='last'>
              </th>
              <th>
              </th>
            </tr>
          </thead>";
        $mpdf -> WriteHTML($html);
        //echo $html;
      }
    }
  }
  //fclose($log);
  $html = "  </tbody>
  </table>
  </body>
  </html>";
  $mpdf -> WriteHTML($html);
  $mpdf -> Output();
  //echo $html;
}
else
{
  throw new Exception("Unknown test type!");
}
?>
