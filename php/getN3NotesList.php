<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/20/2018
 * Time: 4:40 PM
 */
$all = false;
$targetedChapterId = $_REQUEST['chapter'];
if ($_REQUEST['chapter'] == '') {
    return;
}
if ($_REQUEST['chapter'] == 'all') {
    $all = true;
}
$xml = simplexml_load_file("../notes/n3/notes.xml") or die("Error: Cannot create object");
$att = 'id';
echo '<ul class="chapter">';
$i = 1;
foreach ($xml->children() as $chapter) {
  $chapterId = (string)($chapter->attributes()->$att);
  if (!$all && $chapterId != $targetedChapterId) {
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
  foreach ($chapter as $class) {
    $classId = (string)$class->attributes()->$att;
    echo '<li class="class-item"><a href="#lecture' . $i . '">';
    echo $class->attributes()->name;
    echo '</a></li>';
    echo '<ul class="lecture">';
    foreach ($class as $lecture) {
      echo '<li class="lecture-item"><a href="#lecture' . $i++ . '">' . $lecture->name . '</a></li>';
    }
    echo '</ul>';
  }
  echo '</ul>';
}
echo '</ul>';