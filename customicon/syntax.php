<?php
 
// must be run within DokuWiki
if(!defined('DOKU_INC')) die();
 

class syntax_plugin_customicon extends DokuWiki_Syntax_Plugin {
 
    public function getType() { return 'substition'; }
    public function getPType() { return 'block'; }
    public function getSort() { return 32; }
 
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<icon\s+[^>]*>', $mode, 'plugin_customicon');
    }
 
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        $attributes = trim(substr($match, 5, -1));
        return array($match, $attributes);
    }
 
    public function render($format, Doku_Renderer $renderer, $data) {
        if($format == 'xhtml'){

            list($match, $content) = $data;

            $renderer->doc .= '<i ' . $content . '></i>';
            return true;
        }
        return false;
    }
}