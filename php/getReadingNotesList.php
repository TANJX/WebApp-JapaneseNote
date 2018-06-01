<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/20/2018
 * Time: 4:40 PM
 */
$xml = simplexml_load_file("../notes/reading/notes.xml") or die("Error: Cannot create object");
$att = 'id';
$i = 1;
foreach ($xml->children() as $lecture) {
  echo '<ul class="class current-chapter">';
  echo '<li class="lecture-item"><a href="#lecture' . $i++ . '">';
  echo $lecture->name;
  echo '</a>';
  echo '</ul>';
}