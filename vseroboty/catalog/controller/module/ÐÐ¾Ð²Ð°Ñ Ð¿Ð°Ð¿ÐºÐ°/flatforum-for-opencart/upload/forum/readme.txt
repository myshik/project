#============================================================================#
# FlatForum 1.4                                                              #
# by Plasma / Jon Petrosky  [12-06-2006]                                     #
# http://www.phatcode.net/                                                   #
#----------------------------------------------------------------------------#
# Usage Agreement:                                                           #
#   You can use this forum on your own site freely, as long as you do not    #
#   remove or change the "Powered by FlatForum" link in any way. You may     #
#   modify anything else in the script as you see fit. If you redistribute   #
#   the modified script, just make sure you give credit where it's due.      #
#============================================================================#


#============================================================================#
# What is It?                                                                #
#============================================================================#
FlatForum is a fast, clean, and simple threaded forum that uses a flatfile
database. It's designed for people who have small to medium-sized websites
and don't want a huge, bloated forum like phpBB.

(Note that this project is not related to the "flatforum" Drupal theme, which
 makes Drupal forums look like phpBB. If you are looking for this project,
 go to http://drupal.org/project/flatforum/.)


Features
--------
* Small and fast. The required files for FlatForum are less than 150k combined.

* Uses an optimized flatfile database. Unlike most flatfile forums, FlatForum
  won't bog down when your forum starts to get a lot of posts, because it
  doesn't load the entire database for each page view.

* Easy to customize. You don't have to muck through the forum script to
  change the appearance.

* Autolinking that WORKS (all URLs and email addresses).

* Identical appearance in IE, Mozilla, Firefox, Opera, and Netscape.

* Optional word filters (censors).

* Advanced spambot protection without requiring user registration or captchas.

* Free :)


#============================================================================#
# Installation                                                               #
#============================================================================#
FlatForum should work on any webserver running at least PHP 4.1.0, although
I've only tested it on Apache so far. It works on both Windows and Linux/UNIX
servers. It does not use any SQL, so mySQL, PostgreSQL, etc. are not required.


Permissions Note for Linux/UNIX
-------------------------------
If PHP runs as an Apache Module, it most likely runs as "nobody", which means
you need to set global write permissions (666/777) on the files and folders
so PHP can access them.

If PHP runs as CGI (this includes servers running "phpsuexec" and "suphp"),
PHP runs as user, so you do not need global permissions (use 644/755 instead).
In fact, many servers will refuse to run your script if the permissions are
set too high.

If you are unsure of the permissions you need, try 644/755 first, and then
666/777.


Setup Procedure
---------------

1. FTP in to your server.

2. Create a folder for the forum. (I recommend /forum for simplicity)

3. If you have a Linux/UNIX server, CHMOD the forum folder to 755/777.
   (If you have a Windows server, you can skip this step.)

4. Upload these files to your forum folder on the server:

   footer.php    --\
   header.php       \
   index.php          Upload these files to /forum
   settings.php     /
   nqb.css       --/

5. Create a sub-folder in your forum folder called "data"

6. If you have a Linux/UNIX server, CHMOD the data folder to 755/777.
   (Again, if you have a Windows server, you can skip this step.)

7. Upload these files to your data sub-folder:

   .htaccess     --\
   db.txt           \
   filters.txt        Upload these files to /forum/data 
   password.php     /
   vars.txt      --/

8. If you have a Linux/UNIX server, CHMOD all the files in the data folder
   to 644/666. (If you have a Windows server, skip this step.)

