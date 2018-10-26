<?php
$noteset = $_REQUEST['notes'];
?>
<!DOCTYPE html>
<!--suppress JSUnusedLocalSymbols -->
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#b7cc54">
  <meta name="msapplication-TileColor" content="#b7cc54">
  <meta name="theme-color" content="#b7cc54">

  <title>
    <?php if ($noteset == 'n5') {
      echo '初級';
    } else if ($noteset == 'n3') {
      echo '中級';
    } else if ($noteset == 'reading') {
      echo '読解';
    }
    ?> | 日本語ノート</title>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-116224796-1"></script>
  <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
          dataLayer.push(arguments);
      }

      gtag('js', new Date());
      gtag('config', 'UA-116224796-1');

  </script>
  <script src="https://use.typekit.net/uzk1een.js"></script>
  <script>try {
          Typekit.load({async: true});
      } catch (e) {
      }
  </script>
  <link rel="stylesheet" href="/lib/bootstrap-material-design.min.css">
  <link rel="stylesheet" href="/css/github.css">
  <link rel="stylesheet" href="/css/notes.css">
  <link rel="stylesheet" href="/css/style.css">
  <?php
  if ($noteset == '') {
    $noteset = 'n3';
  }
  if ($noteset == 'n5') {
    echo '<link rel="stylesheet" href="/css/n5.css">';
  } else if ($noteset == 'n3') {

  } else if ($noteset == 'reading') {
    echo '<link rel="stylesheet" href="/css/reading.css">';
  }
  ?>
  <script src="/lib/jquery-3.2.1.min.js"></script>
  <script src='/lib/jquery-ui.custom.min.js'></script>
  <script src="/js/note_core.js"></script>
  <script src="/js/note_features.js"></script>
</head>

<body>
<script>
  <?php
  echo 'const NOTESET = \'' . $noteset . '\';';
  ?>

  $(function () {
    <?php
    $chapterId = $_REQUEST['chapter'];

    if ($chapterId == '') {
      $chapterId = 1;
    }
    if ($chapterId == 'all') {
      $chapterId = 'all';
      echo 'loadMenu(\'all\');';
      echo 'loadNote(\'all\');';
    } else {
      echo 'loadMenu(' . $chapterId . ');';
      echo 'loadNote(' . $chapterId . ');';
    }
    ?>
  });

</script>
<div class="head-menu">
  <div class="wrapper clearfix">
    <div class="left">
      <?php
      if ($noteset == 'n5') {
        echo '<h1>日本語　初級</h1>';
      } else if ($noteset == 'n3') {
        echo '<h1>日本語　中級</h1>';
      } else if ($noteset == 'reading') {
        echo '<h1>日本語　読解</h1>';
      }
      ?>
    </div>
    <div class="right">
      <div class="control-btn search-btn" onclick="window.open('/search');"><img src="/img/search.svg"></div>
      <div class="control-btn night-btn" onclick="switchNight()"><img src="/img/night.svg"></div>

      <div class="menu-btn" onclick='menuFold()'>
        <div class="burger">
          <div class="burger__patty"></div>
          <div class="burger__patty"></div>
          <div class="burger__patty"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="side-menu menu-off">
  <div class="menu-main">
    <div class="wrapper" id="menu-main"></div>
  </div>
  <div class="menu-links">
    <a href="https://github.com/TANJX/WebApp-JapaneseNote" target="_blank">
      <img src="/img/github.svg" alt="GitHub Logo">
    </a>
    <a href="http://marstanjx.com" target="_blank">
      <img src="/img/mars.svg" alt="Mars Logo">
    </a>
    <p class="shown-text">Japanese Notes 2.4</p>
    <p class="hidden-text">developed by Mars</p>
  </div>
</div>

<div class="content">
  <div class="container" id="main-text"></div>
</div>

<div class="dictionary" style="display: none">
  <img src="/img/dictionary.svg">
</div>

</body>

</html>
