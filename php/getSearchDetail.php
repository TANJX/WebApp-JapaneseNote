<?php

$set = $_REQUEST['set'];
$note = $_REQUEST['note'];
$h2 = $_REQUEST['h2'];
$h3 = $_REQUEST['h3'];

$filename = '../notes/' . $set . '/' . $note . '.md';


$fp = fopen($filename, "r");
if (!$fp) exit;


switch ($h2) {
  case "课文":
    // get h2 section
    $type = 2;
    break;
  case "会话":
    // get h2 section
    $type = 2;
    break;
  case "单词":
    // get entry section
    $type = 0;
    break;
  case "语法":
    // get h3 section
    $type = 3;
    break;
  default:
    // get h2 section
    $type = 2;
    break;
}


$start = false;
$str = '';
$end = false;
$line_num = 0;
$start_line = -1;
$end_line = -1;

while (($line = fgets($fp)) !== false) {
  if ($end) {
    break;
  }
  switch ($type) {
    case 0:
      break;
    case 2:
      if (startsWith($line, "## " . $h2)) {
        $start = true;
        $str .= $line;
        $str .= PHP_EOL;
        $start_line = $line_num;
      } else if ($start && startsWith($line, '## ')) {
        $start = false;
        $end = true;
        $end_line = $line_num;
      } else if ($start) {
        $str .= $line;
        $str .= PHP_EOL;
      }
      break;
    case 3:
      if (startsWith($line, "### " . $h3)) {
        $start = true;
        $str .= $line;
        $str .= PHP_EOL;
        $start_line = $line_num;
      } else if ($start && (startsWith($line, '### ') || startsWith($line, '## '))) {
        $start = false;
        $end = true;
        $end_line = $line_num;
      } else if ($start) {
        $str .= $line;
        $str .= PHP_EOL;
      }
      break;
  }
  $line_num++;
}
if ($start_line > 0 && $end_line < 0) {
  $end_line = $line_num;
}

fclose($fp);
require 'NoteExtension.php';
$text = NoteExtension::instance()->text($str);
echo json_encode(array(
    'content' => str_replace("@path", "/notes/" . $set . "/img", $text),
    'start_line' => $start_line,
    'end_line' => $end_line
));


function startsWith($haystack, $needle)
{
  $length = strlen($needle);
  return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }
  return (substr($haystack, -$length) === $needle);
}