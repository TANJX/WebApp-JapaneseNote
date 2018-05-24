<?php
include 'Parsedown.php';

class NoteExtension extends Parsedown
{

  function __construct()
  {
    $this->InlineTypes['^'][] = 'GreenBackground';
    $this->InlineTypes['%'][] = 'YellowBackground';
    $this->InlineTypes['&'][] = 'ExampleCont';
    $this->InlineTypes['&'][] = 'Example';
    $this->InlineTypes['$'][] = 'Important';
    $this->InlineTypes['!'][] = 'OrangeText';

    $this->BlockTypes['/'][] = 'QuickTable';
    $this->BlockTypes['~'][] = 'TextField';

    $this->inlineMarkerList .= '^';
    $this->inlineMarkerList .= '%';
    $this->inlineMarkerList .= '&';
    $this->inlineMarkerList .= '$';
    $this->blockMarkerList .= '&';
    $this->blockMarkerList .= '~';

  }

  protected function inlineOrangeText($excerpt)
  {
    if (preg_match('/^\!(.*?)\!/', $excerpt['text'], $matches)) {
      return array(

        // How many characters to advance the Parsedown's
        // cursor after being done processing this tag.
          'extent' => strlen($matches[0]),
          'element' => array(
              'name' => 'span',
              'text' => $matches[1],
              'attributes' => array(
                  'class' => 'orange-text',
              ),
              'handler' => array(
                  'function' => 'lineElements',
                  'argument' => $matches[1],
                  'destination' => 'elements',
              )
          ),

      );
    }
  }

  protected function inlineGreenBackground($excerpt)
  {
    if (preg_match('/^\^(.*?)$/', $excerpt['text'], $matches)) {
      return array(

        // How many characters to advance the Parsedown's
        // cursor after being done processing this tag.
          'extent' => strlen($matches[0]),
          'element' => array(
              'name' => 'span',
              'text' => $matches[1],
              'attributes' => array(
                  'class' => 'green-bg',
              ),
              'handler' => array(
                  'function' => 'lineElements',
                  'argument' => $matches[1],
                  'destination' => 'elements',
              )
          ),

      );
    }
  }

  protected function inlineYellowBackground($excerpt)
  {
    if (preg_match('/^\%(.*?)$/', $excerpt['text'], $matches)) {
      return array(

        // How many characters to advance the Parsedown's
        // cursor after being done processing this tag.
          'extent' => strlen($matches[0]),
          'element' => array(
              'name' => 'span',
              'text' => $matches[1],
              'attributes' => array(
                  'class' => 'yellow-bg',
              ),
              'handler' => array(
                  'function' => 'lineElements',
                  'argument' => $matches[1],
                  'destination' => 'elements',
              )
          ),

      );
    }
  }

  protected function inlineExample($excerpt)
  {
    if (preg_match('/^&(.*?)$/', $excerpt['text'], $matches)) {
      return array(

        // How many characters to advance the Parsedown's
        // cursor after being done processing this tag.
          'extent' => strlen($matches[0]),
          'element' => array(
              'name' => 'span',
              'text' => $matches[1],
              'attributes' => array(
                  'class' => 'example',
              ),
              'handler' => array(
                  'function' => 'lineElements',
                  'argument' => $matches[1],
                  'destination' => 'elements',
              )
          ),

      );
    }
  }

  protected function inlineImportant($excerpt)
  {
    if (preg_match('/^\$(.*?)$/', $excerpt['text'], $matches)) {
      return array(

        // How many characters to advance the Parsedown's
        // cursor after being done processing this tag.
          'extent' => strlen($matches[0]),
          'element' => array(
              'name' => 'span',
              'text' => $matches[1],
              'attributes' => array(
                  'class' => 'important',
              ),
              'handler' => array(
                  'function' => 'lineElements',
                  'argument' => $matches[1],
                  'destination' => 'elements',
              )
          ),

      );
    }
  }


  protected function inlineExampleCont($excerpt)
  {
    if (preg_match('/^&&(.*?)$/', $excerpt['text'], $matches)) {
      return array(

        // How many characters to advance the Parsedown's
        // cursor after being done processing this tag.
          'extent' => strlen($matches[0]),
          'element' => array(
              'name' => 'span',
              'text' => $matches[1],
              'attributes' => array(
                  'class' => 'example-cont',
              ),
              'handler' => array(
                  'function' => 'lineElements',
                  'argument' => $matches[1],
                  'destination' => 'elements',
              )
          ),

      );
    }
  }

  function lineTable($line)
  {
    $parts = explode("/", $line['body']);
    foreach ($parts as $index => $part) {
      if (($index === 0 || $index === count($parts) - 1) && $part === '') {
        continue;
      }
      $Element = array(
          'name' => 'td',
          'handler' => array(
              'function' => 'lineElements',
              'argument' => $part,
              'destination' => 'elements',
          )
      );
      $Elements [] = $Element;
    }
    return array(
        'name' => 'tr',
        'elements' => $Elements,
    );

  }

  protected function blockQuickTable($line, $block)
  {
    if (preg_match('/^\//', $line['text'], $matches)) {
      $block = array(
          'element' => array(
              'name' => 'table',
              'elements' => array(),
          ),
      );
      $block['element']['elements'] [] = array(
          'name' => 'tbody',
          'elements' => array(),
      );
      $block['element']['elements'][0]['elements'] [] = $this->lineTable($line);
      return $block;
    }
  }

  protected function blockQuickTableContinue($line, $block)
  {
    if (isset($block['complete'])) {
      return;
    }

    // A blank newline has occurred.
    if (isset($block['interrupted'])) {
      $block['complete'] = true;
      return;
    }

    $block['element']['elements'][0]['elements'] [] = $this->lineTable($line);
    return $block;
  }


  protected function blockQuickTableComplete($block)
  {
    return $block;
  }

  protected function blockTextField($line, $block)
  {
    if (preg_match('/^~~/', $line['text'], $matches)) {
      $block = array(
          'element' => array(
              'name' => 'div',
              'elements' => array(),
              'attributes' => array(
                  'class' => 'text-field',
              ),
          ),
      );
      return $block;
    }
  }


  protected function blockTextFieldContinue($line, $block)
  {
    if (isset($block['complete'])) {
      return;
    }

    // Check for end of the block. 
    if (preg_match('/~~/', $line['text'])) {
      $block['complete'] = true;
      return $block;
    }
    $block['element']['elements'] [] = array(
        'name' => 'p',
        'handler' => array(
            'function' => 'lineElements',
            'argument' => $line['body'],
            'destination' => 'elements',
        ),
    );

    return $block;
  }

  /**
   * Appending the word `complete` to the function name will cause this function to be
   * called when the block is marked as *complete* (see the previous method).
   */
  protected function blockTextFieldComplete($block)
  {
    return $block;
  }

}