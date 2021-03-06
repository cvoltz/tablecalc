<?php
// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once (DOKU_PLUGIN . 'syntax.php');
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_tablecalc extends DokuWiki_Syntax_Plugin {
    var $id_index = 0;
    /**
     * return some info
     */
    function getInfo() {
        return array(
            'author' => 'Christopher Voltz',
            'email' => 'cjunk@voltz.ws',
            'date' => '2017-05-01',
            'name' => 'Table Calculations Plugin',
            'desc' => 'Adds limited spreadsheet functionality to tables',
            'url' => 'https://github.com/cvoltz/tablecalc.git'
        );
    }
    function getType() {
        return 'substition';
    }
    function getSort() {
        return 1213;
    }
    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern("~~=[_a-z\ A-Z0-9\%\:\.,\\\/\*\-\+\(\)\&\|#><!=;]*~~", $mode, 'plugin_tablecalc');
    }
    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler) {
        global $ID, $ACT, $INFO;
        /*$keywords = array(' ', '~~', '=', 'alert', 'eval', 'for', 'function', 'if', 'else', 'instanceOf', 'while', 'let', 'new', 'this', 'import', 'var', 'export', 'do');
        $match=strtolower($match);
        foreach($keywords as $keyword) {
        $match = str_replace($keyword, '', $match);
        }
        $this->id_index++;
        return array('formula'=>trim($match), 'divid'=>'__tablecalc'.$this->id_index);*/
        $signs = "-~=+*.,;\/!|&\(\)";
        $pattern = "/[$signs]*([a-zA-Z]+)\(/is";
        $aAllowed = array(
            "tag",
            "tag_row",
            "tag_col",
            "cell",
            "row",
            "col",
            "sum",
            "average",
            "count",
            "countif",
            "nop",
            "round",
            "range",
            "label",
            "min",
            "max",
            "calc",
            "check",
            "compare"
        );
        if (preg_match_all($pattern, $match, $aMatches)) {
            foreach ($aMatches[1] as $f) {
                if (!in_array(strtolower($f) , $aAllowed)) {
                    $match = preg_replace("/([$signs]*)$f\(/is", "\\1nop(", $match);
                }
            }
        }
        $aNop = array(
            '~~=',
            '~~'
        );
        foreach ($aNop as $nop) {
            $match = str_replace($nop, '', $match);
        }
        $match = preg_replace("/#([^\(\);,]+)/", "'\\1'", $match);
        $match = preg_replace("/\(([a-z0-9_]+)\)/", "('\\1')", $match);
        $this->id_index++;
        //var_dump($this->id_index);
        return array(
            'formula' => $match,
            'divid' => '__tablecalc' . $this->id_index,
            'idx' => $this->id_index
        );
    }
    function render($mode, &$renderer, $data) {
        global $INFO, $ID, $conf;
        //var_dump($data);
        if ($mode == 'xhtml') {
            if ($data["idx"] == $this->id_index) {
                $final = "setTimeout(tablecalc_final,0);";
            } else {
                $final = "";
            }
            $renderer->doc.= '<span id="' . $data['divid'] . '"><script type="text/javascript" defer="defer">tablecalc("' . $data['divid'] . '","' . $data['formula'] . '");' . $final . '</script></span>';
            return true;
        }
        return false;
    }
}
?>
