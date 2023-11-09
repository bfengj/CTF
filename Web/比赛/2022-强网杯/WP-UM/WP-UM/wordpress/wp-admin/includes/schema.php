<?php
/**
 * WordPress Administration Scheme API
 *
 * Here we keep the DB structure and option values.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Declare these as global in case schema.php is included from a function.
 *
 * @global wpdb   $wpdb            WordPress database abstraction object.
 * @global array  $wp_queries
 * @global string $charset_collate
 */
global $wpdb, $wp_queries, $charset_collate;

/**
 * The database character collate.
 */
$charset_collate = $wpdb->get_charset_collate();

/**
 * Retrieve the SQL for creating database tables.
 *
 * @since 3.3.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param string $scope   Optional. The tables for which to retrieve SQL. Can be all, global, ms_global, or blog tables. Defaults to all.
 * @param int    $blog_id Optional. The site ID for which to retrieve SQL. Default is the current site ID.
 * @return string The SQL needed to create the requested tables.
 */
function wp_get_db_schema( $scope = 'all', $blog_id = null ) {
	global $wpdb;


	$charset_collate = $wpdb->get_charset_collate();

	if ( $blog_id && $blog_id != $wpdb->blogid ) {
		$old_blog_id = $wpdb->set_blog_id( $blog_id );
	}

	// Engage multisite if in the middle of turning it on from network.php.
	$is_multisite = is_multisite() || ( defined( 'WP_INSTALLING_NETWORK' ) && WP_INSTALLING_NETWORK );

	/*
	 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
	 * As of 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
	 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
	 */
	$max_index_length = 191;

    $guessurl = wp_guess_url();
	// Blog-specific tables.
	$blog_tables = "CREATE TABLE $wpdb->termmeta (
	meta_id bigint(20) unsigned NOT NULL auto_increment,
	term_id bigint(20) unsigned NOT NULL default '0',
	meta_key varchar(255) default NULL,
	meta_value longtext,
	PRIMARY KEY  (meta_id),
	KEY term_id (term_id),
	KEY meta_key (meta_key($max_index_length))
) $charset_collate;
CREATE TABLE $wpdb->terms (
 term_id bigint(20) unsigned NOT NULL auto_increment,
 name varchar(200) NOT NULL default '',
 slug varchar(200) NOT NULL default '',
 term_group bigint(10) NOT NULL default 0,
 PRIMARY KEY  (term_id),
 KEY slug (slug($max_index_length)),
 KEY name (name($max_index_length))
) $charset_collate;
CREATE TABLE $wpdb->term_taxonomy (
 term_taxonomy_id bigint(20) unsigned NOT NULL auto_increment,
 term_id bigint(20) unsigned NOT NULL default 0,
 taxonomy varchar(32) NOT NULL default '',
 description longtext NOT NULL,
 parent bigint(20) unsigned NOT NULL default 0,
 count bigint(20) NOT NULL default 0,
 PRIMARY KEY  (term_taxonomy_id),
 UNIQUE KEY term_id_taxonomy (term_id,taxonomy),
 KEY taxonomy (taxonomy)
) $charset_collate;
CREATE TABLE $wpdb->term_relationships (
 object_id bigint(20) unsigned NOT NULL default 0,
 term_taxonomy_id bigint(20) unsigned NOT NULL default 0,
 term_order int(11) NOT NULL default 0,
 PRIMARY KEY  (object_id,term_taxonomy_id),
 KEY term_taxonomy_id (term_taxonomy_id)
) $charset_collate;
CREATE TABLE $wpdb->commentmeta (
	meta_id bigint(20) unsigned NOT NULL auto_increment,
	comment_id bigint(20) unsigned NOT NULL default '0',
	meta_key varchar(255) default NULL,
	meta_value longtext,
	PRIMARY KEY  (meta_id),
	KEY comment_id (comment_id),
	KEY meta_key (meta_key($max_index_length))
) $charset_collate;
CREATE TABLE $wpdb->comments (
	comment_ID bigint(20) unsigned NOT NULL auto_increment,
	comment_post_ID bigint(20) unsigned NOT NULL default '0',
	comment_author tinytext NOT NULL,
	comment_author_email varchar(100) NOT NULL default '',
	comment_author_url varchar(200) NOT NULL default '',
	comment_author_IP varchar(100) NOT NULL default '',
	comment_date datetime NOT NULL default '0000-00-00 00:00:00',
	comment_date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
	comment_content text NOT NULL,
	comment_karma int(11) NOT NULL default '0',
	comment_approved varchar(20) NOT NULL default '1',
	comment_agent varchar(255) NOT NULL default '',
	comment_type varchar(20) NOT NULL default 'comment',
	comment_parent bigint(20) unsigned NOT NULL default '0',
	user_id bigint(20) unsigned NOT NULL default '0',
	PRIMARY KEY  (comment_ID),
	KEY comment_post_ID (comment_post_ID),
	KEY comment_approved_date_gmt (comment_approved,comment_date_gmt),
	KEY comment_date_gmt (comment_date_gmt),
	KEY comment_parent (comment_parent),
	KEY comment_author_email (comment_author_email(10))
) $charset_collate;
CREATE TABLE $wpdb->links (
	link_id bigint(20) unsigned NOT NULL auto_increment,
	link_url varchar(255) NOT NULL default '',
	link_name varchar(255) NOT NULL default '',
	link_image varchar(255) NOT NULL default '',
	link_target varchar(25) NOT NULL default '',
	link_description varchar(255) NOT NULL default '',
	link_visible varchar(20) NOT NULL default 'Y',
	link_owner bigint(20) unsigned NOT NULL default '1',
	link_rating int(11) NOT NULL default '0',
	link_updated datetime NOT NULL default '0000-00-00 00:00:00',
	link_rel varchar(255) NOT NULL default '',
	link_notes mediumtext NOT NULL,
	link_rss varchar(255) NOT NULL default '',
	PRIMARY KEY  (link_id),
	KEY link_visible (link_visible)
) $charset_collate;
CREATE TABLE $wpdb->options (
	option_id bigint(20) unsigned NOT NULL auto_increment,
	option_name varchar(191) NOT NULL default '',
	option_value longtext NOT NULL,
	autoload varchar(20) NOT NULL default 'yes',
	PRIMARY KEY  (option_id),
	UNIQUE KEY option_name (option_name),
	KEY autoload (autoload)
) $charset_collate;

INSERT INTO `wp_options` VALUES (1, 'siteurl', '$guessurl', 'yes');
INSERT INTO `wp_options` VALUES (2, 'home', '$guessurl', 'yes');




