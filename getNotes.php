<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/20/2018
 * Time: 4:40 PM
 */
$chapterId = $_REQUEST['chapter'];
if ($_REQUEST['chapter'] == '') {
  return;
}
$xml = simplexml_load_file("notes/notes.xml") or die("Error: Cannot create object");
$att = 'id';
$courses = array();
$i = 1;
foreach ($xml->children() as $chapter) {
  $chapterId1 = (string)($chapter->attributes()->$att);
  if($chapterId1 != $chapterId) continue;
  foreach ($chapter as $class) {
    $classId = (string)$class->attributes()->$att;
    foreach ($class as $lecture) {
      $lectureId = (string)$lecture->attributes()->$att;
      $filename = 'notes/';
      $filename .= $chapterId;
      $filename .= '-';
      $filename .= $classId;
      $filename .= '-';
      $filename .= $lectureId;
      $filename .= '.md';
      $courses [] = $filename;
    }
  }
}
include 'NoteExtension.php';

function readChapter($filename)
{
  $fp = fopen($filename, "r");
  $str = fread($fp, filesize($filename));
  echo NoteExtension::instance()->text($str);
  fclose($fp);
}

foreach ($courses as $course) {
  echo '<div class="lecture">';
  readChapter($course);
  echo "</div>";
}
