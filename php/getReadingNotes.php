<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/20/2018
 * Time: 4:40 PM
 */

$xml = simplexml_load_file("../notes/reading/notes.xml") or die("Error: Cannot create object");
$att = 'id';
$courses = array();
foreach ($xml->children() as $lecture) {
    $lectureId = (string)$lecture->attributes()->$att;
    $filename = '../notes/reading/';
    $filename .= $lectureId;
    $filename .= '.md';
    $courses [] = $filename;
}
include 'NoteExtension.php';

function readChapter($filename)
{
  $fp = fopen($filename, "r");
  $str = fread($fp, filesize($filename));
  $text = NoteExtension::instance()->text($str);
  echo $text;
  fclose($fp);
}

foreach ($courses as $course) {
  echo '<div class="lecture">';
  readChapter($course);
  echo "</div>";
}
