<?php
/**
* 
* Integrates Kunena forum structure into Joomap-Tree in a proper way with valid links.
* ---------------------------------------------------------------------------------------
* @Joomap Add-on
* @author Mr. Ayan Debnath, INDIA. (a.k.a iosoft)
* @email futureiosoft@yahoo.co.uk (I do reply queries but please don't SPAM me)
* @version 1.0 kunena.plugin.php 4.53KB 2009-02-20
* @Copyright (C) 2007 - 2008 Future iOsoft Technology,INDIA. All rights reserved.
* @This work is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 2.5 India License.
* @license http://creativecommons.org/licenses/by-nc-nd/2.5/in/
*
*
* Based on Kunena Component 1.0.7 and above.
* @Copyright (C) 2008 - 2009 Kunena Team All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
* 
* -- and --
* 
* Based on Joomap 2.x and above
* @author Daniel Grothe
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://koder.de/projekte/joomap/
*
**/

/////////////////////////////////////////////////////////////////
// just put me in /administrator/components/com_joomap/plugins/
/////////////////////////////////////////////////////////////////

// no direct access
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

/**
* This timeFrame variable controls how much old (year) forum threads Joomap will display in its sitemap.
* Usage:
*	$timeFrame=0;   This will not show any threads of the forum at all.
*                   This is for specially those who wants to show categories only, but not the threads.
*
*	$timeFrame=1;     This will show only those forum threads which are 1 year old.
*	$timeFrame=2;     This will show only those forum threads which are 2 years old.
*	$timeFrame=9999;  This will show only those forum threads which are 9999 year old. Actually all the threads.
*
* Note:
* ======
* It is recommended that you don't set value above 5, 
* else the sitemap will become very big with links to very old topics.
* Keep the default value to 2, as in this time (2 years), 
* your links will be crawl and indexed by search bots, so, links will be saved in Search engine databases.
* Users looking for old post can easily find it using search engines like Google, Yahoo, MSN etc.
*
**/
/////////////////////////////////////////////////////
define("timeFrame", 2); // (0 to 9999) integer only
/////////////////////////////////////////////////////

// Register with the Joomap Plugin Manager
$tmp = new Joomap_kunena;
JoomapPlugins::addPlugin($tmp);

/* Handles Kunena forum structure */
class Joomap_kunena
{
	/* Check if we are responsible for this kind of content */
	function isOfType( &$joomla, &$parent )
	{
		if( (strpos($parent->link, 'option=com_kunena')) && !(strpos($parent->link, 'func=showcat'))) return true;
		return false;
	} /* end of function */

	/* Returns category / forum tree structure */
	function &getTree( &$joomap, &$parent ) 
	{
		global $database;
		$list = array();
		
		/* You can change the SQL statement below depending upon what Categories you want to show */
        $query = "SELECT id as cat_id, name as cat_title, ordering FROM #__fb_categories WHERE parent>0 AND published!=0 AND parent IN (SELECT id from #__fb_categories WHERE published!=0) ORDER BY name";
	     
		$database->setQuery($query);
		$cats = $database->loadObjectList();								
		
		/* get list of categories */
		foreach($cats as $cat) 
		{
			$node = new stdclass; 
			$node->id = $parent->id;
			$node->name = $cat->cat_title;
			$node->link = $parent->link.'&amp;func=showcat&amp;catid='.$cat->cat_id;
			$node->tree = array();
			$list[$cat->cat_id] = $node;
		}
		
		$t=(int)constant("timeFrame");
		/* You can change the SQL statement below depending upon what threads you want to show */
		$querymsg = "SELECT id as forum_id, catid as cat_id, subject as forum_name, `time` as modified FROM #__fb_messages WHERE parent=0 AND moved=0 AND hold=0 AND DATEDIFF( CURDATE( ), FROM_UNIXTIME(`time`)) <=(365*'{$t}') ORDER BY subject";
		$database->setQuery($querymsg);
		$forums = $database->loadObjectList();
		
		//get list of forums
		foreach($forums as $forum)
		{
			// sort forums into categories
			if( !isset($list[$forum->cat_id]) ) continue;
			
			$node = new stdclass;
			$node->id = $parent->id;
			$node->name = $forum->forum_name;
			$node->modified = intval($forum->modified);
			$node->link = $parent->link.'&amp;func=view&amp;id='.$forum->forum_id.'&amp;catid='.$forum->cat_id;
			$list[$forum->cat_id]->tree[] = $node;
		}

		return $list;
		
	} /* end of getTree function */
} /* end of class */
?>