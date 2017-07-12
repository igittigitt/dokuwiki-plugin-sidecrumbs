<?php
/**
 * DokuWiki Plugin sidecrumbs (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Oliver Geisen <oliver@rehkopf-geisen.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class syntax_plugin_sidecrumbs extends DokuWiki_Syntax_Plugin {
    /**
     * @return string Syntax mode type
     */
    public function getType() {
        return 'substition';
    }
    /**
     * @return string Paragraph type
     */
    public function getPType() {
        return 'normal';
    }
    /**
     * @return int Sort order - Low numbers go before high numbers
     */
    public function getSort() {
        return 400;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<sidecrumbs (?:up|down)>',$mode,'plugin_sidecrumbs');
    }

    /**
     * Handle matches of the sidecrumbs syntax
     *
     * @param string          $match   The match of the syntax
     * @param int             $state   The state of the handler
     * @param int             $pos     The position in the document
     * @param Doku_Handler    $handler The handler
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){
	$match = utf8_substr($match, 12, -1);
	$data = array();
	$data['up'] = ($match == 'up');
	return $data;
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string         $mode      Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer  $renderer  The renderer
     * @param array          $data      The data from the handler() function
     * @return bool If rendering was successful.
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        if($mode != 'xhtml') return false;
	if (!isset($_SESSION[DOKU_COOKIE]['bc'])) return false;
	$crumbs = $_SESSION[DOKU_COOKIE]['bc'];
	array_pop($crumbs);
	if ($data['up']) {
	    $crumbs = array_reverse($crumbs);
	}
	$renderer->doc .= '<ul class="nav nav-pills">';
	foreach($crumbs as $id => $name)
	{
	    $renderer->doc .= '<li>' . tpl_link(wl($id), hsc($name), 'title="'.$id.'"', true) . '</li>';
	}
	$renderer->doc .= '</ul>';
	return true;
    }
}

// vim:ts=4:sw=4:et:
