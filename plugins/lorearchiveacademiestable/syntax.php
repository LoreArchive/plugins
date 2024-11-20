<?php

// Must be run within DokuWiki
if(!defined('DOKU_INC')) die();

class syntax_plugin_lorearchiveacademiestable extends DokuWiki_Syntax_Plugin {

    public function getType() { return 'substition'; }
    public function getPType() { return 'block'; }
    public function getSort() { return 50; }

    public function connectTo($mode) {
        // Pattern to match (((academies|School)))
        $this->Lexer->addSpecialPattern('\(\(\(academies\\|[^\)]+\)\)\)', $mode, 'plugin_lorearchiveacademiestable');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        // Extract the school name after "academies|"
        $school = trim(substr($match, 13, -3));
        return array($state, $school);
    }

    public function render($format, Doku_Renderer $renderer, $data) {
        if ($format == 'xhtml') {
            list($state, $school) = $data;
    
            $renderer->doc .= '<table class="academiesTable"><thead><tr><th colspan="7">MAJOR ACADEMIES ACROSS KIVOTOS</th></tr></thead><tbody><tr>';
    
            $schools = [
                'abydos' => ['color' => '#70ecec', 'icon' => 'abyd.png', 'label' => 'Abydos'],
                'gehenna' => ['color' => '#e84c44', 'icon' => 'gehn.png', 'label' => 'Gehenna'],
                'trinity' => ['color' => '#ffb45c', 'icon' => 'trin.png', 'label' => 'Trinity'],
                'millennium' => ['color' => '#5084f4', 'icon' => 'mill.png', 'label' => 'Millennium'],
                'hyakkiyako' => ['color' => '#e8548c', 'icon' => 'hyak.png', 'label' => 'Hyakkiyako'],
                'shanhaijing' => ['color' => '#08c43c', 'icon' => 'shan.png', 'label' => 'Shanhaijing'],
                'redwinter' => ['color' => '#b83c5c', 'icon' => 'redw.png', 'label' => 'Red Winter'],
                'srt' => ['color' => '#4a4e3f', 'icon' => 'srt.png', 'label' => 'SRT'],
                'arius' => ['color' => '#48444c', 'icon' => 'ariu.png', 'label' => 'Arius'],
                'valkyrie' => ['color' => '#a8ace4', 'icon' => 'pasted:20240719-125103.png', 'label' => 'Valkyrie'],
                'otherschools' => ['color' => '#000000', 'icon' => 'schale.png', 'label' => 'Others'],
            ];
    
            foreach ($schools as $key => $schoolData) {
                $highlightColor = '';
                if ($school == $key) {
                    $highlightColor = 'style="border: 2px solid ' . $schoolData['color'] . '"';
                }
            
                $label = ($school == $key) ? '<strong>' . $schoolData['label'] . '</strong>' : $schoolData['label'];
            
                if (!in_array($key, ['srt', 'arius', 'valkyrie', 'otherschools'])) {
                    $renderer->doc .= '<td ' . $highlightColor . '>';
                    $renderer->doc .= '<a href="' . wl("setting:academies:$key") . '">';
                    $renderer->doc .= '<img src="' . ml(':icons:' . $schoolData['icon'], ['w' => '80'], true) . '">';
                    $renderer->doc .= '</a>';
                    $renderer->doc .= '<br>' . $label;
                    $renderer->doc .= '</td>';
                }
            }
    
            // Close the first row and the table
            $renderer->doc .= '</tr>';
    
            // Collapsable button and inner academies table
            $renderer->doc .= '<tr><td colspan="7">';
            $renderer->doc .= '<button class="btn btn-dark iabutton" type="button" data-bs-toggle="collapse" data-bs-target="#innerAcademiesCollapse" aria-expanded="false" aria-controls="innerAcademiesCollapse">';
            $renderer->doc .= 'Other Schools';
            $renderer->doc .= '</button>';
            $renderer->doc .= '<div class="collapse innerAcademiesTable" id="innerAcademiesCollapse"><table><tbody><tr>';
    
            // Render inner academies (with highlight and bold logic applied)
            foreach (['srt', 'arius', 'valkyrie', 'otherschools'] as $key) {
                $highlightColor = '';
                if ($school == $key) {
                    $highlightColor = 'style="border: 2px solid ' . $schools[$key]['color'] . '"';
                }
            
                // Conditionally wrap the label with <strong> if the school matches
                $label = ($school == $key) ? '<strong>' . $schools[$key]['label'] . '</strong>' : $schools[$key]['label'];
            
                $renderer->doc .= '<td ' . $highlightColor . '>';
                $renderer->doc .= '<a href="' . wl("setting:academies:$key") . '">';
                $renderer->doc .= '<img src="' . ml(':icons:' . $schools[$key]['icon'], ['w' => '80'], true) . '">';
                $renderer->doc .= '</a>';
                $renderer->doc .= '<br>' . $label;
                $renderer->doc .= '</td>';
            }
            
    
            // Close the inner table and the div
            $renderer->doc .= '</tr></tbody></table></div>';
            $renderer->doc .= '</tr></tbody></table>';
    
            return true;
        }
        return false;
    }
    
    
}
