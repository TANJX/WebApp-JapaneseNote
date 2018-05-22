<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/20/2018
 * Time: 4:40 PM
 */
$targetedChapterId = $_REQUEST['chapter'];
if ($_REQUEST['chapter'] == '') {
  return;
}
$xml = simplexml_load_file("notes/notes.xml") or die("Error: Cannot create object");
$att = 'id';
echo '<ul class="chapter">';
$courses = array();
$i = 1;
foreach ($xml->children() as $chapter) {
  $chapterId = (string)($chapter->attributes()->$att);
  if ($chapterId != $targetedChapterId) {
    echo '<li class="chapter-item"><a href="#" onclick="switchUnit(';
    echo $chapterId;
    echo ')">';
    echo $chapter->attributes()->name;
    echo '</a>';
    continue;
  } else {
    echo '<li class="chapter-item"><a href="#lecture';
    echo $i;
    echo '">';
    echo $chapter->attributes()->name;
    echo '</a>';
    echo '<ul class="class">';
  }
  foreach ($chapter as $class) {
    $classId = (string)$class->attributes()->$att;
    echo '<li class="class-item"><a href="#lecture';
    echo $i;
    echo '">';
    echo $class->attributes()->name;
    echo '</a>';
    echo '<ul class="lecture">';
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
      echo '<li class="lecture-item"><a href="#lecture';
      echo $i++;
      echo '">';
      echo $lecture->name;
      echo '</a>';
    }
    echo '</ul>';
  }
  echo '</ul>';
}
echo '</ul>';