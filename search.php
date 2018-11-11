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

<div class="banner">
  <div class="container"><p>検索：<span id="banner-query"></span></p></div>
</div>

<div class="container">
  <h1>ノート検索</h1>
  <form>
    <input type="text" name="search" autofocus
           value="<?php
           $query = $_REQUEST['query'];
           if ($query != null) {
             echo $query;
           }
           ?>">
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

  <div class="more-result" hidden><img src="/img/more.svg" alt="more icon"></div>

  <div id="result"></div>

  <footer>
    <p>Mars Inc.</p>
  </footer>
</div>
<script src="/js/note_search.js"></script>
</body>
</html>