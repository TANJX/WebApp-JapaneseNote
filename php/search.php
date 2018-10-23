<?php
require 'NoteExtension.php';

$path = '../notes/' . $_REQUEST['set'] . '/notes.xml';
$query = $_REQUEST['query'];

$xml = simplexml_load_file("../notes/" . $_REQUEST['set'] . "/notes.xml") or die("Error: Cannot create object");

$dir = new DirectoryIterator(dirname($path));

$result = array();
$top = array();

foreach ($dir as $fileinfo) {
  if (!$fileinfo->isDot()) {
    $fp = fopen($fileinfo->getPath() . '/' . $fileinfo->getFilename(), "r");
    if (!$fp) continue;
    if (!endsWith($fileinfo->getFilename(), ".md")) {
      continue;
    }

    $note_info = preg_split('/-/', preg_split('/\./', $fileinfo->getFilename())[0]);

    $title = '';
    $first = false; // if first line -> to record the title
    $line_count = 0;

    $is_top = false; // if in the top result loop
    $str = ''; // temp str for top result

    while (($line = fgets($fp)) !== false) {
      $line_count++;

      // get the title
      if (!$first) {
        $title = substr($line, 2, strlen($line) - 2);
        $first = true;
        continue;
      }

      if (startsWith($line, '## ')) continue;

      // top
      if ($is_top) {
        if (startsWith($line, '## ') || startsWith($line, '### ')) {
          // end top result
          $is_top = false;
          $text = NoteExtension::instance()->text($str);
          $top [] = array(
              'title' => $title,
              'id' => $note_info[0] * 100 + $note_info[1] * 10 + $note_info[2],
              'line' => $line_count,
              'content' => str_replace("@path", "../notes/" . $_REQUEST['set'] . "/img", $text));
        } else {
          $str .= $line;
          $str .= PHP_EOL;
        }
        continue;
      }

      // hit
      if (strpos($line, $query) !== false) {
        // start top
        if (startsWith($line, '### ') || startsWith($line, '#### ')) {
          $str = $line;
          $str .= PHP_EOL;
          $is_top = true;
        } else {
          $text = NoteExtension::instance()->text($line);
          $hit = array(
              'title' => $title,
              'id' => $note_info[0] * 100 + $note_info[1] * 10 + $note_info[2],
              'line' => $line_count,
              'content' => $text);
          $result[] = $hit;
        }
      }
    }
    // end line loop
    fclose($fp);
  }
}

function cmp($a, $b)
{
  $a_val = $a['id'] * 1000 + $a['line'];
  $b_val = $b['id'] * 1000 + $b['line'];

  return $a_val - $b_val;
}

usort($result, "cmp");
usort($top, "cmp");

$all = array('top' => $top,
    'results' => $result);

echo json_encode($all);

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