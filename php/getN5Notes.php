<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/20/2018
 * Time: 4:40 PM
 */
$all = false;
$chapterId = $_REQUEST['chapter'];
if ($_REQUEST['chapter'] == '') {
  return;
}
if ($_REQUEST['chapter'] == 'all') {
    $all = true;
}
$xml = simplexml_load_file("../notes/n5/notes.xml") or die("Error: Cannot create object");
$att = 'id';
$courses = array();
foreach ($xml->children() as $chapter) {
  $chapterId1 = (string)($chapter->attributes()->$att);
  if(!$all && $chapterId1 != $chapterId) continue;
  foreach ($chapter as $lecture) {
    $lectureId = (string)$lecture->attributes()->$att;
    $filename = '../notes/n5/';
    $filename .= $lectureId;
    $filename .= '.md';
    $courses [] = $filename;
  }
}
require 'NoteExtension.php';

function readLecture($filename)
{
  $fp = fopen($filename, "r");
  $str = fread($fp, filesize($filename));
  $text = NoteExtension::instance()->text($str);
  echo str_replace("@path", "/notes/n5/img", $text);
  fclose($fp);
}

foreach ($courses as $course) {
  echo '<div class="lecture">';
  readLecture($course);
  echo "</div>";
}
