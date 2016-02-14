<?php
include_once "mpdf60/mpdf.php";
$mpdf=new mPDF();
$html="<html>
<body>
<table>
<tr>
<td>
1
</td>
<td>
2
</td>
</tr>
<tr>
<td>
3
</td>
<td>
4
</td>
</tr>
";
$mpdf->WriteHTML($html);
$mpdf->AddPage();
$html = "
<tr>
<td>
5
</td>
<td>
6
</td>
</tr>
<tr>
<td>
7
</td>
<td>
8
</td>
</tr>
</table>
</body>
</html>";
$mpdf->WriteHTML($html);
$mpdf -> Output();
?>
