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
$xml = simplexml_load_file("../notes/n3/notes.xml") or die("Error: Cannot create object");
$att = 'id';
$courses = array();
foreach ($xml->children() as $chapter) {
  $chapterId1 = (string)($chapter->attributes()->$att);
  if(!$all && $chapterId1 != $chapterId) continue;
  foreach ($chapter as $class) {
    $classId = (string)$class->attributes()->$att;
    foreach ($class as $lecture) {
      $lectureId = (string)$lecture->attributes()->$att;
      $filename = '../notes/n3/';
      $filename .= $chapterId1;
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
  $text = NoteExtension::instance()->text($str);
  $text = str_replace("@path", "../notes/n3/img", $text);
  $text = str_replace("@audio", "../notes/n3/audio", $text);
  echo $text;
  fclose($fp);
}

foreach ($courses as $course) {
  echo '<div class="lecture">';
  readChapter($course);
  echo "</div>";
}