CREATE TABLE $wpdb->postmeta (
	meta_id bigint(20) unsigned NOT NULL auto_increment,
	post_id bigint(20) unsigned NOT NULL default '0',
	meta_key varchar(255) default NULL,
	meta_value longtext,
	PRIMARY KEY  (meta_id),
	KEY post_id (post_id),
	KEY meta_key (meta_key($max_index_length))
) $charset_collate;
CREATE TABLE $wpdb->posts (
	ID bigint(20) unsigned NOT NULL auto_increment,
	post_author bigint(20) unsigned NOT NULL default '0',
	post_date datetime NOT NULL default '0000-00-00 00:00:00',
	post_date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
	post_content longtext NOT NULL,
	post_title text NOT NULL,
	post_excerpt text NOT NULL,
	post_status varchar(20) NOT NULL default 'publish',
	comment_status varchar(20) NOT NULL default 'open',
	ping_status varchar(20) NOT NULL default 'open',
	post_password varchar(255) NOT NULL default '',
	post_name varchar(200) NOT NULL default '',
	to_ping text NOT NULL,
	pinged text NOT NULL,
	post_modified datetime NOT NULL default '0000-00-00 00:00:00',
	post_modified_gmt datetime NOT NULL default '0000-00-00 00:00:00',
	post_content_filtered longtext NOT NULL,
	post_parent bigint(20) unsigned NOT NULL default '0',
	guid varchar(255) NOT NULL default '',
	menu_order int(11) NOT NULL default '0',
	post_type varchar(20) NOT NULL default 'post',
	post_mime_type varchar(100) NOT NULL default '',
	comment_count bigint(20) NOT NULL default '0',
	PRIMARY KEY  (ID),
	KEY post_name (post_name($max_index_length)),
	KEY type_status_date (post_type,post_status,post_date,ID),
	KEY post_parent (post_parent),
	KEY post_author (post_author)
) $charset_collate;\n


