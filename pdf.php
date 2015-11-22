<?php

include_once "db_connect.php";
include_once "/mpdf60/mpdf.php";
$mdpf = new mPDF('');

$id = $_GET['id'];
$stmt = $dbo -> prepare("SELECT * FROM worksheets WHERE ws_id = :id");
$stmt -> bindParam(':id', $id);
$stmt -> execute();
$result = $stmt -> fetch();
foreach ($result as $key => $value)
{
  ${$key} = $value;
}

if ($type == 'ct')
{
  $term = explode($testno, '.')[0];
  $exam = explode($testno, '.')[1];
  $html = "
<html>
<body>
<style>
</style>
<table>
  <thead>
    <th>
      <img src='tevitol.png' alt='Tevitol Logo'>
      TEV Inanc Turkes High School
      <br>
      Year {$grade}, Term {$term}, Exam {$exam}
      <br>
      Topic {$topic}
    </th>
  </thead>
  <tbody>";
  $mpdf -> WriteHTML($html);

  for ($i=0; $i <= count($questions); $i++)
  {
    $stmt = $dbo -> prepare("SELECT `text` FROM questions WHERE qid=:id");
    $stmt -> bindParam(':id', $questions[$i]);
    $stmt -> execute();
    $qtext = $stmt -> fetch();
    $html = "<tr class='ct_q'>
      <td>
        $qtext
      </td>
    </tr>
    <tr class='ct_a'>
      <td>

      </td>
    </tr>";
    $mpdf -> WriteHTML($html);

    if ($i%2==0)
    {
      $mpdf -> AddPage();
    }
  }

  $html = "  </tbody>
  </table>
  </body>
  </html>";
  $mpdf -> WriteHTML($html);
  $mpdf -> Output();
}
else if ($type == 'ws')
{
  $html = "
<html>
<body>
<style>
</style>
<table>
  <thead>
    <th>
      <img src='tevitol.png' alt='Tevitol Logo'>
      TEV Inanc Turkes High School
      <br>
      Year {$grade} Topic {$topic} Worksheet
    </th>
  </thead>
  <tbody>";
  $mpdf -> WriteHTML($html);

  for ($i=0; $i <= count($questions); $i++)
  {
    $stmt = $dbo -> prepare("SELECT `text` FROM questions WHERE qid=:id");
    $stmt -> bindParam(':id', $questions[$i]);
    $stmt -> execute();
    $qtext1 = $stmt -> fetch();
    $stmt -> bindParam(':id', $questions[$i+4]);
    $stmt -> execute();
    $qtext2 = $stmt -> fetch();

    $html = "<tr class='ct_q'>
      <td class='left'>
        $qtext1
      </td>
      <td class='right'>
        $qtext2
      </td>
    </tr>
    <tr class='ct_a'>
      <td class='left'>
      </td>
      <td class='right'>
      </td>
    </tr>";
    $mpdf -> WriteHTML($html);

    if($i%4==0)
    {
      $mpdf -> AddPage();
      $i = $i+4;
    }
  }
  $html = "  </tbody>
  </table>
  </body>
  </html>";
  $mpdf -> WriteHTML($html);
  $mpdf -> Output();
}
else
{
  throw new Exception("Unknown test type!");
}
?>
