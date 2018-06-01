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
$xml = simplexml_load_file("../notes/n5/notes.xml") or die("Error: Cannot create object");
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
    echo '<li class="chapter-item current-chapter"><a href="#lecture';
    echo $i;
    echo '">';
    echo $chapter->attributes()->name;
    echo '</a>';
    echo '<ul class="class">';
  }
  foreach ($chapter as $lecture) {
    $lectureId = (string)$lecture->attributes()->$att;
    $filename = '../notes/n5/';
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