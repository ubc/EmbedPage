<?php
/**
 * Main include file for the Embed Page extension of MediaWiki.
 * This code is released under the GNU General Public License.
 *
 *
 * Purpose:
 *     creates an embed option for wiki pages works with the https://github.com/ubc/EmbedTracker  extension
 *
 * Usage:
 *     wfLoadExtension( 'EmbedPage' ); in LocalSettings.php
 *
 * @package MediaWiki
 * @subpackage Extensions
 * @author Scott McMillan (email: user "scott.mcmillan" at ubc.ca)  UBC Centre for Teaching, Learning and Technology
 *         Pan Luo
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 */

class EmbedPageHooks {
	/**
	 * Adds the Wiki feed links to the bottom of the toolbox in Monobook or like-minded skins.
	 * @param Skin $pageSkin Instance of skin
	 * @param $sidebar instance of sidebar
	 * @return bool
	 */

	public static function onSidebarBeforeOutput(Skin $pageSkin, &$sidebar) {

		global $wgServer, $wgScript;

		if (! $pageSkin) {
			return true;
		}
		$pageTitle = $pageSkin->getTitle();
		if (! $pageTitle) {
			return true;
		}
		$dbKey = $pageTitle->getPrefixedDBkey();

		// urlencode $dbKey twice to handle special characters
		$embedPageCode = "<script type=\"text/javascript\">document.write(\'<script type=\"text/javascript\" charset=\"utf-8\" src=\"$wgServer/extensions/EmbedPage/getPage.php?title=$wgScript/" . urlencode($dbKey) . "&referer=' + document.location.href + ' \"><\/script>\');</script>";
		$embedBox = "<span style=\"font-size:10px;\">Others:</span><textarea style=\"margin:0; width:95%;font-size:10px; height:120px;\" onClick=\"this.select();\">" . $embedPageCode . "</textarea>";
		$embedPageCode1 = "<iframe style=\"height: 100vh; width: 100%; border: 0 none;\" src=\"" . $wgServer . $wgScript . "/" . urlencode($dbKey) . "?action=render\"></iframe>";
		$embedBox1 = "<span style=\"font-size:10px;\">Canvas:</span><textarea style=\"margin:0; width:95%;font-size:10px; height:120px;\" onClick=\"this.select();\">" . $embedPageCode1 . "</textarea>";

		$embedAction = 'if($("#article_embed").length == 0) {$("#embed-page").parent().append(\'<div id="article_embed">' . $embedBox1 . '<br/>' . $embedBox . '</div>\');} else {$(\'#article_embed\').toggle();}; return false;';
		$sidebar['TOOLBOX'][] = [
			"id"   => "embed-page",
			"text" => "Embed Page",
			"href" => '#',
			"onClick" => "$embedAction",
		];

		return true;
	}
}
