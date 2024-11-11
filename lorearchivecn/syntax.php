<?php
 
// must be run within DokuWiki
if(!defined('DOKU_INC')) die();
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_lorearchivecn extends DokuWiki_Syntax_Plugin {
 
    public function getType() { return 'substition'; }
    public function getPType() { return 'normal'; }
    public function getSort() { return 32; }
 
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('(((cn)))',$mode,'plugin_lorearchivecn');
    }
 
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        return array($match, $state, $pos);
    }
 
    public function render($format, Doku_Renderer $renderer, $data) {
    // $data is what the function handle return'ed.
        if($format == 'xhtml'){
            /** @var Doku_Renderer_xhtml $renderer */
            $renderer->doc .= '<sup>[<em><a href="' . wl('citation') . '">citation needed</a></em>]</sup>';
            return true;
        }
        return false;
    }
}