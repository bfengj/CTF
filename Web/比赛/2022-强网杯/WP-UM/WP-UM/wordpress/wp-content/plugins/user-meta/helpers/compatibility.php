<?php
namespace UserMeta;

/**
 * Get blog ids from multisite network
 * get_sites() is available since 4.6.0
 * @since 1.4.2
 */
function getBlogIdsFromSites() {
	$blogIds = [];
	if (function_exists("get_sites")) {
		foreach (get_sites() as $site)
			$blogIds[] = $site->blog_id;
	} else {
		foreach (wp_get_sites() as $site)
			$blogIds[] = $site["blog_id"];
	}

	return $blogIds;
}