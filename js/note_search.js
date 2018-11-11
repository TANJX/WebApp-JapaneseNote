let last_query = '';
let last_check = true;
let result;

function getDetail(count) {
    let xmlhtt_inner = new XMLHttpRequest();
    xmlhtt_inner.onreadystatechange = function () {
        if (xmlhtt_inner.readyState === 4 && xmlhtt_inner.status === 200) {
            const response = JSON.parse(xmlhtt_inner.responseText);
            let $entry_ = $(`#entry-${count}`);
            $entry_.children('.content').html(response['content']);
            $entry_.addClass('detail').addClass('detail__loaded');
            for (let i = 0; i < result['results'].length; i++) {
                if (i > 100) break;
                if (i === count) continue;
                if (result['results'][i]['file'] === result['results'][count]['file']) {
                    if (result['results'][i]['line'] >= response['start_line'] &&
                        result['results'][i]['line'] <= response['end_line']) {
                        $(`#entry-${i}`).remove();
                    }
                }
            }
        }
    };
    xmlhtt_inner.open("GET",
        `/php/getSearchDetail.php?set=n3&note=${result['results'][count]['file']}&h2=${result['results'][count]['h2']}&h3=${result['results'][count]['h3']}`
        , true);
    xmlhtt_inner.send();
}

$(document).scroll(function () {
    let scroll = $(window).scrollTop();
    let element = $('input[name="search"]');
    let offset = element.offset();
    let pos = offset.top - scroll;
    if (pos < -110) {
        $('.banner').addClass('show');
    } else {
        $('.banner').removeClass('show');
    }
});

(function update() {
    let query = $('input[name="search"]').val();
    let checked = $('#checkbox-1').is(":checked");
    if (!/^[0-9]*$/.test(query) && (last_check !== checked || last_query !== query)) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                $('#result').html('');
                $('#top-result').html('');
                result = JSON.parse(this.responseText);
                // console.log(result);
                let count = 0;
                if (!checked) {
                    for (count; count < result['results'].length; count++) {
                        if (count > 100) break;
                        let $entry = $(`<div class='entry' id='entry-${count}'></div>`);
                        $entry.append("<div class='content'>" + result['results'][count]['content'] + "</div>");
                        $entry.append("<div class='title'>" + result['results'][count]['title'] + "</div>");
                        let $more_btn = $(`<img src='/img/more.svg' alt='more icon' class='more' onclick='getDetail(${count})'>`);
                        $entry.append($more_btn);
                        $('#result').append($entry);
                    }
                }
                for (count = 0; count < result['top'].length; count++) {
                    if (count > 100) break;
                    let $entry = $("<div class='entry'></div>");
                    $entry.append("<div class='content'>" + result['top'][count]['content'] + "</div>");
                    $entry.append("<div class='title'>" + result['top'][count]['title'] + "</div>");
                    $('#top-result').append($entry);
                }
            }
            $('.info').hide();
            if ($('#result').children().length === 0 && $('#top-result').children().length === 0) {
                $('.no-result').show();
            } else {
                $('.no-result').hide();
            }
            history.replaceState(null, null, 'http://notes.marstanjx.com/search/' + query + '/');
            $('#banner-query').text(query);
        };
        xmlhttp.open("GET", "/php/search.php?set=n3&query=" + query, true);
        xmlhttp.send();

        last_query = query;
        last_check = checked;
    }
    setTimeout(update, 300);
})();