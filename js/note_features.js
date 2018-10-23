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

function scrollToLecture(targetname) {
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