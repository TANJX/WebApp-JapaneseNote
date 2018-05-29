<!DOCTYPE html>
<!--suppress JSUnusedLocalSymbols -->
<html lang="jp">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">
  <title>Japanese Notes</title>
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
      }</script>
  <script src="lib/jquery-3.2.1.min.js"></script>

  <link rel="stylesheet" href="lib/bootstrap-material-design.min.css">
  <link rel="stylesheet" href="css/github.css">
  <link rel="stylesheet" href="css/notes.css">
  <link rel="stylesheet" href="css/style.css">
  <?php
  $noteset = $_REQUEST['notes'];

  if ($noteset == '') {
    $noteset = 'n3';
  }

  if ($noteset == 'n5') {
    echo '<link rel="stylesheet" href="css/n5.css">';
  } else if ($noteset == 'n3') {

  } else if ($noteset == 'reading') {
    echo '<link rel="stylesheet" href="css/reading.css">';
  }
  ?>
</head>

<body>
<script>

  <?php
  echo "$(function () {";
  $noteset = $_REQUEST['notes'];
  $chapterId = $_REQUEST['chapter'];

  if ($chapterId == '') {
    $chapterId = 1;
  }
  if ($noteset == '') {
    $noteset = 'n3';
  }

  echo 'loadMenu(';
  echo $chapterId;
  echo ');loadNote(';
  echo $chapterId;
  echo ');';
  echo '});';

  echo 'const NOTESET = \'' . $noteset . '\';';

  ?>

  function switchUnit(unit) {
      $('#menu-main').fadeOut("500", function () {
          $('#menu-main').empty();
          loadMenu(unit);
      });
      $('#main-text').fadeOut("500", function () {
          $('#main-text').empty();
          loadNote(unit);
      });
      return false;
  }

  function loadMenu(unit) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
              document.getElementById("menu-main").innerHTML = this.responseText;
          }
      };
      xmlhttp.addEventListener("load", function (ev) {
          $('#menu-main').fadeIn("500", function () {
          });
          $("a").on("click", function () {
              if (menu) {
                  menufold();
              }
          });
      });
      if (NOTESET === 'n5')
          xmlhttp.open("GET", "php/getN5NotesList.php?chapter=" + unit, true);
      else if ((NOTESET === 'n3'))
          xmlhttp.open("GET", "php/getN3NotesList.php?chapter=" + unit, true);
      else if ((NOTESET === 'reading'))
          xmlhttp.open("GET", "php/getReadingNotesList.php", true);
      xmlhttp.send();
  }

  function loadNote(unit) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
              document.getElementById("main-text").innerHTML = this.responseText;
          }
      };
      xmlhttp.addEventListener("load", function (ev) {
          $('#main-text').delay("300").fadeIn("500", function () {
          });
          updateField();
          $(document).scroll(function () {
              var scroll = $(window).scrollTop();
              var elements = $(".content .lecture");
              var el;
              for (var i = elements.length - 1; i >= 0; i--) {
                  el = $(elements[i]);
                  var offset = el.offset();
                  var pos = offset.top - $(document).scrollTop();
                  if (pos < 350) {
                      var course = el.children().first().attr("name");
                      var courses = $("#menu-main .lecture-item a");
                      for (var j = 0; j < courses.length; j++) {
                          el = $(courses[j]);
                          if (el.attr("href") === "#" + course) {
                              if (!el.hasClass("scroll-selected")) {
                                  el.addClass("scroll-selected");
                              }
                          } else if (el.hasClass("scroll-selected")) {
                              el.removeClass("scroll-selected");
                          }
                      }
                      break;
                  }
              }
          });
      });
      if (NOTESET === 'n5')
          xmlhttp.open("GET", "php/getN5Notes.php?chapter=" + unit, true);
      else if ((NOTESET === 'n3'))
          xmlhttp.open("GET", "php/getN3Notes.php?chapter=" + unit, true);
      else if ((NOTESET === 'reading'))
          xmlhttp.open("GET", "php/getReadingNotes.php", true);
      xmlhttp.send();
  }

  function updateField() {
      var i = 1;
      $(".content h1").each(function () {
          $(this).before('<a class="anchor" name="lecture' + i++ + '"></a>');
      });
      i = 1;
      $(".content h2").each(function () {
          $(this).nextUntil("h2, h1").wrapAll('<div class="section"></div>');
          $(this).wrap('<a id="t' + i
              + '" class="fold open" onclick="fold(' + i++ + ')"></a>');
      });
  }

  function fold(i) {
      if ($("#t" + i).is('.open')) {
          // to close
          $("#t" + i).next(".section").css('display', 'none');
          $("#t" + i).removeClass("open");
          $("#t" + i).after('<p class="more">. . .</p>')
      } else {
          // to open
          $("#t" + i).next('.more').remove();
          $("#t" + i).next(".section").css('display', 'block');
          $("#t" + i).addClass("open");
      }
  }

  var menu = false;

  function menufold() {
      if (menu) {
          // close menu
          $('.side-menu').removeClass('menu-on').addClass('menu-off');
          $('.menu-btn').removeClass('btn-on');
          menu = false;
      } else {
          $('.menu-btn').addClass('btn-on');
          $('.side-menu').removeClass('menu-off').addClass('menu-on');
          menu = true;
      }
  }
</script>
<div class="head-menu">
<div class="wrapper clearfix">
<?php
$noteset = $_REQUEST['notes'];

if ($noteset == '') {
  $noteset = 'n3';
}

if ($noteset == 'n5') {
  echo '<h1>日本語　初級</h1>';
} else if ($noteset == 'n3') {
  echo '<h1>日本語　中級</h1>';
} else if ($noteset == 'reading') {
  echo '<h1>日本語　読解</h1>';
}
?>

<div class="menu-btn" onclick='menufold()'></div>
</div>
</div>

<div class="side-menu menu-off">
  <div class="wrapper" id="menu-main"></div>
</div>

<div class="content">
  <div class="container" id="main-text"></div>
</div>

</body>

</html>
