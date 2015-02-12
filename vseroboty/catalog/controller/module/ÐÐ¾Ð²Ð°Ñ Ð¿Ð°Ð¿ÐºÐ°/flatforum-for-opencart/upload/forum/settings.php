<?php
#============================================================================#
# FlatForum 1.4                                                              #
# by Plasma / Jon Petrosky  [12-06-2006]                                     #
# http://www.phatcode.net/                                                   #
#----------------------------------------------------------------------------#
# See "readme.txt" for more information about these settings.                #
#============================================================================#


#============================================================================#
# Website / Server Settings                                                  #
#============================================================================#
define('WEBSITE_NAME', 'OpenCart Store');      # The name of your website
define('TIME_OFFSET' , 0);                     # Server time offset, in seconds
define('DATE_FORMAT' , 'D M j Y \a\\t g:i a'); # Format of the date (See www.php.net/date)
define('BR'          , "\r\n");                # Line break


#============================================================================#
# Forum Flatfile Database Settings                                           #
#============================================================================#
define('FORUM_DATA_PATH' , 'data/');                         # Path to db (must be writable!)
define('FORUM_DATA_INDEX', FORUM_DATA_PATH.'db.txt');        # Flatfile db index
define('FORUM_DATA_VARS' , FORUM_DATA_PATH.'vars.txt');      # Forum variables
define('FORUM_DATA_FILTERS', FORUM_DATA_PATH.'filters.txt'); # Filter/trigger words


#============================================================================#
# Input Options                                                              #
#============================================================================#
define('AUTO_SUBJECT', 're: ');     # Set to 're: ' (or any other string) to enable auto-subject on replies
define('FILTERS_ENABLED', true);    # Enable word filters
define('TAG_CODE_ENABLED', true);   # Allow <code></code>
define('TAG_B_ENABLED', true);      # Allow <b></b>
define('TAG_I_ENABLED', true);      # Allow <i></i>
define('TAG_U_ENABLED', true);      # Allow <u></u>


#============================================================================#
# Anti-Spam Settings                                                         #
#============================================================================#
define('TRIGGERS_ENABLED', true);   # Enable spam triggers
define('TRAP_ENABLED', true);       # Use trap field
define('TIMER_ENABLED', true);      # Use timed forms
define('TIMER_MIN', 2);             # Minimum time (seconds) before form can be submitted (anti-flood)
define('TIMER_MAX', 60*60*2);       # Maximum time (seconds) before form can be submitted (anti-spam)
define('SLV_ENABLED', true);        # Use spam link verification (SLV)
define('SLV_URL', 'http://www.linksleeve.org/slv.php');   # Complete URL of SLV XML-RPC
define('SLV_TIMEOUT', 3);           # Time to wait (seconds) for SLV server response
# HTTP header returned to spammer
define('SPAM_RESPONSE_HEADER', "Location: http://en.wikipedia.org/wiki/Spam_(electronic)\r\n");
# To return a 404 error code, use:
#define('SPAM_RESPONSE_HEADER', "HTTP/1.0 404 Not Found");


#============================================================================#
# Page Template                                                              #
#----------------------------------------------------------------------------#
# These files will be included before and after the forum script output.     #
# By editing them, you can change the appearance of the forum to match your  #
# site.                                                                      #
#============================================================================#
define('PAGE_HEADER', 'header.php');
define('PAGE_FOOTER', 'footer.php');
define('STYLE_SHEET', 'nqb.css');


#============================================================================#
# Page Titles                                                                #
#----------------------------------------------------------------------------#
# These are the strings that will be displayed in the browser title bar      #
#============================================================================#
define('PAGE_TITLE'      , WEBSITE_NAME.' / Forum');
define('PAGE_TITLE_NEW'  , PAGE_TITLE.' / New Message');
define('PAGE_TITLE_VIEW' , PAGE_TITLE.' / View Message');
define('PAGE_TITLE_ADMIN', PAGE_TITLE.' / Admin');


#============================================================================#
# Forum Appearance                                                           #
#============================================================================#
# These are the HTML titles that are displayed at the top of the forum
define('FORUM_TITLE'      , '<table cellpadding="6" cellspacing="0" width="100%"><tr><td class="forum_shade"><h1>Forum</h1></td></tr></table><br>'.BR);
define('FORUM_TITLE_ADMIN', '<table cellpadding="6" cellspacing="0" width="100%"><tr><td class="forum_shade"><h1>Forum Admin</h1></td></tr></table><br>'.BR);
# No-text marker
define('FORUM_NT_MARKER'  , '*');
# Horizontal rule
define('FORUM_HR', space(10).'<div align="center" style="width:100%;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td class="forum_shade_hr"></td></tr></table></div></div>'.space(10).BR);


