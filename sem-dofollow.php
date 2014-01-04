<?php
/*
Plugin Name: Do Follow
Plugin URI: http://www.semiologic.com/software/dofollow/
Description: Removes the <a href="http://www.semiologic.com/2005/02/05/prepare-for-more-comment-spam-not-less/">evil nofollow attribute</a> that WordPress adds in comments.
Version: 4.1
Author: Denis de Bernardy, Mike Koepke
Author URI: http://www.getsemiologic.com
Text Domain: sem-dofollow
Domain Path: /lang
License: Dual licensed under the MIT and GPLv2 licenses
*/

/*
Terms of use
------------

This software is copyright Denis de Bernardy & Mike Koepke, and is distributed under the terms of the MIT and GPLv2 licenses.


Hat tips
--------

	* Sebastian Herp <http://sebbi.de>
	* Thomas Parisot <http://oncle-tom.net>
**/

class sem_dofollow {

	public function __construct() {
		add_filter('get_comment_author_link', array($this, 'strip_nofollow'), 15);
		add_filter('comment_text', array($this, 'strip_nofollow'), 15);
		remove_filter('pre_comment_content', 'wp_rel_nofollow', 15);
	}

	/**
	 * strip_nofollow()
	 *
	 * @param string $text
	 * @return string $text
	 **/

	function strip_nofollow($text = '') {
		return preg_replace_callback("/<\s*a\s+(.+?)>/is", array($this, 'strip_nofollow_callback'), $text);
	} # strip_nofollow()


	/**
	 * strip_nofollow_callback()
	 *
	 * @param array $match
	 * @return string $text
	 **/

	function strip_nofollow_callback($match) {
		$attr = $match[1];
		$attr = " $attr ";
		$attr = preg_replace("/
			\s
			rel\s*=\s*(['\"])
			([^\\1]*?\s+)?
			nofollow
			(\s+[^\\1]*?)?
			\\1
			/ix", " rel=$1$2$3$1", $attr);
		$attr = preg_replace("/
			\s
			rel\s*=\s*(['\"])\s*\\1
			/ix", '', $attr);
		$attr = trim($attr);
		return '<a ' . $attr . '>';
	} # strip_nofollow_callback()
}

$sem_dofollow = new sem_dofollow();
