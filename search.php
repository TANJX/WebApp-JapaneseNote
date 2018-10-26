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
</head>
<body>

<h1>ノート検索</h1>

<form>
  <input type="text" name="search">
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
</form>

<div class="info">
  <p>Type to Start Searching...</p>
</div>

<div class="no-result" hidden>
  <p>No Result.</p>
</div>

<div id="top-result"></div>

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
    (function update() {
        let query = $('input[name="search"]').val();
        let checked = $('#checkbox-1').is(":checked");
        if (!/^[0-9]*$/.test(query) && (last_check !== checked || last_query !== query)) {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    $('#result').html('');
                    $('#top-result').html('');
                    const result = JSON.parse(this.responseText);
                    // console.log(result);
                    let count = 0;
                    if (!checked) {
                        for (count; count < result['results'].length; count++) {
                            if (count > 100) break;
                            let $entry = $("<div class='entry'></div>");
                            $entry.append("<div class='content'>" + result['results'][count]['content'] + "</div>");
                            $entry.append("<div class='title'>" + result['results'][count]['title'] + "</div>");
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