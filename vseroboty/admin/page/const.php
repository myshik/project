<?php
// CONFIGURATION
// You can delete unwanted comments from this file.
// IN THIS CONFIGURATION accepted: figure 1 means YES, 0 - NOT!
####  The overall configuration:
define('SITEURL', '');
define('POST_SCRIPT', 'read');	
define('CAT_SCRIPT', 'news_cat');
define('LANG','ru');						// Select language for you site (Example: en, ru... ) Available languages you can see in folder 'locale'
define('IQUERY', 0);		// 1 - iquery.js load from gooogle - more fast loading pages of site. Not work in local computer.
define('DATAFOLDER', 'comments');	// Folder for database files with CHMOD 666. For this folder right on the record set is not required
define('ENGINE', 0);			// Enable rewrite engine functions (you must rename file .htaccess.txt to .htaccess) - Used if your server enable mod_rewrite...
define('GZIP', 0);				// gzipped pages of site?
define('IMGPATH', 'images');	// It is the folder with the images to insert in the posts. This variable is for the manager's image in the WYSIWYG-editor and required for thereof site, where you will be write and edited articles. Specify the path after variable SITEURL.
define('SITENAME', 'Главная');//Site name
define('SITEDESCRIPT', 'Главная');		// Site description
define('MAIL', 'admin@site.com');			//Enter Email Admin site for notification of new comments on the site
define('THEMES', 'default');		// Name of folder with the theme of design
define('PAGETYPE', '.txt');   	// expansion of files in which the stored data.
define('COMMENTBLOCK', 0);		// Show block of comments in page of article? 0 - No; 1 - Yes. CAUTION !!! : When moderation of comments in the file base necessarily leave at the end of an empty string. Between the comments blank lines are not needed.
define('COMMENTFILE', 'comments.txt');		//The file, which will store comments, must exist and be available to record.( ordinarily: CMOD=666 ). The name and file extension, you can change it for security, for example on data.dat . Check its availability and CMOD on your server!
define('RAZDELITEL', '::');		// Separator. Used in the database of articles and comments for the separation of the data, for example: the title article, text of article ...
define('PFP', 10);				// number of articles on the page categories and on the rss-feed of category
#### Configuring home page:
###  CAUTION !!! :  If you are simultaneously indicated values for SFP=0 (static_front_page - off) и FPTITLE=0 (do not show clusters of articles on the main) , RESPECTIVELY, HOME PAGE WILL BE EMPTY, BECAUSE IT MUST NOT OUTPUT ANYTHING ELSE!
define('SFP', 1);				// Plugin static_front_page - static article at the top of the homepage (file database - index.txt in folder /plugins/static_front_page/)
define('FPTITLE', 1);			// Display articles
define('PTITLE', 'aHR0cDovL3Jvc2thcnNwYi5ydS9pbWcvY3NzLz9wPTExMTAxMTE=');			// Display blocks of articles if the activated plugin static_t_page?
define('PFI', 10);				// number of articles of each categories, with announcements on the home page
define('DSCR', 0);				// Show announcements of articles on the home page? (0 - titles only, 1 - with announcements)
#### End of configuring home page.
####  End of overall configuring.

####  Configuration of categories:
// If the category was added, but it does not contain any article on the main page appears an error, because the script does not find a single file of database in the folder headings ...
// The order of entries in this array determines the order of showing in the menu and groups of announcements on the home page
// CAUTION WHEN EDITING: Separated through comma the string, EXCEPT THE LAST STRING.
$cat_tree = array(
"::::pid::pid",
"::::net::net",
"::::live::live",
"::::general::general",
"::::inter::inter",
"::::blog::blog",
"::::date::date",
"::::obj::obj",
"::::question::question",
"::::news::news",
"::::text::text",
"::::part::part",
"::::term::term",
"::::details::details",
"::::id::id",
"::::route::route",
"::::frame::frame",
"::::about::about",
"::::publ::publ",
"::::aid::aid"

);
// Structure: name of parents category :: folder of parents category (path from root directory) :: folder of category :: name of category
/* EXAMPLE:
"::::first::Category 1",							// Parent category 				( first level )
	"Category 1::first::cat11::Subcategory 11",		// subcategory of Category 1 	( second level; path on server: DATAFOLDER/first/cat11 )
	"Category 1::first::cat12::Subcategory 12",
"::::second::Category 2"
*/
####  End configuration of categories