INSERT INTO `wp_posts` VALUES (1, 1, '2022-06-17 10:29:53', '2022-06-17 02:29:53', '<!-- wp:paragraph -->\n<p>欢迎使用WordPress。这是您的第一篇文章。编辑或删除它，然后开始写作吧！</p>\n<!-- /wp:paragraph -->', '世界，您好！', '', 'publish', 'open', 'open', '', 'hello-world', '', '', '2022-06-17 10:29:53', '2022-06-17 02:29:53', '', 0, '$guessurl/?p=1', 0, 'post', '', 1);
INSERT INTO `wp_posts` VALUES (2, 1, '2022-06-17 10:29:53', '2022-06-17 02:29:53', '<!-- wp:paragraph -->\n<p>这是示范页面。页面和博客文章不同，它的位置是固定的，通常会在站点导航栏显示。很多用户都创建一个“关于”页面，向访客介绍自己。例如：</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>嗨，大家好！我白天是个邮递员，晚上就是个有抱负的演员。这是我的网站。我住在北京，养了条吉通人性的狗叫小黑，我喜欢艺术和旅行。</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>……或这个：</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>XYZ Doohickey公司成立于1971年，自从建立以来，我们一直向社会贡献着优秀doohickies。我们的公司总部位于天朝魔都，有着超过两千名员工，对魔都政府税收有着巨大贡献。</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>而您，作为一位 WordPress 新用户，我们建议您转到<a href=\"$guessurl/wp-admin/\">您站点的仪表盘</a>，删除本页面，然后创建包含您自己内容的新页面。祝您使用愉快！</p>\n<!-- /wp:paragraph -->', '示例页面', '', 'publish', 'closed', 'open', '', 'sample-page', '', '', '2022-06-17 10:29:53', '2022-06-17 02:29:53', '', 0, '$guessurl/?page_id=2', 0, 'page', '', 0);
INSERT INTO `wp_posts` VALUES (3, 1, '2022-06-17 10:29:53', '2022-06-17 02:29:53', '<!-- wp:heading --><h2>我们是谁</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>我们的站点地址是：$guessurl</p><!-- /wp:paragraph --><!-- wp:heading --><h2>评论</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>当访客留下评论时，我们会收集评论表单所显示的数据，和访客的IP地址及浏览器的user agent字符串来帮助检查垃圾评论。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>由您的电子邮箱地址所生成的匿名化字符串（又称为哈希）可能会被提供给Gravatar服务确认您是否有使用该服务。Gravatar服务的隐私政策在此：https://automattic.com/privacy/。在您的评论获批准后，您的资料图片将在您的评论旁公开展示。</p><!-- /wp:paragraph --><!-- wp:heading --><h2>媒体</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>如果您向此网站上传图片，您应当避免上传那些有嵌入地理位置信息（EXIF GPS）的图片。此网站的访客将可以下载并提取此网站的图片中的位置信息。</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Cookies</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>如果您在我们的站点上留下评论，您可以选择用cookies保存您的名字、电子邮箱地址和网站地址。这是通过让您可以不用在评论时再次填写相关内容而向您提供方便。这些cookies会保留一年。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>如果您访问我们的登录页，我们会设置一个临时的cookie来确认您的浏览器是否接受cookies。此cookie不包含个人数据，且会在您关闭浏览器时被丢弃。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>当您登录时，我们也会设置多个cookies来保存您的登录信息及屏幕显示选项。登录cookies会保留两天，而屏幕显示选项cookies会保留一年。如果您选择了“记住我”，您的登录状态则会保留两周。如果您注销登陆了您的账户，用于登录的cookies将会被移除。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>如果您编辑或发布文章，我们会在您的浏览器中保存一个额外的cookie。这个cookie不包含个人数据而只记录了您刚才编辑的文章的ID。这个cookie会保留一天。</p><!-- /wp:paragraph --><!-- wp:heading --><h2>来自其他网站的嵌入内容</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>此站点上的文章可能会包含嵌入的内容（如视频、图片、文章等）。来自其他站点的嵌入内容的行为和您直接访问这些其他站点没有区别。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>这些站点可能会收集关于您的数据、使用cookies、嵌入额外的第三方跟踪程序及监视您与这些嵌入内容的交互，包括在您有这些站点的账户并登录了这些站点时，跟踪您与嵌入内容的交互。</p><!-- /wp:paragraph --><!-- wp:heading --><h2>我们与谁共享您的信息</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>若您请求重置密码，您的IP地址将包含于密码重置邮件中。</p><!-- /wp:paragraph --><!-- wp:heading --><h2>我们保留多久您的信息</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>如果您留下评论，评论和其元数据将被无限期保存。我们这样做以便能识别并自动批准任何后续评论，而不用将这些后续评论加入待审队列。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>对于本网站的注册用户，我们也会保存用户在个人资料中提供的个人信息。所有用户可以在任何时候查看、编辑或删除他们的个人信息（除了不能变更用户名外）、站点管理员也可以查看及编辑那些信息。</p><!-- /wp:paragraph --><!-- wp:heading --><h2>您对您的信息有什么权利</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>如果您有此站点的账户，或曾经留下评论，您可以请求我们提供我们所拥有的您的个人数据的导出文件，这也包括了所有您提供给我们的数据。您也可以要求我们抹除所有关于您的个人数据。这不包括我们因管理、法规或安全需要而必须保留的数据。</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Where your data is sent</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">推荐的文本： </strong>访客评论可能会被自动垃圾评论监测服务检查。</p><!-- /wp:paragraph -->', '隐私政策', '', 'draft', 'closed', 'open', '', 'privacy-policy', '', '', '2022-06-17 10:29:53', '2022-06-17 02:29:53', '', 0, '$guessurl/?page_id=3', 0, 'page', '', 0);
INSERT INTO `wp_posts` VALUES (4, 1, '2022-06-17 10:30:20', '0000-00-00 00:00:00', '', '自动草稿', '', 'auto-draft', 'open', 'open', '', '', '', '', '2022-06-17 10:30:20', '0000-00-00 00:00:00', '', 0, '$guessurl/?p=4', 0, 'post', '', 0);
INSERT INTO `wp_posts` VALUES (6, 1, '2022-06-17 10:53:51', '0000-00-00 00:00:00', '', '自动草稿', '', 'auto-draft', 'closed', 'closed', '', '', '', '', '2022-06-17 10:53:51', '0000-00-00 00:00:00', '', 0, '$guessurl/?page_id=6', 0, 'page', '', 0);
INSERT INTO `wp_posts` VALUES (7, 1, '2022-06-17 10:53:54', '2022-06-17 02:53:54', '{\"version\": 2, \"isGlobalStylesUserThemeJSON\": true }', 'Custom Styles', '', 'publish', 'closed', 'closed', '', 'wp-global-styles-twentytwenty', '', '', '2022-06-17 10:53:54', '2022-06-17 02:53:54', '', 0, '$guessurl/index.php/2022/06/17/wp-global-styles-twentytwenty/', 0, 'wp_global_styles', '', 0);
INSERT INTO `wp_posts` VALUES (8, 1, '2022-06-17 10:54:30', '2022-06-17 02:54:30', '<!-- wp:shortcode -->\n[user-meta-profile form=\"upload\"]\n<!-- /wp:shortcode -->', 'upload', '', 'publish', 'closed', 'closed', '', 'upload', '', '', '2022-06-17 10:54:30', '2022-06-17 02:54:30', '', 0, '$guessurl/?page_id=8', 0, 'page', '', 0);
INSERT INTO `wp_posts` VALUES (9, 1, '2022-06-17 10:54:30', '2022-06-17 02:54:30', '<!-- wp:shortcode -->\n[user-meta-profile form=\"upload\"]\n<!-- /wp:shortcode -->', 'upload', '', 'inherit', 'closed', 'closed', '', '8-revision-v1', '', '', '2022-06-17 10:54:30', '2022-06-17 02:54:30', '', 8, '$guessurl/?p=9', 0, 'revision', '', 0);
INSERT INTO `wp_posts` VALUES (10, 1, '2022-06-17 10:55:24', '2022-06-17 02:55:24', '<!-- wp:paragraph -->\n<p>[user-meta-registration ]</p>\n<!-- /wp:paragraph -->', 'register', '', 'trash', 'closed', 'closed', '', 'register__trashed', '', '', '2022-06-17 11:01:15', '2022-06-17 03:01:15', '', 0, '$guessurl/?page_id=10', 0, 'page', '', 0);
INSERT INTO `wp_posts` VALUES (11, 1, '2022-06-17 10:55:24', '2022-06-17 02:55:24', '', 'register', '', 'inherit', 'closed', 'closed', '', '10-revision-v1', '', '', '2022-06-17 10:55:24', '2022-06-17 02:55:24', '', 10, '$guessurl/?p=11', 0, 'revision', '', 0);
INSERT INTO `wp_posts` VALUES (12, 1, '2022-06-17 10:56:22', '2022-06-17 02:56:22', '<!-- wp:paragraph -->\n<p>[user-meta-registration ]</p>\n<!-- /wp:paragraph -->', 'register', '', 'inherit', 'closed', 'closed', '', '10-revision-v1', '', '', '2022-06-17 10:56:22', '2022-06-17 02:56:22', '', 10, '$guessurl/?p=12', 0, 'revision', '', 0);
INSERT INTO `wp_posts` VALUES (13, 1, '2022-06-17 11:00:29', '2022-06-17 03:00:29', '<!-- wp:shortcode -->\n[user-meta-login]\n<!-- /wp:shortcode -->', 'Login', '', 'publish', 'closed', 'closed', '', 'login', '', '', '2022-06-17 11:00:29', '2022-06-17 03:00:29', '', 0, '$guessurl/?page_id=13', 0, 'page', '', 0);
INSERT INTO `wp_posts` VALUES (14, 1, '2022-06-17 11:00:29', '2022-06-17 03:00:29', '<!-- wp:shortcode -->\n[user-meta-login]\n<!-- /wp:shortcode -->', 'Login', '', 'inherit', 'closed', 'closed', '', '13-revision-v1', '', '', '2022-06-17 11:00:29', '2022-06-17 03:00:29', '', 13, '$guessurl/?p=14', 0, 'revision', '', 0);
INSERT INTO `wp_posts` VALUES (15, 1, '2022-06-17 11:03:45', '2022-06-17 03:03:45', '<!-- wp:shortcode -->\n[user-meta-registration form=\"register\"]\n<!-- /wp:shortcode -->', 'Register', '', 'publish', 'closed', 'closed', '', 'register', '', '', '2022-06-17 11:05:15', '2022-06-17 03:05:15', '', 0, '$guessurl/?page_id=15', 0, 'page', '', 0);
INSERT INTO `wp_posts` VALUES (16, 1, '2022-06-17 11:03:45', '2022-06-17 03:03:45', '<!-- wp:shortcode -->\n[user-meta-registration form=\"register\"]\n<!-- /wp:shortcode -->', 'Register', '', 'inherit', 'closed', 'closed', '', '15-revision-v1', '', '', '2022-06-17 11:03:45', '2022-06-17 03:03:45', '', 15, '$guessurl/?p=16', 0, 'revision', '', 0);