#============================================================================#
# Forum Sizes and Limits                                                     #
#============================================================================#
define('FORUM_MSG_MAX'         , 30);    # Maximum number of messages to display per page
define('FORUM_NAME_MAXLEN'     , 32);     # Maximum character length allowed for names
define('FORUM_SUBJECT_MAXLEN'  , 64);     # Maximum character length allowed for subjects
define('FORUM_MSG_MAXLEN'      , 131072); # Maximum character length allowed for messages
define('FORUM_NAME_FIELDLEN'   , 32);     # Size of the name field
define('FORUM_SUBJECT_FIELDLEN', 32);     # Size of the subject field
define('FORUM_MSG_FIELDWIDTH'  , 60);     # Width of the message field
define('FORUM_MSG_FIELDHEIGHT' , 14);     # Height of the message field
define('FORUM_FORCE_WRAP_MAIN' , 20);     # Number of characters to force wrapping at on the main page (thread index)
define('FORUM_FORCE_WRAP_POST' , 40);     # Number of characters to force wrapping at in the post


#============================================================================#
# Text Strings (currently English only)                                      #
#----------------------------------------------------------------------------#
# If you would like to use a different language, you can translate the       #
# strings below.                                                             #
#============================================================================#
define('TEXT_ADMINHELP'   , 'To edit the content of a message, click on the subject line. To remove individual messages, check the box next to the message(s) that you want to remove and click the button below. To remove all messages from a specific IP, just click on the IP address.');
define('TEXT_ALLMSGSFROM' , 'All messages from');
define('TEXT_BACKTOMSG'   , 'Back to Messages');
define('TEXT_CHANGE'      , 'Change');
define('TEXT_CHPASS'      , 'Change Password');
define('TEXT_CHSAVED'     , 'Changes to message have been saved.');
define('TEXT_EDITFILTERS' , 'Edit Filters/Triggers');
define('TEXT_EDITMSG'     , 'Edit Message');
define('TEXT_ENTERPASS'   , 'Enter Password');
define('TEXT_EXCEPTFOR'   , 'except for');
define('TEXT_FILTERHELP'  , 'Words in the "From" section will be replaced with their corresponding word in the "To" section.');
define('TEXT_FILTERSUPD'  , 'Word filters have been updated.');
define('TEXT_FROM'        , 'From');
define('TEXT_GOBACK'      , 'Go Back');
define('TEXT_HAVEBEENREM' , 'have been removed.');
define('TEXT_INMSGONLY'   , 'in the message only');
define('TEXT_LINKS'       , 'All URLs and email addresses will automatically be converted to hyperlinks.');
define('TEXT_LOGIN'       , 'Login');
define('TEXT_LOGOUT'      , 'Log Out');
define('TEXT_MESSAGE'     , 'Message');
define('TEXT_MESSAGESIN'  , 'messages in');
define('TEXT_MISSINGNS'   , 'You must enter both your name and a subject for your post.');
define('TEXT_MISSINGNSA'  , 'You must enter both a name and a subject for the post.');
define('TEXT_MISSINGPASS' , 'You must enter all the passwords.');
define('TEXT_MSGPERPAGE'  , 'messages per page.');
define('TEXT_NAME'        , 'Name');
define('TEXT_NEWPASS'     , 'New Password');
define('TEXT_NEWPASSERR'  , 'New passwords do not match. Please retype them.');
define('TEXT_NEXTPAGE'    , 'Next Page');
define('TEXT_NO'          , 'No');
define('TEXT_NOHTML'      , 'No HTML is allowed');
define('TEXT_OLDPASS'     , 'Old Password');
define('TEXT_OLDPASSERR'  , 'Old password is incorrect.');
define('TEXT_PASSCH'      , 'Password changed.');
define('TEXT_PASSERR'     , 'Incorrect Password.');
define('TEXT_POSTMSG'     , 'Post Message');
define('TEXT_POSTNEWMSG'  , 'Post a New Message');
define('TEXT_POWEREDBY'   , 'Powered by');
define('TEXT_REMOVED'     , 'All selected messages have been removed.');
define('TEXT_REMOVEMSG'   , 'Remove Messages');
define('TEXT_REMOVEFROM'  , 'Are you sure you want to remove all messages from');
define('TEXT_REMOVESELECT', 'Remove Selected Messages');
define('TEXT_REMOVESURE'  , 'Are you sure you want to remove these messages and all their replies?');
define('TEXT_REPLYTOMSG'  , 'Reply to this Message');
define('TEXT_RESYNCDB'    , 'Resync Database');
define('TEXT_RESYNCDBOK'  , 'Forum database successfully resynchronized.');
define('TEXT_RNEWPASS'    , 'Retype New Password');
define('TEXT_SAVECHANGES' , 'Save Changes');
define('TEXT_SPAMTRIGGERS', 'Spam Triggers');
define('TEXT_SUBJECT'     , 'Subject');
define('TEXT_THREADSDISP' , 'threads. Displaying');
define('TEXT_TO'          , 'To');
define('TEXT_TRIGGERHELP' , 'Any phrase below found in a message will cause it to be immediately discarded as spam.');
define('TEXT_TRIGGERSUPD' , 'Spam triggers have been updated.');
define('TEXT_UNKNOWN'     , 'unknown');
define('TEXT_WORDFILTERS' , 'Word Filters');
define('TEXT_YES'         , 'Yes');

?>
