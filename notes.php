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
  <script src="/lib/jquery-3.2.1.min.js"></script>
  <script src='/lib/jquery-ui.custom.min.js'></script>
  <link rel="stylesheet" href="/lib/bootstrap-material-design.min.css">
  <link rel="stylesheet" href="/css/github.css">
  <link rel="stylesheet" href="/css/notes.css">
  <link rel="stylesheet" href="/css/style.css">
  <?php
  $noteset = $_REQUEST['notes'];

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
</head>

<body>
<script>
    let NOTESET;

    <?php
    echo "$(function () {";
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

    echo '});';

    echo 'NOTESET = \'' . $noteset . '\';';

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
        history.replaceState(null, null, 'http://notes.marstanjx.com/' + NOTESET + '/chapter/' + unit);
        return false;
    }

    function loadMenu(unit) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById("menu-main").innerHTML = this.responseText;
            }
        };
        xmlhttp.addEventListener("load", function (ev) {
            $('#menu-main').fadeIn("500", function () {
            });
            $("a").on("click", function () {
                if (menu) {
                    menuFold();
                }
            });
        });
        if (NOTESET === 'n5')
            xmlhttp.open("GET", "/php/getN5NotesList.php?chapter=" + unit, true);
        else if ((NOTESET === 'n3'))
            xmlhttp.open("GET", "/php/getN3NotesList.php?chapter=" + unit, true);
        else if ((NOTESET === 'reading'))
            xmlhttp.open("GET", "/php/getReadingNotesList.php", true);
        xmlhttp.send();
    }

    function loadNote(unit) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById("main-text").innerHTML = this.responseText;
            }
        };
        xmlhttp.addEventListener("load", function (ev) {
            $('#main-text').delay("300").fadeIn("500", function () {
            });
            updateField();

            // href click
            $('.current-chapter a').click(function (event) {
                event.preventDefault();
                let targetname = $(this).attr('href').substring(1);
                console.log(targetname);
                let target = $('[name="' + targetname + '"]');

                // x screen height
                let distance = Math.abs(target.offset().top - $(document).scrollTop()) / screen.height;

                if (distance < 25) {
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 800 + 50 * (distance - 5), "easeOutExpo", function () {
                        // Callback after animation
                        // Must change focus!
                        target.focus();
                        if (target.is(":focus")) { // Checking if the target was focused
                            return false;
                        } else {
                            target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                            target.focus(); // Set focus again
                        }
                    });
                } else {
                    $('html, body').scrollTop(target.offset().top);
                }
                return false;
            });
            $('#menu-main a').click(function (event) {
                event.preventDefault();
                return false;
            });

            // scroll spy
            $(document).scroll(function () {
                let scroll = $(window).scrollTop();
                let elements = $(".content .lecture");
                let el;
                for (let i = elements.length - 1; i >= 0; i--) {
                    el = $(elements[i]);
                    let offset = el.offset();
                    let pos = offset.top - $(document).scrollTop();
                    if (pos < 350) {
                        let course = el.children().first().attr("name");
                        let courses = $("#menu-main .lecture-item a");
                        for (let j = 0; j < courses.length; j++) {
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

            // night mode img revert
            if (night) {
                $('img:not([alt="Mars Logo"])').css('filter', 'invert(100%)');
            }

        });
        if (NOTESET === 'n5')
            xmlhttp.open("GET", "/php/getN5Notes.php?chapter=" + unit, true);
        else if ((NOTESET === 'n3'))
            xmlhttp.open("GET", "/php/getN3Notes.php?chapter=" + unit, true);
        else if ((NOTESET === 'reading'))
            xmlhttp.open("GET", "/php/getReadingNotes.php", true);
        xmlhttp.send();
    }

    function updateField() {
        let i = 1;
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

    let menu = false;

    function menuFold() {
        if (menu) {
            // close menu
            $('.side-menu').removeClass('menu-on').addClass('menu-off');
            $('.menu-btn').removeClass('btn-on');
            $('.burger').removeClass('burger--close');
            menu = false;
        } else {
            $('.menu-btn').addClass('btn-on');
            $('.side-menu').removeClass('menu-off').addClass('menu-on');
            $('.burger').addClass('burger--close');
            menu = true;
        }
    }

    let night = false;

    function switchNight() {
        if (night) {
            $('head link[href="/css/night.css"]').remove();
            $('img').css('filter', 'invert(0%)');
        } else {
            $('head').append('<link rel="stylesheet" href="/css/night.css">');
            $('img:not([alt="Mars Logo"])').css('filter', 'invert(100%)');
        }
        night = !night;
    }

    $(function () {
        $('.menu-links').hover(function (e) {
            $('.shown-text').animate({
                bottom: "50px"
            }, 500, "easeInOutExpo");
            $('.hidden-text').animate({
                bottom: "50px"
            }, 600, "easeInOutExpo");
        }, function (e) {
            $('.shown-text').animate({
                bottom: "0"
            }, 600, "easeInOutExpo");
            $('.hidden-text').animate({
                bottom: "0"
            }, 500, "easeInOutExpo");
        });
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
      ?></div>
    <div class="right">
      <div class="night-btn" onclick="switchNight()"><img src="/img/night.svg"></div>

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
    <p class="shown-text">Japanese Notes 2.2</p>
    <p class="hidden-text">developed by Mars</p>
  </div>
</div>

<div class="content">
  <div class="container" id="main-text"></div>
</div>

</body>

</html>