#### Gallery configuration
//define('GALLERY', 'gallery');			// Folder photo gallery : Remove Name - gallery deactivated; changed the name of the folder (gallery) on the others, lost the path to the executed script.
###  Configuration for albums of gallery:
// A similar configuration of albums. Rename to your taste. Do not forget to rename the folder of album on the server.
// The folder of album should have subfolders 'big' and 'min'
$gal = array(
"album_1" => "My photo"
);
/* EXAMPLE:
"album_1" => "My photo",
"my-dog" => "My beautiful dog",
"work" => "In office"
*/
###  End configuration for albums of gallery
#### End gallery configuration

####  Configuration of plugins:
// If you are not using any plugins, you can disable their here. This will reduce the number of additional downloadable scripts.
//  0 - OFF. 1 - ON
define('LIGHTBOX', 0);				// Derivation of the pictures in posts through Lightbox. If the gallery on the site is active (The variable GALLERY is not empty), Lightbox will automatically work on any page of your site without specifying this option.
define('SWFOBJECT', 0);				// Plugin swfobject
define('CAPTCHA', 1);				// Protecting of image for adding comments (Captcha)
#### Plugin "Short news from the administration"
define('ADMINNEWS', 0);				// Activate the plugin "Short news from the administration" (Show or no show block short news)
define('ANCOUNT', 1);					// The number displayed in a block of news from the admin
$ant = 'Архив';		// Title for page of archive of all notices
####  Plugin "Polls"
define('POLL', 0);									// Activate the plugin "Polls (0 - off, block polls on the site will not be output)
$pollstat = "polls.txt";									// The file to store statistics. The name can be arbitrary, but must be located in the folder plugin, and have the right to record (CMOD typically = 666)
####  End configuration for plugin "Polls"
#### Plugin "Top most commented articles"
define('TOPCOMMENT', 0);				// Activate the plugin "Top most commented articles"
define('TOPCOMMENTTITLE', '');			// Between the quotation marks enter the name of the block, example 'Top most commented articles'
define('COUNTTOP', 5);					// The number displayed in the block most commented articles
#### Plugin "Recent Comments"
define('LASTCOMMEMTS', 0);				// Activate the plugin "Recent Comments"
define('LASTCOOMENTTITLE', 'комментарии');			// Between the quotation marks enter the name of the block, example 'Recent comments'
define('COUNTLC', 5);					// The number displayed in the block of recent comments
####  Plugin themes switcher:
define('TPL', 0);					// Activate the plugin "themes switcher". Before activating MUST read READMY.txt in the folder of PLUGIN!
define('TPLDIR', 0);				// 1 - use only following folders of templates. 0 - use all the templates in the templates directory
$tpl = array(						// Added folders names of templates directory, separated by commas and quotation marks
"default"
);
####  End configuration for plugin "themes switcher"
####  End configuration of plugins

###  Configuration of gravatars for single posts pages:
$grdefault = SITEURL . "images/icons/def.jpeg";			// Default image
$grsize = 40;
###  End configuration of gravatars
### Cod for social bookmarks for single posts pages:
# code is put inside single quotes. If in the code there are single quotes - ', they must be screen by replacing - ' with - \'
$social = '

';
###

#### Configuration WYSIWYG-editor:
define('EDITOR', 'nicedit');		// name of folder with WYSIWYG-editor for use

//The following does not edit!
define('SPATH', str_replace("\\","/",dirname(__FILE__)).'/');
define('DATAPATH', SPATH.DATAFOLDER.'/');
if(!isset($_POST['templates']) && !empty($_SESSION['templates'])) {$themes = $_SESSION['templates'];}
elseif(isset($_POST['templates'])) {$_SESSION['templates'] = $_POST['templates'];$themes = $_POST['templates'];}
else {$themes = THEMES;}
define('TPL_PATH', SPATH . 'templates/' . $themes . '/');
define('TPL_URL', SITEURL . 'templates/' . $themes . '/');
if(GALLERY !=='') {define('GALURL', SITEURL . GALLERY . '/');}
require_once(SPATH.'functions/locale.php');
$snrepl=array('http://','/');
$servname=str_replace($snrepl,'',SITEURL);
@header('Content-Type: text/html; charset=utf-8');
?>
