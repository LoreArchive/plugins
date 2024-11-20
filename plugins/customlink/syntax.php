<?php

// must be run within DokuWiki
if(!defined('DOKU_INC')) die();

class syntax_plugin_customlink extends DokuWiki_Syntax_Plugin {

    public function getType() { return 'substition'; }
    public function getPType() { return 'block'; }
    public function getSort() { return 32; }

    public function connectTo($mode) {
        // Modify the pattern to match <alink "page" everythingElse>
        $this->Lexer->addSpecialPattern('<alink\s+"[^"]+"\s+[^>]*>', $mode, 'plugin_customlink');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        // Remove "<alink " and ">" and trim whitespace
        $attributesString = trim(substr($match, 7, -1));

        // Capture page ID within double quotes and additional attributes
        preg_match('/^"([^"]+)"\s*(.*)$/', $attributesString, $matches);
        $page = $matches[1];
        $otherAttributes = trim($matches[2]);

        return array($page, $otherAttributes);
    }

    public function render($format, Doku_Renderer $renderer, $data) {
        if ($format == 'xhtml') {
            list($page, $otherAttributes) = $data;
            
            // Generate the URL for the page
            $url = wl($page);

            // Get the page title from metadata
            $metadata = p_get_metadata($page);
            $title = $metadata['title'] ?? $page; // Fallback to page ID if no title
            
            // Render the anchor tag with attributes and page title
            $renderer->doc .= '<a href="' . $url . '" ' . $otherAttributes . '>' . hsc($title) . '</a>';
            return true;
        }
        return false;
    }
}
