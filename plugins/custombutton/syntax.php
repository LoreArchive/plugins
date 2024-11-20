<?php
/**
 * 
 * @author Cieron <cirrow@proton.me>
 * @license GPL v2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * 
 */

if (!defined('DOKU_INC')) die();

class syntax_plugin_custombutton extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'container';
    }

    public function getPType() {
        return 'block';
    }

    public function getSort() {
        return 150;
    }

    // override default accepts() method to allow nesting - ie, to get the plugin accepts its own entry syntax
    function accepts($mode) {
        if ($mode == substr(get_class($this), 7)) return true;
        return parent::accepts($mode);
    }

    public function connectTo($mode) {
        // Match any <div ...> pattern
        $this->Lexer->addEntryPattern('<buttonbutton\s+[^>]*>(?=.*?</buttonbutton>)', $mode, 'plugin_custombutton');
    }

    public function postConnect() {
        $this->Lexer->addExitPattern('</buttonbutton>', 'plugin_custombutton');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $attributes = trim(substr($match, 13, -1));

                return array($state, $attributes); // Return attributes without additional processing

            case DOKU_LEXER_UNMATCHED:
                // Handle the content inside <div>...</div>
                return array($state, $match);

            case DOKU_LEXER_EXIT:
                return array($state, '');
        }
        return array();
    }

    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return false;

        list($state, $content) = $data;
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $renderer->doc .= '<button ' . $content . '>';
                break;

            case DOKU_LEXER_UNMATCHED:
                $renderer->doc .= $content;
                break;

            case DOKU_LEXER_EXIT:
                $renderer->doc .= '</button>';
                break;
        }
        return true;
    }
}