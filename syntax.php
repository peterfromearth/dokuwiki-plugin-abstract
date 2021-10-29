<?php
/**
 * Abstract plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author  peterfromearth <coder@peterfromearth.de>
 */

class syntax_plugin_abstract extends DokuWiki_Syntax_Plugin {

    function getType() { return 'container'; }
    function getPType() { return 'normal'; }
    function getSort() { return 200; }
	function getAllowedTypes() { 
      return array('container', 'formatting', 'substition', 'disabled','protected'); 
   } 

	
	function connectTo($mode) {
        $this->Lexer->addEntryPattern('<abstract[^>]*>(?=.*</abstract>)',$mode,'plugin_abstract');
    }
 
    function postConnect() {
        $this->Lexer->addExitPattern('</abstract>','plugin_abstract');
    }
	
	
    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        switch ($state) {
            case DOKU_LEXER_ENTER : 
				$flags = substr($match, 9, -1);
				$flags = $this->parseFlags($flags);
				return [$state,$flags];
				break;
            case DOKU_LEXER_UNMATCHED :
                return [$state, $match];
				break;
            case DOKU_LEXER_EXIT :
                return [$state, ''];
				break;
        }
        return array();
    }

    function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode == 'metadata') {
            list($state, $match) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER : 
					//reset the output
					$renderer->doc = '';
					break;
                case DOKU_LEXER_UNMATCHED :
                    $renderer->doc .= hsc($match);
                    break;
                case DOKU_LEXER_EXIT :
					// cut off too long abstracts
					$renderer->doc = trim($renderer->doc);
					if (strlen($renderer->doc) > 500)
						$renderer->doc = utf8_substr($renderer->doc, 0, 500).'…';
					$renderer->meta['description']['abstract'] = $renderer->doc;
					
					//$renderer->doc .= '#####ABSTRACEND#####';
                    break;
            }
			return true;
        } else if($mode == 'xhtml'){
           list($state, $match) = $data;
		   switch ($state) {
                case DOKU_LEXER_ENTER : 
					if($match['hide']) 
						$renderer->doc .= '<span style="display:none">';
					else 
						$renderer->doc .= '<span>';
					break;
                case DOKU_LEXER_UNMATCHED :
                    $renderer->doc .= hsc($match);
                    break;
                case DOKU_LEXER_EXIT :
					$renderer->doc .= '</span>';
                    break;
            }
            
        }
        return false;
    }
	
	/*
	 * parseFlags checks for tagfilter flags and returns them as true/false
	 * @param $flags array 
	 * @return array tagfilter flags
	 */
	function parseFlags($flags){
		$flags = explode('&',trim($flags));
		$conf = [
			'hide' => false,
		];
		
		foreach($flags as $k=>$flag) {
			list($flag,$value) = explode('=',$flag,2);
			switch($flag) {
				case 'hide':
					$conf['hide'] = true;
					break;
			}
		}
	
		return $conf;
	}

}
