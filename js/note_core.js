function switchUnit(unit) {
    $('#menu-main').fadeOut("500", function () {
        $('#menu-main').empty();
        loadMenu(unit);
    });
    $('#main-text').fadeOut("500", function () {
        $('#main-text').empty();
        loadNote(unit);
    });
    history.replaceState(null, null, `//notes.marstanjx.com/${NOTESET}/chapter/${unit}/`);
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
        // close menu when click a link on the menu
        $("a").on("click", function () {
            console.log($(this));
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
            const lecture = window.location.href.split('#')[1];
            if (lecture !== undefined)
                scrollToLecture(lecture);
        });
        updateField();

        // lecture link click
        $('.current-chapter a').click(function (event) {
            let targetname = $(this).attr('href').substring(1);
            console.log(targetname);
            scrollToLecture(targetname);
        });
        $('#menu-main .chapter-item:not(.current-chapter) a').click(function (event) {
            console.log('not');
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