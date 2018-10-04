<?php

$set = $_REQUEST['set'];
$note = $_REQUEST['note'];
$title = '### ' . $_REQUEST['title'];

// echo 'title: ' . $title . '<br>';

$filename = '../notes/' . $set . '/' . $note . '.md';

// echo 'filename: ' . $filename . '<br>';

$fp = fopen($filename, "r");
if (!$fp) exit;

$start = false;
$str = '';
while (($line = fgets($fp)) !== false) {
  if (startsWith($line, $title)) {
    $start = true;
    $str .= $line;
    $str .= PHP_EOL;
  } else if ($start && (startsWith($line, '## ') || startsWith($line, '### '))) {
    $start = false;
    break;
  } else if ($start) {
    $str .= $line;
    $str .= PHP_EOL;
  }
}
fclose($fp);
require 'NoteExtension.php';
$text = NoteExtension::instance()->text($str);
echo str_replace("@path", "../notes/" . $set . "/img", $text);

function startsWith($haystack, $needle) {
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}