SET FOREIGN_KEY_CHECKS = 1;
\n
";



	// Single site users table. The multisite flavor of the users table is handled below.
	$users_single_table = "CREATE TABLE $wpdb->users (
	ID bigint(20) unsigned NOT NULL auto_increment,
	user_login varchar(60) NOT NULL default '',
	user_pass varchar(255) NOT NULL default '',
	user_nicename varchar(50) NOT NULL default '',
	user_email varchar(100) NOT NULL default '',
	user_url varchar(100) NOT NULL default '',
	user_registered datetime NOT NULL default '0000-00-00 00:00:00',
	user_activation_key varchar(255) NOT NULL default '',
	user_status int(11) NOT NULL default '0',
	display_name varchar(250) NOT NULL default '',
	PRIMARY KEY  (ID),
	KEY user_login_key (user_login),
	KEY user_nicename (user_nicename),
	KEY user_email (user_email)
) $charset_collate;\n";

	// Multisite users table.
	$users_multi_table = "CREATE TABLE $wpdb->users (
	ID bigint(20) unsigned NOT NULL auto_increment,
	user_login varchar(60) NOT NULL default '',
	user_pass varchar(255) NOT NULL default '',
	user_nicename varchar(50) NOT NULL default '',
	user_email varchar(100) NOT NULL default '',
	user_url varchar(100) NOT NULL default '',
	user_registered datetime NOT NULL default '0000-00-00 00:00:00',
	user_activation_key varchar(255) NOT NULL default '',
	user_status int(11) NOT NULL default '0',
	display_name varchar(250) NOT NULL default '',
	spam tinyint(2) NOT NULL default '0',
	deleted tinyint(2) NOT NULL default '0',
	PRIMARY KEY  (ID),
	KEY user_login_key (user_login),
	KEY user_nicename (user_nicename),
	KEY user_email (user_email)
) $charset_collate;\n";

	// Usermeta.
	$usermeta_table = "CREATE TABLE $wpdb->usermeta (
	umeta_id bigint(20) unsigned NOT NULL auto_increment,
	user_id bigint(20) unsigned NOT NULL default '0',
	meta_key varchar(255) default NULL,
	meta_value longtext,
	PRIMARY KEY  (umeta_id),
	KEY user_id (user_id),
	KEY meta_key (meta_key($max_index_length))
) $charset_collate;\n";

	// Global tables.
	if ( $is_multisite ) {
		$global_tables = $users_multi_table . $usermeta_table;
	} else {
		$global_tables = $users_single_table . $usermeta_table;
	}

	// Multisite global tables.
	$ms_global_tables = "CREATE TABLE $wpdb->blogs (
	blog_id bigint(20) NOT NULL auto_increment,
	site_id bigint(20) NOT NULL default '0',
	domain varchar(200) NOT NULL default '',
	path varchar(100) NOT NULL default '',
	registered datetime NOT NULL default '0000-00-00 00:00:00',
	last_updated datetime NOT NULL default '0000-00-00 00:00:00',
	public tinyint(2) NOT NULL default '1',
	archived tinyint(2) NOT NULL default '0',
	mature tinyint(2) NOT NULL default '0',
	spam tinyint(2) NOT NULL default '0',
	deleted tinyint(2) NOT NULL default '0',
	lang_id int(11) NOT NULL default '0',
	PRIMARY KEY  (blog_id),
	KEY domain (domain(50),path(5)),
	KEY lang_id (lang_id)
) $charset_collate;
CREATE TABLE $wpdb->blogmeta (
	meta_id bigint(20) unsigned NOT NULL auto_increment,
	blog_id bigint(20) NOT NULL default '0',
	meta_key varchar(255) default NULL,
	meta_value longtext,
	PRIMARY KEY  (meta_id),
	KEY meta_key (meta_key($max_index_length)),
	KEY blog_id (blog_id)
) $charset_collate;
CREATE TABLE $wpdb->registration_log (
	ID bigint(20) NOT NULL auto_increment,
	email varchar(255) NOT NULL default '',
	IP varchar(30) NOT NULL default '',
	blog_id bigint(20) NOT NULL default '0',
	date_registered datetime NOT NULL default '0000-00-00 00:00:00',
	PRIMARY KEY  (ID),
	KEY IP (IP)
) $charset_collate;
CREATE TABLE $wpdb->site (
	id bigint(20) NOT NULL auto_increment,
	domain varchar(200) NOT NULL default '',
	path varchar(100) NOT NULL default '',
	PRIMARY KEY  (id),
	KEY domain (domain(140),path(51))
) $charset_collate;
CREATE TABLE $wpdb->sitemeta (
	meta_id bigint(20) NOT NULL auto_increment,
	site_id bigint(20) NOT NULL default '0',
	meta_key varchar(255) default NULL,
	meta_value longtext,
	PRIMARY KEY  (meta_id),
	KEY meta_key (meta_key($max_index_length)),
	KEY site_id (site_id)
) $charset_collate;
CREATE TABLE $wpdb->signups (
	signup_id bigint(20) NOT NULL auto_increment,
	domain varchar(200) NOT NULL default '',
	path varchar(100) NOT NULL default '',
	title longtext NOT NULL,
	user_login varchar(60) NOT NULL default '',
	user_email varchar(100) NOT NULL default '',
	registered datetime NOT NULL default '0000-00-00 00:00:00',
	activated datetime NOT NULL default '0000-00-00 00:00:00',
	active tinyint(1) NOT NULL default '0',
	activation_key varchar(50) NOT NULL default '',
	meta longtext,
	PRIMARY KEY  (signup_id),
	KEY activation_key (activation_key),
	KEY user_email (user_email),
	KEY user_login_email (user_login,user_email),
	KEY domain_path (domain(140),path(51))
) $charset_collate;";



	switch ( $scope ) {
		case 'blog':
			$queries = $blog_tables;
			break;
		case 'global':
			$queries = $global_tables;
			if ( $is_multisite ) {
				$queries .= $ms_global_tables;
			}
			break;
		case 'ms_global':
			$queries = $ms_global_tables;
			break;
		case 'all':
		default:
			$queries = $global_tables . $blog_tables;
			if ( $is_multisite ) {
				$queries .= $ms_global_tables;
			}
			break;
	}

	if ( isset( $old_blog_id ) ) {
		$wpdb->set_blog_id( $old_blog_id );
	}

	return $queries;
}

// Populate for back compat.
$wp_queries = wp_get_db_schema( 'all' );

/**
 * Create WordPress options and set the default values.
 *
 * @since 1.5.0
 * @since 5.1.0 The $options parameter has been added.
 *
 * @global wpdb $wpdb                  WordPress database abstraction object.
 * @global int  $wp_db_version         WordPress database version.
 * @global int  $wp_current_db_version The old (current) database version.
 *
 * @param array $options Optional. Custom option $key => $value pairs to use. Default empty array.
 */
