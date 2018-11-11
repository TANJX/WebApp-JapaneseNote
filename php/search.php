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

    $h2 = '';
    $h3 = '';

    // for each line
    while (($line = fgets($fp)) !== false) {
      $line_count++;

      // get the title
      if (!$first) {
        $title = substr($line, 2, strlen($line) - 2);
        $first = true;
        continue;
      }


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

      // record h2 and ignore result
      if (startsWith($line, '## ')) {
        $h2 = substr($line, 3, strlen($line) - 3);
        $h3 = '';
        continue;
      }
      if (startsWith($line, '### ')) {
        $h3 = substr($line, 4, strlen($line) - 4);
      }

      // remove special characters
      $line_cleared = preg_replace("/_[^_]*__/", "", $line);
      $line_cleared = preg_replace("/[\.,\/#!$%\^&\*;:{}=\-_`~() \[\]]/", "", $line_cleared);
      $line_cleared = mb_ereg_replace("、", "", $line_cleared);
      $line_cleared = mb_ereg_replace("。", "", $line_cleared);
      $line_cleared = mb_ereg_replace("「", "", $line_cleared);
      $line_cleared = mb_ereg_replace("」", "", $line_cleared);
      $line_cleared = mb_ereg_replace("＋", "", $line_cleared);
      $line_cleared = mb_ereg_replace("：", "", $line_cleared);
      $line_cleared = mb_ereg_replace("／", "", $line_cleared);
      $line_cleared = mb_ereg_replace("（", "", $line_cleared);
      $line_cleared = mb_ereg_replace("）", "", $line_cleared);
      // hit
      if (strpos($line_cleared, $query) !== false) {
        // start top
        if (startsWith($line, '### ') || startsWith($line, '#### ')) {
          $str = $line;
          $str .= PHP_EOL;
          $is_top = true;
        } else {
          // normal result
          $text = NoteExtension::instance()->text($line);
          $hit = array(
              'title' => $title,
              'id' => $note_info[0] * 100 + $note_info[1] * 10 + $note_info[2],
              'file' => $note_info[0]. "-". $note_info[1] . "-" . $note_info[2],
              'line' => $line_count,
              'content' => $text,
              'h2' => $h2,
              'h3' => $h3,
          );
          $result[] = $hit;
        }
      }
    }
    // end line loop
    fclose($fp);

    // if top result reaches EOF
    if ($is_top) {
      $text = NoteExtension::instance()->text($str);
      $top [] = array(
          'title' => $title,
          'id' => $note_info[0] * 100 + $note_info[1] * 10 + $note_info[2],
          'line' => $line_count,
          'content' => str_replace("@path", "../notes/" . $_REQUEST['set'] . "/img", $text));
    }
  }
}

usort($result, "cmp");
usort($top, "cmp");

$all = array('top' => $top,
    'results' => $result);

echo json_encode($all);

function cmp($a, $b)
{
  $a_val = $a['id'] * 1000 + $a['line'];
  $b_val = $b['id'] * 1000 + $b['line'];

  return $a_val - $b_val;
}

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