<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2018/05/27
 * Time: 22:40
 */

$level = $_REQUEST['level'];
$dir = '../notes';
if ($level == 0)
  $dir .= '/n5/';
else
  $dir .= '/n3/';
$notes = scandir($dir);

do {
  $note = $notes[rand(0, sizeof($notes) - 1)];
} while (!preg_match('/md$/', $note));

$lines = file($dir . $note);

$pointers = array();

foreach ($lines as $line_num => $line) {
  if (preg_match('/^&((?!&).)*$/', $line)) {
    $pointers[] = $line_num;
  }
}

$index = $pointers[rand(0, sizeof($pointers) - 1)];

$results = array();
$results [] = $lines[$index];

do {
  $index += 2;
  $line = $lines[$index];
  if (preg_match('/^&&/', $line)) {
    $results [] = $line;
  } else
    break;
} while (true);

include 'NoteExtension.php';
foreach ($results as $line) {
  $text = NoteExtension::instance()->text($line);
  echo $text;
}