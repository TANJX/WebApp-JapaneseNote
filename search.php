<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ノート検索 | 日本語ノート</title>
  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">
  <link rel="stylesheet" href="/css/search.css">
  <script src="https://use.typekit.net/uzk1een.js"></script>
  <script>try {
          Typekit.load({async: true});
      } catch (e) {
      }
  </script>
  <script src="/lib/jquery-3.2.1.min.js"></script>
  <script src='/lib/jquery-ui.custom.min.js'></script>
  <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
</head>
<body>

<h1>ノート検索</h1>

<form>
  <input type="text" name="search" autofocus>
  <div class="options">
    <div class="mdc-form-field">
      <div class="mdc-checkbox">
        <input type="checkbox"
               class="mdc-checkbox__native-control"
               id="checkbox-1" checked/>
        <div class="mdc-checkbox__background">
          <svg class="mdc-checkbox__checkmark"
               viewBox="0 0 24 24">
            <path class="mdc-checkbox__checkmark-path"
                  fill="none"
                  d="M1.73,12.91 8.1,19.28 22.79,4.59"></path>
          </svg>
          <div class="mdc-checkbox__mixedmark"></div>
        </div>
      </div>
      <label for="checkbox-1">Only Show Top Results</label>
    </div>
  </div>
  <div class="mdc-chip-set mdc-chip-set--choice">
    <div class="mdc-chip" tabindex="0">
      <div class="mdc-chip__text">N3-N2</div>
    </div>
    <div class="mdc-chip" tabindex="1">
      <div class="mdc-chip__text">N5-N4</div>
    </div>
  </div>
</form>

<div class="info">
  <p>Type to Start Searching...</p>
</div>

<div class="no-result" hidden>
  <p>No Result.</p>
</div>

<div id="top-result" class="detail"></div>

<div id="result"></div>

<footer>
  <p>Mars Inc.</p>
</footer>

<script>
    let last_query = '';
    let last_check = true;
    <?php
    $query = $_REQUEST['query'];
    if ($query != null) {
      echo '$(\'input[name="search"]\').val("' . $query . '");';
    }
    ?>
    let result;

    function getDetail(count) {
        let xmlhtt_inner = new XMLHttpRequest();
        xmlhtt_inner.onreadystatechange = function () {
            if (xmlhtt_inner.readyState === 4 && xmlhtt_inner.status === 200) {
                const response = JSON.parse(xmlhtt_inner.responseText);
                console.log(response);
                let $entry_ = $(`#entry-${count}`);
                $entry_.children('.content').html(response['content']);
                $entry_.addClass('detail').addClass('detail__loaded');
                for (let i = 0; i < result['results'].length; i++) {
                    if (i > 100) break;
                    if (i === count) continue;
                    if (result['results'][i]['file'] === result['results'][count]['file']) {
                        console.log(i);
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
                    console.log(result);
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
            };
            xmlhttp.open("GET", "/php/search.php?set=n3&query=" + query, true);
            xmlhttp.send();

            last_query = query;
            last_check = checked;
        }
        setTimeout(update, 300);
    })();
</script>
</body>
</html>