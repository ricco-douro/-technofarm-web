

<?php


$postData = file_get_contents('php://input');
$getData = $_GET['transaction_id'];

#$xml = simplexml_load_string($postData);

$file1 = 'postdump.txt';
$file2 = 'getdump.txt';

$fp1 = fopen($file1, 'a');
$fp2 = fopen($file2, 'a');

fwrite($fp1, $postData);
fwrite($fp2, $getData);

fclose($fp1);
fclose($fp2);

?>