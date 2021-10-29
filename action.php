<?php
/**
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author  peterfromearth <coder@peterfromearth.de>
 */

class action_plugin_abstract extends DokuWiki_Action_Plugin {

	/**
	 * Register the eventhandlers
	 */
    function register(Doku_Event_Handler $controller) {
		//$controller->register_hook('PARSER_METADATA_RENDER', 'AFTER',  $this, '_abstract');
	}
	function _abstract(&$event, $param) {
		dbg($event);

	}
}
