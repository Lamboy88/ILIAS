<?php
/*
	+-----------------------------------------------------------------------------+
	| ILIAS open source                                                           |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 1998-2009 ILIAS open source, University of Cologne            |
	|                                                                             |
	| This program is free software; you can redistribute it and/or               |
	| modify it under the terms of the GNU General Public License                 |
	| as published by the Free Software Foundation; either version 2              |
	| of the License, or (at your option) any later version.                      |
	|                                                                             |
	| This program is distributed in the hope that it will be useful,             |
	| but WITHOUT ANY WARRANTY; without even the implied warranty of              |
	| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
	| GNU General Public License for more details.                                |
	|                                                                             |
	| You should have received a copy of the GNU General Public License           |
	| along with this program; if not, write to the Free Software                 |
	| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
	+-----------------------------------------------------------------------------+
*/

/**
* Access key handling
*
* @author Alex Killing <alex.killing@gmx.de>
* @version $Id$
* @ingroup ServicesAccessibility
*/
class ilAccessKey
{
	// function id constants
	const NEXT = 1;
	const PREVIOUS = 2;
	const DELETE = 3;
	const LAST_VISITED = 4;
	const TREE_ON = 5;
	const TREE_OFF = 6;
	const REPLY = 7;
	const FORWARD_MAIL = 8;
	const MARK_ALL_READ = 9;
	
	public static $func_def = array(
		ilAccessKey::NEXT => array(
			"component" => array("global"),
			"lang" => "acc_next"),
		ilAccessKey::PREVIOUS => array(
			"component" => array("global"),
			"lang" => "acc_previous"),
		ilAccessKey::DELETE => array(
			"component" => array("global"),
			"lang" => "acc_delete"),
		ilAccessKey::LAST_VISITED => array(
			"component" => array("global"),
			"lang" => "acc_last_visited"),
		ilAccessKey::TREE_ON => array(
			"component" => array("global"),
			"lang" => "acc_tree_on"),
		ilAccessKey::TREE_OFF => array(
			"component" => array("global"),
			"lang" => "acc_tree_off"),
		ilAccessKey::REPLY => array(
			"component" => array("mail"),
			"lang" => "acc_reply"),
		ilAccessKey::FORWARD_MAIL => array(
			"component" => array("mail"),
			"lang" => "acc_forward_mail"),
		ilAccessKey::MARK_ALL_READ => array(
			"component" => array("frm"),
			"lang" => "acc_mark_all_read")
		);
	
	/**
	* Get all function ids with empty keys
	*
	* @return	array		function id => empty string
	*/
	private static function getAllKeysEmpty()
	{
		$empty_keys = array();
		foreach (self::$func_def as $f => $c)
		{
			$empty_keys[$f] = "";
		}
		
		return $empty_keys;
	}
	
	/**
	* Get Function Name
	*/
	static function getFunctionName($a_func_id)
	{
		global $lng;

		return $lng->txt(self::$func_def[$a_func_id]["lang"]);
	}
	
	/**
	* Get Component Names
	*/
	static function getComponentNames($a_func_id)
	{
		global $lng;

		$c_str = $lim = "";
		foreach (self::$func_def[$a_func_id]["component"] as $c)
		{
			$c_str.= $lim.$lng->txt("acc_comp_".$c);
			$lim = ", ";
		}
		return $c_str;
	}

	
	/**
	* Get access keys for language.
	*
	* @param	string		lang key, "0" means default values
	*/
	static function getKeys($lang_key = "0", $a_ignore_default = false)
	{
		global $ilDB;
		
		$keys = ilAccessKey::getAllKeysEmpty();
		
		// get defaults
		if ($lang_key != "0" && !$a_ignore_default)
		{
			$keys = ilAccessKey::getKeys();
		}
		
		// get keys of selected language
		$set = $ilDB->query("SELECT * FROM acc_access_key ".
			" WHERE lang_key = ".$ilDB->quote($lang_key, "text")
			);
		while ($rec  = $ilDB->fetchAssoc($set))
		{
			$keys[$rec["function_id"]] = $rec["access_key"];
		}
		
		return $keys;
	}
	
	/**
	* Get single access key.
	*
	* @param	string		lang key, "0" means default values
	*/
	static function getKey($a_func_id, $lang_key = "0", $a_ignore_default = false)
	{
		global $ilDB;
		
		// get defaults
		if ($lang_key != "0" && !$a_ignore_default)
		{
			$key = ilAccessKey::getKey($a_func_id);
		}
		
		// get keys of selected language
		$set = $ilDB->query("SELECT * FROM acc_access_key ".
			" WHERE lang_key = ".$ilDB->quote($lang_key, "text").
			" AND function_id = ".$ilDB->quote($a_func_id, "integer")
			);
		if ($rec  = $ilDB->fetchAssoc($set))
		{
			$key = $rec["access_key"];
		}
		
		return $key;
	}

	/**
	* Write keys
	*
	* @param	array	function ids => keys
	*/
	static function writeKeys($a_keys, $a_lang_key = "0")
	{
		global $ilDB;
		
		$ilDB->manipulate("DELETE FROM acc_access_key WHERE ".
			"lang_key = ".$ilDB->quote($a_lang_key, "text")
			);
		
		foreach ($a_keys as $func_id => $acc_key)
		{
			$ilDB->manipulate("INSERT INTO acc_access_key ".
				"(lang_key, function_id, access_key) VALUES (".
				$ilDB->quote($a_lang_key, "text").",".
				$ilDB->quote($func_id, "integer").",".
				$ilDB->quote(strtolower(trim($acc_key)), "text").
				")");
		}
	}
	
}