9. Your forum should now be up and running. You can try it out by going to:

   http://www.yoursite.com/forum/

   (Where "yoursite.com" is the name of your website...obviously...)
   If the forum loads, you can go on to the next step. If you get a directory
   listing instead of the forum, try going to:

   http://www.yoursite.com/forum/index.php

   If the forum loads, then you need to configure your webserver to treat
   index.php as an index file. (Or create an index.html that redirects to
   index.php, it's up to you.) There's plenty of people on the net who
   can help you with this, so I won't go into detail here...

   However, If you see the script source or your browser tries to download
   index.php, this means that your webserver doesn't support PHP. You'll have
   to contact your hosting company about getting PHP support. (Or if you're
   cheap and are using a free host, find a different free one that at least
   supports PHP.)

10. This is the last step, but it's also the most important. You need to change
    the admin password. To do this, go to:

    http://www.yoursite.com/forum/index.php?action=admin

    The default password is "password", without the quotes. Once you're logged
    in, click the link to change the password. Make sure that nobody else
    knows your password! After you've changed your password, you can log out.

That wasn't so hard, now was it?


#============================================================================#
# Customization                                                              #
#============================================================================#
Now that your forum is set up, you'll probably want to customize it to match
the rest of your website. There are a couple of files that you can change
to do this.

The first is settings.php. If you open this file up with a text editor, you
should see a bunch of settings, along with comments that explain what they do.
The settings are divided into sections. I'll go through each section and
explain them.


Website/Server Settings
-----------------------
WEBSITE_NAME - The name of your website. You'll want to change this. :)

TIME_OFFSET - Server time offset, in seconds. If your webserver is located in
              a different time zone than you are, you can change this so that
              the times on the board match your local time. For example, if
              your server's time is 3 hours behind your time, you would set
              TIME_OFFSET to 10800 (60 sec * 60 min * 3 hrs).

DATE_FORMAT - The format of the date and time displayed on the forum. Go to
              http://www.php.net/date/ to see a list of valid characters
              and some examples.

BR - The line break to use. On Windows systems, this is "\r\n". On UNIX
     systems, it's usually just "\n". You shouldn't have to change this, even
     if you have a UNIX server, but if you do, here it is.


Forum Flatfile Database Settings
--------------------------------
You shouldn't have to change any of the settings in this section, but here
they are anyway.

FORUM_DATA_PATH - Your "data" sub-folder (must be writable). This is where
                  the messages will be stored.

FORUM_DATA_INDEX - The flatfile database index file. Must be writable.

FORUM_DATA_VARS - Forum variables file. Again, must be writable.

FORUM_DATA_FILTERS - Filter/trigger words file. Must be writeable.


Input Options
-------------
AUTO_SUBJECT - Set this to 're: ' (or any other string) to enable auto-subject
               on replies. Set to null ('') to disable auto-subjects.

FILTERS_ENABLED - Set to true to enable word filters. You can add/remove
                  filters in the admin section. Filters are not case sensitive,
                  but only whole words are matched.

TAG_CODE_ENABLED - Set to true to allow code tags (<code></code>)

TAG_B_ENABLED - Set to true to allow bold tags (<b></b>)

TAG_I_ENABLED - Set to true to allow italics tags (<i></i>)

TAG_U_ENABLED - Set to true to allow underline tags (<u></u>)


Anti-Spam Settings
------------------
TRIGGERS_ENABLED - Set to true to enable spam triggers. If a trigger phrase
                   is found, the message is deleted as spam. Trigger phrases
                   are case sensitive, but will match any part of the string
                   including partial words and symbols.

TRAP_ENABLED - Set to true to use a trap field to prevent spam. A trap field
               is an additional form field which is invisible to all browsers
               which support CSS. Spambots ignore CSS, so they see the field
               and try to put text in it when they submit the form. If this
               is detected, the message is deleted as spam. Note that trap
               fields will cause problems with VERY old browsers that do not
               support CSS. (IE 2.x, Netscape 3.x)

TIMER_ENABLED - Set to true to use timed forms. If the message is not posted
                from the original form within the set time threshold, it is
                deleted as spam. The time of the original form is encoded in
                the field name. Timed forms prevent spammers from manually
                inspecting your forum HTML and hard-coding the forum field
                names into a spambot, since the field names are valid for only
                a short time.

TIMER_MIN - Minimum time in seconds before the form can be submitted. Applies
            only if TIMER_ENABLED is true. Increasing the minimum time helps
            prevent flooding.

TIMER_MAX - Maximum time in seconds before the form can be submitted. Applies
            only if TIMER_ENABLED is true. Decreasing the maximum time helps
            prevent spamming.

SLV_ENABLED - Set to true to use spam link verification (SLV). SLV is a new
              system where user input from forums, guestbooks, and blogs is
              passed through an SLV server which monitors frequent spam links.
              If the SLV server detects that the message is spam, it will
              notify the forum script, and the script will delete the message
              as spam. Unfortunately, SLV is not very effective yet, as not
              many sites are using the system. However, it is recommended
              that you leave SLV enabled, as this will improve the system for
              everyone. See http://www.linksleeve.org/ for more information.

SLV_URL - Complete URL of SLV XML-RPC ('http://www.linksleeve.org/slv.php')

SLV_TIMEOUT - Time to wait in seconds for SLV server response.

SPAM_RESPONSE_HEADER - HTTP header returned to spammer if a spam message is
                       detected, using any of the above methods. For example,
                       to return a 404 error, use: 'HTTP/1.0 404 Not Found'


Page Template
-------------
PAGE_HEADER - PHP file to be included at the start of each page. By editing
              this file or specifying your own, you can change the forum
              appearance. Note that if the trap field is enabled, this file
              needs '$style_inline' in the HTML head section in order for the
              trap field to be hidden correctly. (See the provided header.php)

PAGE_FOOTER - Same as above, except it's included at the end of each page.

STYLE_SHEET - The stylesheet to use. (Settings for fonts, colors, spacing, etc.)


Page Titles
-----------
PAGE_TITLE - Displayed when browsing the forum

PAGE_TITLE_NEW - Displayed when posting a new message

PAGE_TITLE_VIEW - Displayed when viewing a message

PAGE_TITLE_ADMIN - Displayed in the admin section


Forum Appearance
----------------
FORUM_TITLE - HTML that is displayed at the top of each forum page. You'll
              probably want to change this to match the rest of your site.

FORUM_TITLE_ADMIN - HTML that is displayed at the top of each admin page.
                    Again, you'll probably want to change this.

FORUM_NT_MARKER - Marker to display when a message has no text. Notice that
                  this is NOT updated dynamically, so if you change this,
                  it will only affect new posts.

FORUM_HR - The "horizontal rule" (i.e. line) to use between messages. If you
           don't want a horizontal rule, you can set this to null (''). If you
           want a colored rule, you should use a colored HTML table, because
           Mozilla and Opera don't render colored HRs.


Forum Sizes and Limits
----------------------
FORUM_MSG_MAX - Maximum number of messages to display per page. Note that it
                is possible that a few more messages may be displayed than
                this limit. That is because the forum won't break threads in
                middle when the limit is reached.

FORUM_NAME_MAXLEN - Maximum number of characters allowed for names.

FORUM_SUBJECT_MAXLEN - Maximum number of characters allowed in the subject.

FORUM_MSG_MAXLEN - Maximum number of characters allowed in the message.

FORUM_NAME_FIELDLEN - Size of the name field in characters.

FORUM_SUBJECT_FIELDLEN - Size of the subject field in characters.

FORUM_MSG_FIELDWIDTH - Width of the message field in characters.

FORUM_MSG_FIELDHEIGHT - Height of the message field in characters.

FORUM_FORCE_WRAP_MAIN - Number of characters to force wrapping at on the main
                        page (thread index). Forcing wrapping on the main page
                        helps preserve the layout of your site if you
                        frequently have "monster" threads.

FORUM_FORCE_WRAP_POST - Number of characters to force wrapping at in the post.
                        Forcing wrapping in posts helps preserve the layout
                        of your site if somebody posts a very long string with
                        no spaces. (Usually it's somebody being annoying...)


Text Strings
------------
These are all the text strings (besides the ones defined above) that the forum
displays. If you want the forum to use a different language, you may translate
these strings.


That's it for the settings.php file; other files that you can change to
modify the forum appearance are the header.php and footer.php files.
These are pretty self-explanatory.


#============================================================================#
# Admin Section                                                              #
#============================================================================#
You can access the admin section of your forum by going to:

http://www.yoursite.com/forum/index.php?action=admin

or, if your webserver is set up to use index.php as the index (most are):

http://www.yoursite.com/forum/?action=admin

Once you are logged in, you can edit filters/triggers, change your password,
edit messages, remove selected messages, remove all messages from specific
IP, or resync the database. (If the database doesn't match the variable file
for some reason, this will fix it.) If you are on a public computer, make
sure you log out or close the browser window when you are finished in the
admin section.


#============================================================================#
# Styles                                                                     #
#============================================================================#
FlatForum comes with two styles by default, "Nemesis QB Blue" (nqb.css) and
"Phat Dark" (phat.css). To specify which style to use, change the STYLE_SHEET
define in settings.php. You can, of course, make your own style to match
your site by editing the stylesheets.


#============================================================================#
# Greetz                                                                     #
#============================================================================#
Special thanks to Subxero and Dav for their ideas for improvements!


#============================================================================#
# I Guess That's It...                                                       #
#============================================================================#
Well, this is the first major version of FlatForum, so if you find any bugs
or have any suggestions, please let me know by emailing me at
plasma@phatcode.net :)


#============================================================================#
# Version History:                                                           #
#============================================================================#
1.4 - Dec 6 2006 * Relaxed license
                 * Compatible with PHP 5.x
                 * Added ability for admin to edit posts
                 * Added option for auto subject line (re: ...)
                 * Added option to allow/disallow <code>, <b>, <i>, <u>
                 * Fixed bookmarks (#) in autolinking
                 * Added word filters
                 * Added spambot protection:
                     Encoded field names
                     Trigger phrases
                     Trap field
                     Timed forms
                     Link verification (LinkSleeve)

1.3 - Mar 8 2005 * Text files are no longer generated or needed for
                     posts without messages (You can safely delete
                     all .txt files that are 0 bytes in size.)
                 * Word-wrapping for "normal" text fixed in IE

1.2 - Nov 6 2004 * All email address are now UTF-8 encoded to
                     protect from spambots
                 * Site Layout protection (long lines wrapped)
                 * Improved code formatting
                 * Improved autolinking
                 * No-text marker is now dynamic
                 * No longer causes PHP notices

1.1 - Mar 4 2004 * Fixed buggy sessions in Windows/CGI setups
                 * Added file_get_contents for PHP versions <4.3.0

1.0 - Mar 3 2004 * Initial release