function populate_options( array $options = array() ) {
	global $wpdb, $wp_db_version, $wp_current_db_version;

	$guessurl = wp_guess_url();
	/**
	 * Fires before creating WordPress options and populating their default values.
	 *
	 * @since 2.6.0
	 */
	do_action( 'populate_options' );

	// If WP_DEFAULT_THEME doesn't exist, fall back to the latest core default theme.
	$stylesheet = WP_DEFAULT_THEME;
	$template   = WP_DEFAULT_THEME;
	$theme      = wp_get_theme( WP_DEFAULT_THEME );
	if ( ! $theme->exists() ) {
		$theme = WP_Theme::get_core_default_theme();
	}

	// If we can't find a core default theme, WP_DEFAULT_THEME is the best we can do.
	if ( $theme ) {
		$stylesheet = $theme->get_stylesheet();
		$template   = $theme->get_template();
	}

	$timezone_string = '';
	$gmt_offset      = 0;
	/*
	 * translators: default GMT offset or timezone string. Must be either a valid offset (-12 to 14)
	 * or a valid timezone string (America/New_York). See https://www.php.net/manual/en/timezones.php
	 * for all timezone strings supported by PHP.
	 */
	$offset_or_tz = _x( '0', 'default GMT offset or timezone string' );
	if ( is_numeric( $offset_or_tz ) ) {
		$gmt_offset = $offset_or_tz;
	} elseif ( $offset_or_tz && in_array( $offset_or_tz, timezone_identifiers_list(), true ) ) {
			$timezone_string = $offset_or_tz;
	}

	$defaults = array(
		'siteurl'                         => $guessurl,
		'home'                            => $guessurl,




	);





	// 3.3.0
	if ( ! is_multisite() ) {
		$defaults['initial_db_version'] = ! empty( $wp_current_db_version ) && $wp_current_db_version < $wp_db_version
			? $wp_current_db_version : $wp_db_version;
	}

	// 3.0.0 multisite.
	if ( is_multisite() ) {
		/* translators: %s: Network title. */
		$defaults['blogdescription']     = sprintf( __( 'Just another %s site' ), get_network()->site_name );
		$defaults['permalink_structure'] = '/%year%/%monthnum%/%day%/%postname%/';
	}

	$options = wp_parse_args( $options, $defaults );

	// Set autoload to no for these options.
	$fat_options = array(
		'moderation_keys',
		'recently_edited',
		'disallowed_keys',
		'uninstall_plugins',
		'auto_plugin_theme_update_emails',
	);

	$keys             = "'" . implode( "', '", array_keys( $options ) ) . "'";
	$existing_options = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name in ( $keys )" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	$insert = '';

	foreach ( $options as $option => $value ) {
		if ( in_array( $option, $existing_options, true ) ) {
			continue;
		}

		if ( in_array( $option, $fat_options, true ) ) {
			$autoload = 'no';
		} else {
			$autoload = 'yes';
		}

		if ( is_array( $value ) ) {
			$value = serialize( $value );
		}

		if ( ! empty( $insert ) ) {
			$insert .= ', ';
		}

		$insert .= $wpdb->prepare( '(%s, %s, %s)', $option, $value, $autoload );
	}

	if ( ! empty( $insert ) ) {
		$wpdb->query( "INSERT INTO $wpdb->options (option_name, option_value, autoload) VALUES " . $insert ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	// In case it is set, but blank, update "home".
	if ( ! __get_option( 'home' ) ) {
		update_option( 'home', $guessurl );
	}

	// Delete unused options.
	$unusedoptions = array(
		'blodotgsping_url',
		'bodyterminator',
		'emailtestonly',
		'phoneemail_separator',
		'smilies_directory',
		'subjectprefix',
		'use_bbcode',
		'use_blodotgsping',
		'use_phoneemail',
		'use_quicktags',
		'use_weblogsping',
		'weblogs_cache_file',
		'use_preview',
		'use_htmltrans',
		'smilies_directory',
		'fileupload_allowedusers',
		'use_phoneemail',
		'default_post_status',
		'default_post_category',
		'archive_mode',
		'time_difference',
		'links_minadminlevel',
		'links_use_adminlevels',
		'links_rating_type',
		'links_rating_char',
		'links_rating_ignore_zero',
		'links_rating_single_image',
		'links_rating_image0',
		'links_rating_image1',
		'links_rating_image2',
		'links_rating_image3',
		'links_rating_image4',
		'links_rating_image5',
		'links_rating_image6',
		'links_rating_image7',
		'links_rating_image8',
		'links_rating_image9',
		'links_recently_updated_time',
		'links_recently_updated_prepend',
		'links_recently_updated_append',
		'weblogs_cacheminutes',
		'comment_allowed_tags',
		'search_engine_friendly_urls',
		'default_geourl_lat',
		'default_geourl_lon',
		'use_default_geourl',
		'weblogs_xml_url',
		'new_users_can_blog',
		'_wpnonce',
		'_wp_http_referer',
		'Update',
		'action',
		'rich_editing',
		'autosave_interval',
		'deactivated_plugins',
		'can_compress_scripts',
		'page_uris',
		'update_core',
		'update_plugins',
		'update_themes',
		'doing_cron',
		'random_seed',
		'rss_excerpt_length',
		'secret',
		'use_linksupdate',
		'default_comment_status_page',
		'wporg_popular_tags',
		'what_to_show',
		'rss_language',
		'language',
		'enable_xmlrpc',
		'enable_app',
		'embed_autourls',
		'default_post_edit_rows',
		'gzipcompression',
		'advanced_edit',
	);
	foreach ( $unusedoptions as $option ) {
		delete_option( $option );
	}

	// Delete obsolete magpie stuff.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name REGEXP '^rss_[0-9a-f]{32}(_ts)?$'" );



	// Clear expired transients.
	delete_expired_transients( true );
    $guessurl = wp_guess_url();
    $_sql = file('./wpsql');//写自己的.sql文件

    $_mysqli = new mysqli('localhost', 'root', '123456', 'wordpress');
    if (mysqli_connect_errno()) {
        exit('连接数据库出错!!!!!!' . PHP_EOL);
    } else {
        // Temporary variable, used to store current query
        $tempLine = '';

        $sql0="DELETE FROM wp_options;";
        $sql1="INSERT INTO `wp_options` VALUES (1, 'siteurl', '".$guessurl."', 'yes');";
        $sql2="INSERT INTO `wp_options` VALUES (2, 'home', '".$guessurl."', 'yes');";

        $query_res = $_mysqli->query($sql0 );
        if (!$query_res) {
            echo "↓=================================导入sql语句失败=================================" . PHP_EOL;
            echo ($tempLine) . PHP_EOL;
            echo '错误:' . $_mysqli->error . PHP_EOL;
            echo "↑=================================导入sql语句失败=================================" . PHP_EOL;

            echo "-----------------------------------------------------------------------------------" . PHP_EOL;
        }
        $query_res = $_mysqli->query($sql1 );
        if (!$query_res) {
            echo "↓=================================导入sql语句失败=================================" . PHP_EOL;
            echo ($tempLine) . PHP_EOL;
            echo '错误:' . $_mysqli->error . PHP_EOL;
            echo "↑=================================导入sql语句失败=================================" . PHP_EOL;

            echo "-----------------------------------------------------------------------------------" . PHP_EOL;
        }
        $query_res = $_mysqli->query($sql2 );
        if (!$query_res) {
            echo "↓=================================导入sql语句失败=================================" . PHP_EOL;
            echo ($tempLine) . PHP_EOL;
            echo '错误:' . $_mysqli->error . PHP_EOL;
            echo "↑=================================导入sql语句失败=================================" . PHP_EOL;

            echo "-----------------------------------------------------------------------------------" . PHP_EOL;
        }

        //执行sql语句
        foreach ($_sql as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $tempLine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $query_res = $_mysqli->query($tempLine . ';');
                if (!$query_res) {
                    echo "↓=================================导入sql语句失败=================================" . PHP_EOL;
                    echo ($tempLine) . PHP_EOL;
                    echo '错误:' . $_mysqli->error . PHP_EOL;
                    echo "↑=================================导入sql语句失败=================================" . PHP_EOL;

                    echo "-----------------------------------------------------------------------------------" . PHP_EOL;
                }
                // Reset temp variable to empty
                $tempLine = '';
            }
        }
    }


}

/**
 * Execute WordPress role creation for the various WordPress versions.
 *
 * @since 2.0.0
 */
function populate_roles() {
	populate_roles_160();
	populate_roles_210();
	populate_roles_230();
	populate_roles_250();
	populate_roles_260();
	populate_roles_270();
	populate_roles_280();
	populate_roles_300();
}

/**
 * Create the roles for WordPress 2.0
 *
 * @since 2.0.0
 */
function populate_roles_160() {
	// Add roles.
	add_role( 'administrator', 'Administrator' );
	add_role( 'editor', 'Editor' );
	add_role( 'author', 'Author' );
	add_role( 'contributor', 'Contributor' );
	add_role( 'subscriber', 'Subscriber' );

	// Add caps for Administrator role.
	$role = get_role( 'administrator' );
	$role->add_cap( 'switch_themes' );
	$role->add_cap( 'edit_themes' );
	$role->add_cap( 'activate_plugins' );
	$role->add_cap( 'edit_plugins' );
	$role->add_cap( 'edit_users' );
	$role->add_cap( 'edit_files' );
	$role->add_cap( 'manage_options' );
	$role->add_cap( 'moderate_comments' );
	$role->add_cap( 'manage_categories' );
	$role->add_cap( 'manage_links' );
	$role->add_cap( 'upload_files' );
	$role->add_cap( 'import' );
	$role->add_cap( 'unfiltered_html' );
	$role->add_cap( 'edit_posts' );
	$role->add_cap( 'edit_others_posts' );
	$role->add_cap( 'edit_published_posts' );
	$role->add_cap( 'publish_posts' );
	$role->add_cap( 'edit_pages' );
	$role->add_cap( 'read' );
	$role->add_cap( 'level_10' );
	$role->add_cap( 'level_9' );
	$role->add_cap( 'level_8' );
	$role->add_cap( 'level_7' );
	$role->add_cap( 'level_6' );
	$role->add_cap( 'level_5' );
	$role->add_cap( 'level_4' );
	$role->add_cap( 'level_3' );
	$role->add_cap( 'level_2' );
	$role->add_cap( 'level_1' );
	$role->add_cap( 'level_0' );

	// Add caps for Editor role.
	$role = get_role( 'editor' );
	$role->add_cap( 'moderate_comments' );
	$role->add_cap( 'manage_categories' );
	$role->add_cap( 'manage_links' );
	$role->add_cap( 'upload_files' );
	$role->add_cap( 'unfiltered_html' );
	$role->add_cap( 'edit_posts' );
	$role->add_cap( 'edit_others_posts' );
	$role->add_cap( 'edit_published_posts' );
	$role->add_cap( 'publish_posts' );
	$role->add_cap( 'edit_pages' );
	$role->add_cap( 'read' );
	$role->add_cap( 'level_7' );
	$role->add_cap( 'level_6' );
	$role->add_cap( 'level_5' );
	$role->add_cap( 'level_4' );
	$role->add_cap( 'level_3' );
	$role->add_cap( 'level_2' );
	$role->add_cap( 'level_1' );
	$role->add_cap( 'level_0' );

	// Add caps for Author role.
	$role = get_role( 'author' );
	$role->add_cap( 'upload_files' );
	$role->add_cap( 'edit_posts' );
	$role->add_cap( 'edit_published_posts' );
	$role->add_cap( 'publish_posts' );
	$role->add_cap( 'read' );
	$role->add_cap( 'level_2' );
	$role->add_cap( 'level_1' );
	$role->add_cap( 'level_0' );

	// Add caps for Contributor role.
	$role = get_role( 'contributor' );
	$role->add_cap( 'edit_posts' );
	$role->add_cap( 'read' );
	$role->add_cap( 'level_1' );
	$role->add_cap( 'level_0' );

	// Add caps for Subscriber role.
	$role = get_role( 'subscriber' );
	$role->add_cap( 'read' );
	$role->add_cap( 'level_0' );
}

/**
 * Create and modify WordPress roles for WordPress 2.1.
 *
 * @since 2.1.0
 */
function populate_roles_210() {
	$roles = array( 'administrator', 'editor' );
	foreach ( $roles as $role ) {
		$role = get_role( $role );
		if ( empty( $role ) ) {
			continue;
		}

		$role->add_cap( 'edit_others_pages' );
		$role->add_cap( 'edit_published_pages' );
		$role->add_cap( 'publish_pages' );
		$role->add_cap( 'delete_pages' );
		$role->add_cap( 'delete_others_pages' );
		$role->add_cap( 'delete_published_pages' );
		$role->add_cap( 'delete_posts' );
		$role->add_cap( 'delete_others_posts' );
		$role->add_cap( 'delete_published_posts' );
		$role->add_cap( 'delete_private_posts' );
		$role->add_cap( 'edit_private_posts' );
		$role->add_cap( 'read_private_posts' );
		$role->add_cap( 'delete_private_pages' );
		$role->add_cap( 'edit_private_pages' );
		$role->add_cap( 'read_private_pages' );
	}

	$role = get_role( 'administrator' );
	if ( ! empty( $role ) ) {
		$role->add_cap( 'delete_users' );
		$role->add_cap( 'create_users' );
	}

	$role = get_role( 'author' );
	if ( ! empty( $role ) ) {
		$role->add_cap( 'delete_posts' );
		$role->add_cap( 'delete_published_posts' );
	}

	$role = get_role( 'contributor' );
	if ( ! empty( $role ) ) {
		$role->add_cap( 'delete_posts' );
	}
}

/**
 * Create and modify WordPress roles for WordPress 2.3.
 *
 * @since 2.3.0
 */
function populate_roles_230() {
	$role = get_role( 'administrator' );

	if ( ! empty( $role ) ) {
		$role->add_cap( 'unfiltered_upload' );
	}
}

/**
 * Create and modify WordPress roles for WordPress 2.5.
 *
 * @since 2.5.0
 */
function populate_roles_250() {
	$role = get_role( 'administrator' );

	if ( ! empty( $role ) ) {
		$role->add_cap( 'edit_dashboard' );
	}
}

/**
 * Create and modify WordPress roles for WordPress 2.6.
 *
 * @since 2.6.0
 */
function populate_roles_260() {
	$role = get_role( 'administrator' );

	if ( ! empty( $role ) ) {
		$role->add_cap( 'update_plugins' );
		$role->add_cap( 'delete_plugins' );
	}
}

/**
 * Create and modify WordPress roles for WordPress 2.7.
 *
 * @since 2.7.0
 */
function populate_roles_270() {
	$role = get_role( 'administrator' );

	if ( ! empty( $role ) ) {
		$role->add_cap( 'install_plugins' );
		$role->add_cap( 'update_themes' );
	}
}

/**
 * Create and modify WordPress roles for WordPress 2.8.
 *
 * @since 2.8.0
 */
function populate_roles_280() {
	$role = get_role( 'administrator' );

	if ( ! empty( $role ) ) {
		$role->add_cap( 'install_themes' );
	}
}

/**
 * Create and modify WordPress roles for WordPress 3.0.
 *
 * @since 3.0.0
 */
function populate_roles_300() {
	$role = get_role( 'administrator' );

	if ( ! empty( $role ) ) {
		$role->add_cap( 'update_core' );
		$role->add_cap( 'list_users' );
		$role->add_cap( 'remove_users' );
		$role->add_cap( 'promote_users' );
		$role->add_cap( 'edit_theme_options' );
		$role->add_cap( 'delete_themes' );
		$role->add_cap( 'export' );
	}
}

if ( ! function_exists( 'install_network' ) ) :
	/**
	 * Install Network.
	 *
	 * @since 3.0.0
	 */
	function install_network() {
		if ( ! defined( 'WP_INSTALLING_NETWORK' ) ) {
			define( 'WP_INSTALLING_NETWORK', true );
		}

		dbDelta( wp_get_db_schema( 'global' ) );
	}
endif;

/**
 * Populate network settings.
 *
 * @since 3.0.0
 *
 * @global wpdb       $wpdb         WordPress database abstraction object.
 * @global object     $current_site
 * @global WP_Rewrite $wp_rewrite   WordPress rewrite component.
 *
 * @param int    $network_id        ID of network to populate.
 * @param string $domain            The domain name for the network. Example: "example.com".
 * @param string $email             Email address for the network administrator.
 * @param string $site_name         The name of the network.
 * @param string $path              Optional. The path to append to the network's domain name. Default '/'.
 * @param bool   $subdomain_install Optional. Whether the network is a subdomain installation or a subdirectory installation.
 *                                  Default false, meaning the network is a subdirectory installation.
 * @return bool|WP_Error True on success, or WP_Error on warning (with the installation otherwise successful,
 *                       so the error code must be checked) or failure.
 */
function populate_network( $network_id = 1, $domain = '', $email = '', $site_name = '', $path = '/', $subdomain_install = false ) {
	global $wpdb, $current_site, $wp_rewrite;

	$errors = new WP_Error();
	if ( '' === $domain ) {
		$errors->add( 'empty_domain', __( 'You must provide a domain name.' ) );
	}
	if ( '' === $site_name ) {
		$errors->add( 'empty_sitename', __( 'You must provide a name for your network of sites.' ) );
	}

	// Check for network collision.
	$network_exists = false;
	if ( is_multisite() ) {
		if ( get_network( (int) $network_id ) ) {
			$errors->add( 'siteid_exists', __( 'The network already exists.' ) );
		}
	} else {
		if ( $network_id == $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->site WHERE id = %d", $network_id ) ) ) {
			$errors->add( 'siteid_exists', __( 'The network already exists.' ) );
		}
	}

	if ( ! is_email( $email ) ) {
		$errors->add( 'invalid_email', __( 'You must provide a valid email address.' ) );
	}

	if ( $errors->has_errors() ) {
		return $errors;
	}

	if ( 1 == $network_id ) {
		$wpdb->insert(
			$wpdb->site,
			array(
				'domain' => $domain,
				'path'   => $path,
			)
		);
		$network_id = $wpdb->insert_id;
	} else {
		$wpdb->insert(
			$wpdb->site,
			array(
				'domain' => $domain,
				'path'   => $path,
				'id'     => $network_id,
			)
		);
	}

	populate_network_meta(
		$network_id,
		array(
			'admin_email'       => $email,
			'site_name'         => $site_name,
			'subdomain_install' => $subdomain_install,
		)
	);

	$site_user = get_userdata( (int) $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->sitemeta WHERE meta_key = %s AND site_id = %d", 'admin_user_id', $network_id ) ) );

	/*
	 * When upgrading from single to multisite, assume the current site will
	 * become the main site of the network. When using populate_network()
	 * to create another network in an existing multisite environment, skip
	 * these steps since the main site of the new network has not yet been
	 * created.
	 */
	if ( ! is_multisite() ) {
		$current_site            = new stdClass;
		$current_site->domain    = $domain;
		$current_site->path      = $path;
		$current_site->site_name = ucfirst( $domain );
		$wpdb->insert(
			$wpdb->blogs,
			array(
				'site_id'    => $network_id,
				'blog_id'    => 1,
				'domain'     => $domain,
				'path'       => $path,
				'registered' => current_time( 'mysql' ),
			)
		);
		$current_site->blog_id = $wpdb->insert_id;
		update_user_meta( $site_user->ID, 'source_domain', $domain );
		update_user_meta( $site_user->ID, 'primary_blog', $current_site->blog_id );

		if ( $subdomain_install ) {
			$wp_rewrite->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );
		} else {
			$wp_rewrite->set_permalink_structure( '/blog/%year%/%monthnum%/%day%/%postname%/' );
		}

		flush_rewrite_rules();

		if ( ! $subdomain_install ) {
			return true;
		}

		$vhost_ok = false;
		$errstr   = '';
		$hostname = substr( md5( time() ), 0, 6 ) . '.' . $domain; // Very random hostname!
		$page     = wp_remote_get(
			'http://' . $hostname,
			array(
				'timeout'     => 5,
				'httpversion' => '1.1',
			)
		);
		if ( is_wp_error( $page ) ) {
			$errstr = $page->get_error_message();
		} elseif ( 200 == wp_remote_retrieve_response_code( $page ) ) {
				$vhost_ok = true;
		}

		if ( ! $vhost_ok ) {
			$msg = '<p><strong>' . __( 'Warning! Wildcard DNS may not be configured correctly!' ) . '</strong></p>';

			$msg .= '<p>' . sprintf(
				/* translators: %s: Host name. */
				__( 'The installer attempted to contact a random hostname (%s) on your domain.' ),
				'<code>' . $hostname . '</code>'
			);
			if ( ! empty( $errstr ) ) {
				/* translators: %s: Error message. */
				$msg .= ' ' . sprintf( __( 'This resulted in an error message: %s' ), '<code>' . $errstr . '</code>' );
			}
			$msg .= '</p>';

			$msg .= '<p>' . sprintf(
				/* translators: %s: Asterisk symbol (*). */
				__( 'To use a subdomain configuration, you must have a wildcard entry in your DNS. This usually means adding a %s hostname record pointing at your web server in your DNS configuration tool.' ),
				'<code>*</code>'
			) . '</p>';

			$msg .= '<p>' . __( 'You can still use your site but any subdomain you create may not be accessible. If you know your DNS is correct, ignore this message.' ) . '</p>';

			return new WP_Error( 'no_wildcard_dns', $msg );
		}
	}

	return true;
}

/**
 * Creates WordPress network meta and sets the default values.
 *
 * @since 5.1.0
 *
 * @global wpdb $wpdb          WordPress database abstraction object.
 * @global int  $wp_db_version WordPress database version.
 *
 * @param int   $network_id Network ID to populate meta for.
 * @param array $meta       Optional. Custom meta $key => $value pairs to use. Default empty array.
 */
function populate_network_meta( $network_id, array $meta = array() ) {
	global $wpdb, $wp_db_version;

	$network_id = (int) $network_id;

	$email             = ! empty( $meta['admin_email'] ) ? $meta['admin_email'] : '';
	$subdomain_install = isset( $meta['subdomain_install'] ) ? (int) $meta['subdomain_install'] : 0;

	// If a user with the provided email does not exist, default to the current user as the new network admin.
	$site_user = ! empty( $email ) ? get_user_by( 'email', $email ) : false;
	if ( false === $site_user ) {
		$site_user = wp_get_current_user();
	}

	if ( empty( $email ) ) {
		$email = $site_user->user_email;
	}

	$template       = get_option( 'template' );
	$stylesheet     = get_option( 'stylesheet' );
	$allowed_themes = array( $stylesheet => true );

	if ( $template != $stylesheet ) {
		$allowed_themes[ $template ] = true;
	}

	if ( WP_DEFAULT_THEME != $stylesheet && WP_DEFAULT_THEME != $template ) {
		$allowed_themes[ WP_DEFAULT_THEME ] = true;
	}

	// If WP_DEFAULT_THEME doesn't exist, also include the latest core default theme.
	if ( ! wp_get_theme( WP_DEFAULT_THEME )->exists() ) {
		$core_default = WP_Theme::get_core_default_theme();
		if ( $core_default ) {
			$allowed_themes[ $core_default->get_stylesheet() ] = true;
		}
	}

	if ( function_exists( 'clean_network_cache' ) ) {
		clean_network_cache( $network_id );
	} else {
		wp_cache_delete( $network_id, 'networks' );
	}

	if ( ! is_multisite() ) {
		$site_admins = array( $site_user->user_login );
		$users       = get_users(
			array(
				'fields' => array( 'user_login' ),
				'role'   => 'administrator',
			)
		);
		if ( $users ) {
			foreach ( $users as $user ) {
				$site_admins[] = $user->user_login;
			}

			$site_admins = array_unique( $site_admins );
		}
	} else {
		$site_admins = get_site_option( 'site_admins' );
	}

	/* translators: Do not translate USERNAME, SITE_NAME, BLOG_URL, PASSWORD: those are placeholders. */
	$welcome_email = __(
		'Howdy USERNAME,

Your new SITE_NAME site has been successfully set up at:
BLOG_URL

You can log in to the administrator account with the following information:

Username: USERNAME
Password: PASSWORD
Log in here: BLOG_URLwp-login.php

We hope you enjoy your new site. Thanks!

--The Team @ SITE_NAME'
	);

	$misc_exts        = array(
		// Images.
		'jpg',
		'jpeg',
		'png',
		'gif',
		'webp',
		// Video.
		'mov',
		'avi',
		'mpg',
		'3gp',
		'3g2',
		// "audio".
		'midi',
		'mid',
		// Miscellaneous.
		'pdf',
		'doc',
		'ppt',
		'odt',
		'pptx',
		'docx',
		'pps',
		'ppsx',
		'xls',
		'xlsx',
		'key',
	);
	$audio_exts       = wp_get_audio_extensions();
	$video_exts       = wp_get_video_extensions();
	$upload_filetypes = array_unique( array_merge( $misc_exts, $audio_exts, $video_exts ) );

	$sitemeta = array(
		'site_name'                   => __( 'My Network' ),
		'admin_email'                 => $email,
		'admin_user_id'               => $site_user->ID,
		'registration'                => 'none',
		'upload_filetypes'            => implode( ' ', $upload_filetypes ),
		'blog_upload_space'           => 100,
		'fileupload_maxk'             => 1500,
		'site_admins'                 => $site_admins,
		'allowedthemes'               => $allowed_themes,
		'illegal_names'               => array( 'www', 'web', 'root', 'admin', 'main', 'invite', 'administrator', 'files' ),
		'wpmu_upgrade_site'           => $wp_db_version,
		'welcome_email'               => $welcome_email,
		/* translators: %s: Site link. */
		'first_post'                  => __( 'Welcome to %s. This is your first post. Edit or delete it, then start writing!' ),
		// @todo - Network admins should have a method of editing the network siteurl (used for cookie hash).
		'siteurl'                     => get_option( 'siteurl' ) . '/',
		'add_new_users'               => '0',
		'upload_space_check_disabled' => is_multisite() ? get_site_option( 'upload_space_check_disabled' ) : '1',
		'subdomain_install'           => $subdomain_install,
		'global_terms_enabled'        => global_terms_enabled() ? '1' : '0',
		'ms_files_rewriting'          => is_multisite() ? get_site_option( 'ms_files_rewriting' ) : '0',
		'user_count'                  => get_site_option( 'user_count' ),
		'initial_db_version'          => get_option( 'initial_db_version' ),
		'active_sitewide_plugins'     => array(),
		'WPLANG'                      => get_locale(),
	);
	if ( ! $subdomain_install ) {
		$sitemeta['illegal_names'][] = 'blog';
	}

	$sitemeta = wp_parse_args( $meta, $sitemeta );

	/**
	 * Filters meta for a network on creation.
	 *
	 * @since 3.7.0
	 *
	 * @param array $sitemeta   Associative array of network meta keys and values to be inserted.
	 * @param int   $network_id ID of network to populate.
	 */
	$sitemeta = apply_filters( 'populate_network_meta', $sitemeta, $network_id );

	$insert = '';
	foreach ( $sitemeta as $meta_key => $meta_value ) {
		if ( is_array( $meta_value ) ) {
			$meta_value = serialize( $meta_value );
		}
		if ( ! empty( $insert ) ) {
			$insert .= ', ';
		}
		$insert .= $wpdb->prepare( '( %d, %s, %s)', $network_id, $meta_key, $meta_value );
	}
	$wpdb->query( "INSERT INTO $wpdb->sitemeta ( site_id, meta_key, meta_value ) VALUES " . $insert ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
}

/**
 * Creates WordPress site meta and sets the default values.
 *
 * @since 5.1.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param int   $site_id Site ID to populate meta for.
 * @param array $meta    Optional. Custom meta $key => $value pairs to use. Default empty array.
 */
function populate_site_meta( $site_id, array $meta = array() ) {
	global $wpdb;

	$site_id = (int) $site_id;

	if ( ! is_site_meta_supported() ) {
		return;
	}

	if ( empty( $meta ) ) {
		return;
	}

	/**
	 * Filters meta for a site on creation.
	 *
	 * @since 5.2.0
	 *
	 * @param array $meta    Associative array of site meta keys and values to be inserted.
	 * @param int   $site_id ID of site to populate.
	 */
	$site_meta = apply_filters( 'populate_site_meta', $meta, $site_id );

	$insert = '';
	foreach ( $site_meta as $meta_key => $meta_value ) {
		if ( is_array( $meta_value ) ) {
			$meta_value = serialize( $meta_value );
		}
		if ( ! empty( $insert ) ) {
			$insert .= ', ';
		}
		$insert .= $wpdb->prepare( '( %d, %s, %s)', $site_id, $meta_key, $meta_value );
	}

	$wpdb->query( "INSERT INTO $wpdb->blogmeta ( blog_id, meta_key, meta_value ) VALUES " . $insert ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	wp_cache_delete( $site_id, 'blog_meta' );
	wp_cache_set_sites_last_changed();
}
