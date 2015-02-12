<?php
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
#----------------------------------------------------------------------------#
# Version History:                                                           #
#   1.4 - Dec 6 2006 * Relaxed license                                       #
#                    * Compatible with PHP 5.x                               #
#                    * Added ability for admin to edit posts                 #
#                    * Added option for auto subject line (re: ...)          #
#                    * Added option to allow/disallow <code>, <b>, <i>, <u>  #
#                    * Fixed bookmarks (#) in autolinking                    #
#                    * Added word filters                                    #
#                    * Added spambot protection:                             #
#                        Encoded field names                                 #
#                        Trigger phrases                                     #
#                        Trap field                                          #
#                        Timed forms                                         #
#                        Link verification (LinkSleeve)                      #
#                                                                            #
#   1.3 - Mar 8 2005 * Text files are no longer generated or needed for      #
#                        posts without messages (You can safely delete       #
#                        all .txt files that are 0 bytes in size.)           #
#                    * Word-wrapping for "normal" text fixed in IE           #
#                                                                            #
#   1.2 - Nov 6 2004 * All email address are now UTF-8 encoded to            #
#                        protect from spambots                               #
#                    * Site Layout protection (long lines wrapped)           #
#                    * Improved code formatting                              #
#                    * Improved autolinking                                  #
#                    * No-text marker is now dynamic                         #
#                    * No longer causes PHP notices                          #
#                                                                            #
#   1.1 - Mar 4 2004 * Fixed buggy sessions in Windows/CGI setups            #
#                    * Added file_get_contents for PHP versions <4.3.0       #
#                                                                            #
#   1.0 - Mar 3 2004 * Initial release                                       #
#============================================================================#

# You can modify settings.php to change the forum settings
require('settings.php');

# Define the version
define('FORUM_VERSION', '1.4');

# Provide our own functions if using an older version of PHP

# file_get_contents for PHP < 4.3.0
if (!function_exists('file_get_contents'))
{
    function file_get_contents ($filename)
    {
        $fp = fopen($filename, 'r');
        $data = fread($fp, filesize($filename));
        fclose($fp);
        return $data;
    }
}

# stream_get_meta_data for PHP < 4.3.0
if (!function_exists('stream_get_meta_data'))
{
    function stream_get_meta_data ($fp)
    {
        return false;
    }
}

# html_entity_decode for PHP < 4.3.0
if (!function_exists('html_entity_decode'))
{
    function html_entity_decode ($string)
    {
       # Replace numeric entities
       $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
       $string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);

       # Replace literal entities
       $trans_tbl = get_html_translation_table(HTML_ENTITIES);
       $trans_tbl = array_flip($trans_tbl);

       return strtr($string, $trans_tbl);
    }
}

# stripos for PHP < 5.0.0
if (!function_exists('stripos'))
{
  function stripos ($string, $needle, $offset = 0)
  {
     return strpos(strtolower($string), strtolower($needle), $offset);
  }
}

# PHP >= 4.2.0 automatically seeds the random number generator
if (version_compare(phpversion(), '4.2.0', '<'))
{
    mt_srand(crc32(microtime()));
}

# Stupid browsers can't agree on standards...
if (isset($_SERVER['HTTP_USER_AGENT']))
{
    $browser = strtolower($_SERVER['HTTP_USER_AGENT']);

    if (strpos($browser, 'gecko') !== false)      # Mozilla/Netscape/Firefox
    {
        define('BROWSER', 'mozilla');
        define('FORUM_MARGIN_ADJUST', '14px');
        define('FORUM_WRAP', '<span style="font-size:0px"> </span>');
    }
    elseif (strpos($browser, 'opera') !== false)  # Opera
    {
        define('BROWSER', 'opera');
        define('FORUM_MARGIN_ADJUST', '15px');
        define('FORUM_WRAP', '&#173;');
    }
    else
    {
        define('BROWSER', 'msie');                # IE and all others
        define('FORUM_MARGIN_ADJUST', '16px');
        define('FORUM_WRAP', '<wbr />');
    }
}
else
{
    define('BROWSER', 'msie');                     # Assume IE blocking user-agent
    define('FORUM_MARGIN_ADJUST', '16px');
    define('FORUM_WRAP', '<wbr />');
}

# Setup random class names if the trap field is enabled
# (This will make it harder for a spambot to figure out which field is the trap)
if (TRAP_ENABLED)
{
    $rand = array();
    for ($i=0; $i<10; $i++) $rand[] = 'c'.$i;
    shuffle($rand);
    
    define('NAME_CLASS', $rand[0]);
    define('SUBJECT_CLASS', $rand[1]);
    define('TRAP_CLASS', $rand[2]);
    define('DUMMY_CLASS_1', $rand[3]);
    define('DUMMY_CLASS_2', $rand[4]);
    define('DUMMY_CLASS_3', $rand[5]);
    define('DUMMY_CLASS_4', $rand[6]);
    define('DUMMY_CLASS_5', $rand[7]);
    define('DUMMY_CLASS_6', $rand[8]);
    define('DUMMY_CLASS_7', $rand[9]);
}
else
{
    define('NAME_CLASS', '');
    define('SUBJECT_CLASS', '');
    define('TRAP_CLASS', '');
}


# Main action handler
$action = get_var('action');
switch ($action)
{
    case 'new':
        forum_new();
        break;
    case 'admin':
        forum_admin();
        break;
    case 'post':
        forum_post();
        break;
    case 'view':
        forum_view();
        break;
    default:
        forum_main();
        break;
}



#============================================================================#
# page_start - Invoked at the start of each normal page                      #
#              (this is the "template")                                      #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $title - Text to display in the browser title bar (appended to template) #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function page_start ($title)
{
    $style_sheet = STYLE_SHEET;

    # Generate inline css for form fields if using trap field
    $style_inline = '';
    if (TRAP_ENABLED)
    {
        $class = array();
        $class[] = '.'.NAME_CLASS.' {display:inline;}'.BR;
        $class[] = '.'.SUBJECT_CLASS.' {display:inline;}'.BR;
        $class[] = '.'.TRAP_CLASS.' {display:none;}'.BR;
        $class[] = '.'.DUMMY_CLASS_1.' {display:inline;}'.BR;
        $class[] = '.'.DUMMY_CLASS_2.' {display:inline;}'.BR;
        $class[] = '.'.DUMMY_CLASS_3.' {display:inline;}'.BR;
        $class[] = '.'.DUMMY_CLASS_4.' {display:none;}'.BR;
        $class[] = '.'.DUMMY_CLASS_5.' {display:none;}'.BR;
        $class[] = '.'.DUMMY_CLASS_6.' {display:none;}'.BR;
        $class[] = '.'.DUMMY_CLASS_7.' {display:none;}'.BR;
        shuffle($class);
        
        $style_inline = '<style type="text/css">'.BR.'<!--'.BR;
        foreach ($class as $this_class) $style_inline .= $this_class;
        $style_inline .= '-->'.BR.'</style>';
    }

    include(PAGE_HEADER);
}


#============================================================================#
# page_end - Invoked at the end of each normal page                          #
#            (completes the template)                                        #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function page_end ()
{
    include(PAGE_FOOTER);
}


#============================================================================#
# forum_load_all - Loads the entire forum index                              #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (function) - Array containing entire forum index                         #
#============================================================================#
function forum_load_all ()
{
    $forum_db = array();
    foreach (file(FORUM_DATA_INDEX) as $line)
    {
        $line = trim($line);
        if ($line != '')
        {
            list($this_id, $root, $parent, $name, $subject, $notext, $time, $ip, $replies) = explode('`', $line);
            $forum_db[] = array('id'      => $this_id,
                                'root'    => $root,
                                'parent'  => $parent,
                                'name'    => $name,
                                'subject' => $subject,
                                'notext'  => $notext,
                                'time'    => $time,
                                'ip'      => $ip,
                                'replies' => $replies);
        }
    }
    return $forum_db;
}


#============================================================================#
# forum_save_all - Saves the entire forum index                              #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $forum_db - Entire forum db array                                        #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_save_all ($forum_db)
{
    $fp = fopen(FORUM_DATA_INDEX, 'w');
    flock($fp, LOCK_EX);
    foreach ($forum_db as $post)
    {
        fwrite($fp, $post['id'].'`'.$post['root'].'`'.$post['parent'].'`'.$post['name'].'`'.$post['subject'].'`'.$post['notext'].'`'.$post['time'].'`'.$post['ip'].'`'.$post['replies'].BR);
    }
    flock($fp, LOCK_UN);
    fclose($fp);
}


#============================================================================#
# forum_load_page - Loads the forum index for the page starting with         #
#                   the specified post id                                    #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $start - Id of the first post on the forum page                          #
# Returns:                                                                   #
#   (function) - Array containing forum index for the page                   #
#============================================================================#
function forum_load_page ($start)
{
    $fp = fopen(FORUM_DATA_INDEX, 'r');
    for ($index=0; $index<$start; $index++)
    {
        fgets($fp);
    }
    $posts = 0;

    $forum_db = array();
    while (!feof($fp))
    {
        $line = trim(fgets($fp));
        if ($line != '')
        {
            list($this_id, $root, $parent, $name, $subject, $notext, $time, $ip, $replies) = explode('`', $line);
            $forum_db[$index] = array('id'      => $this_id,
                                      'root'    => $root,
                                      'parent'  => $parent,
                                      'name'    => $name,
                                      'subject' => $subject,
                                      'notext'  => $notext,
                                      'time'    => $time,
                                      'ip'      => $ip,
                                      'replies' => $replies);
            $posts++;
            if (($posts>FORUM_MSG_MAX) && ($forum_db[$index]['id'] == $forum_db[$index]['root'])) break;
            $index++;
        }
    }
    fclose($fp);
    return $forum_db;
}


#============================================================================#
# forum_load_thread - Loads the forum index for the thread containing        #
#                     the specified post id                                  #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $id - Id of a post in the thread                                         #
# Returns:                                                                   #
#   (function) - Array containing forum index for the thread                 #
#       $index - forum db array index of post with specified id              #
#  $root_index - forum db array index of root post for the specified thread  #
#============================================================================#
function forum_load_thread ($id, &$index, &$root_index)
{
    $fp = fopen(FORUM_DATA_INDEX, 'r');
    $forum_db = array();
    $index = false;
    $count = 0;
    while (!feof($fp))
    {
        $line = trim(fgets($fp));
        if ($line != '')
        {
            list($this_id, $root, $parent, $name, $subject, $notext, $time, $ip, $replies) = explode('`', $line);
            $forum_db[$count] = array('id'      => $this_id,
                                      'root'    => $root,
                                      'parent'  => $parent,
                                      'name'    => $name,
                                      'subject' => $subject,
                                      'notext'  => $notext,
                                      'time'    => $time,
                                      'ip'      => $ip,
                                      'replies' => $replies);

            if (!isset($forum_db[$count]['root'])) break;
            if (($index !== false) && ($forum_db[$count]['id'] == $forum_db[$count]['root'])) break;
            if ($forum_db[$count]['id'] == $id) $index = $count;
            $count++;
        }
    }
    fclose($fp);
    if ($index === false) return false;

    foreach ($forum_db as $count => $post)
    {
        if ($post['id'] == $forum_db[$index]['root'])
        {
            $root_index = $count;
            break;
        }
    }
    return $forum_db;
}


#============================================================================#
# forum_load_filters - Loads the filters/triggers                            #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (Array 1) - Filters                                                      #
#   (Array 2) - Triggers                                                     #
#============================================================================#
function forum_load_filters ()
{
    $filter_db = array();
    $trigger_db = array();
    foreach (file(FORUM_DATA_FILTERS) as $line)
    {
        $line = trim($line);
        if ($line != '')
        {
            list($from, $to) = explode('`', $line);
            if ($to == '~')
            {
                $trigger_db[] = $from;
            }
            else
            {
                $filter_db[] = array('from' => $from, 'to' => $to);
            }
        }
    }
    return array($filter_db, $trigger_db);
}


#============================================================================#
# forum_save_filters - Saves the filters/triggers                            #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#    $filter_db - Filters                                                    #
#   $trigger_db - Triggers                                                   #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_save_filters ($filter_db, $trigger_db)
{
    $fp = fopen(FORUM_DATA_FILTERS, 'w');
    flock($fp, LOCK_EX);
    foreach ($filter_db as $filter)
    {
        fwrite($fp, $filter['from'].'`'.$filter['to'].BR);
    }
    foreach ($trigger_db as $trigger)
    {
        fwrite($fp, $trigger.'`~'.BR);
    }
    flock($fp, LOCK_UN);
    fclose($fp);
}


#============================================================================#
# forum_main - Displays the main forum pages                                 #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_main ()
{
    # Start the page
    page_start(PAGE_TITLE);
    print(FORUM_TITLE);
    print('<div align="center"><a href="'.php_self().'?action=new">'.TEXT_POSTNEWMSG.'</a></div><br />'.BR);

    # Get the id of the start post and load the forum db for this page only
    $start = get_var('start');
    if (($start=='') || ($start<0)) $start = 0;
    $forum_db = forum_load_page($start);

    # Get the forum stats to display at the bottom of the page
    $fp = fopen(FORUM_DATA_VARS, 'r');
    fgets($fp);
    $num_msgs = trim(fgets($fp));
    $num_threads = trim(fgets($fp));
    fclose($fp);

    # Display all the threads on this page
    # (very easy using recursion...)
    print('<ul class="forum_ul">'.BR);
    for ($index=$start; ($index<$num_msgs) && (($index-$start)<FORUM_MSG_MAX); $index++)
    {
        print(FORUM_HR);
        print('<div style="width:100%; overflow:hidden; margin-left:-'.FORUM_MARGIN_ADJUST.';">'.BR);
        $index = forum_show($forum_db, $index, false, 1);
        print('</div>'.BR);
    }
    print(FORUM_HR.'</ul><br />'.BR);

    # If we're not on the last page, show a link to the next page
    # (due to the nature of the forum, there's no easy way to display a
    #  link to the previous page... :\ )
    if ($index < $num_msgs)
    {
        print('<div align="center">');
        print('<a href="'.php_self().'?start='.$index.'">'.TEXT_NEXTPAGE.'</a><br /></div>');
    }

    # Finish off the page
    print('<br /><table width="100%" border="0" cellpadding="0" cellspacing="0">'.BR);
    print('<tr><td class="forum_small">'.$num_msgs.' '.TEXT_MESSAGESIN.' '.$num_threads.' '.TEXT_THREADSDISP.' '.FORUM_MSG_MAX.' '.TEXT_MSGPERPAGE.'</td>'.BR);
    print('<td align="right" class="forum_small">'.TEXT_POWEREDBY.' <a href="http://www.phatcode.net/find/flatforum">FlatForum '.FORUM_VERSION.'</a></td></tr></table>'.BR);
    page_end();
}


#============================================================================#
# forum_show - Recursive function used to display message threads            #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $forum_db - Forum database                                               #
#      $index - Forum db array index of post to display                      #
#   $is_reply - true if thread list is for a reply, otherwise false          #
#      $level - Thread level (used to adjust spacing)                        #
# Returns:                                                                   #
#   (function) - Updated index after all child posts have been displayed     #
#============================================================================#
function forum_show ($forum_db, $index, $is_reply=false, $level=1)
{
    # Display our post
    $post = $forum_db[$index];

    $subject = $post['subject'];
    if (strpos($subject, '@') !== false)
    {
        $subject = fast_wordwrap($subject, FORUM_FORCE_WRAP_MAIN, '`');
        $subject = html_unicode($subject);
        $subject = str_replace('&#96;', FORUM_WRAP, $subject);
    }
    else
    {
        $subject = fast_wordwrap($subject, FORUM_FORCE_WRAP_MAIN, FORUM_WRAP);
    }

    $name = $post['name'];
    if (strpos($name, '@') !== false)
    {
        $name = fast_wordwrap($name, FORUM_FORCE_WRAP_MAIN, '`');
        $name = html_unicode($name);
        $name = str_replace('&#96;', FORUM_WRAP, $name);
    }
    else
    {
        $name = fast_wordwrap($name, FORUM_FORCE_WRAP_MAIN, FORUM_WRAP);
    }

    $notext = '';
    if ($post['notext']) $notext = FORUM_NT_MARKER.' ';

    if ($level == 1)
    {
        if ($is_reply)
            $class = 'forum_li1r';
        else
            $class = 'forum_li1';
        print('<li class="'.$class.'" style="margin-left:'.FORUM_MARGIN_ADJUST.'; text-indent:-'.FORUM_MARGIN_ADJUST.';"><a href="'.php_self().'?action=view&id='.$post['id'].'">'.$subject.'</a> '.$notext.' - <b>'.$name.'</b> ');
    }
    else
    {
        if ($is_reply)
            $class = 'forum_li2r';
        else
            $class = 'forum_li2';
        print('<li class="'.$class.'"><a href="'.php_self().'?action=view&id='.$post['id'].'">'.$subject.'</a> '.$notext.'- <b>'.$name.'</b> ');
    }

    if (!$is_reply)
    {
        print('<span class="forum_date">('.date(DATE_FORMAT, $post['time']+TIME_OFFSET).')</span>'.BR);
    }
    else
    {
        print('('.date(DATE_FORMAT, $post['time']+TIME_OFFSET).')'.BR);
    }

    # If there are any replies, display them by calling ourself recursively
    if ($post['replies'] > 0)
    {
        if ($is_reply)
            $class = ' class="forum_ulr"';
        else
            $class = ' class="forum_ul"';
        print('<ul'.$class.'>');
        $level++;
        for ($i=0; $i<$post['replies']; $i++)
        {
            $index++;
            $index = forum_show($forum_db, $index, $is_reply, $level);
        }
        print('</ul>');
    }
    print('</li>'.BR);

    # Return the updated db array index
    return $index;
}


#============================================================================#
# forum_view - Displays a message, the post thread, and a form for replies   #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#      $form_name - Name entered in reply form (only set on error!)          #
#   $form_subject - Subject entered in reply form (only set on error!)       #
#   $form_message - Message entered in reply form (only set on error!)       #
#             $id - id of message to display (only set on user reply error!) #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_view ($form_name = '', $form_subject = '', $form_message = '', $id = '')
{
    # If the id is already set, that means the user tried to reply but forgot something
    if ($id == '')
    {
        $id = get_var('id');
        $warn = false;
    }
    else
    {
        $warn = true;
    }

    # Load the forum db for this thread only
    $forum_db = forum_load_thread($id, $index, $root_index);
    if ($forum_db === false) # invalid message id
    {
        header('Location: '.php_self());
        exit;
    }
    $post = $forum_db[$index];

    # Get the contents of the message (if there is one)
    if ($post['notext'])
    {
        $message = '<i>No message</i>';
    }
    else
    {
        $message = @file_get_contents(FORUM_DATA_PATH.$id.'.txt');
        if (!$message) $message = '<i>No message</i>';
    }
    $message = safe_wordwrap($message, FORUM_FORCE_WRAP_POST, FORUM_WRAP);
    
    # Start the page
    page_start(PAGE_TITLE_VIEW);
    print(FORUM_TITLE);
    print('<div align="center"><a href="'.php_self().'">'.TEXT_BACKTOMSG.'</a><br /><br /></div>'.BR);

    # Set up the post subject so that we can autoformat it (to enable links in the subject)
    $subject = html_entity_decode($post['subject']);
    $subject = forum_autoformat($subject, true);
    $subject = safe_wordwrap($subject, FORUM_FORCE_WRAP_POST, FORUM_WRAP);

    # Display the post
    $name = $post['name'];
    if (strpos($name, '@') !== false)
    {
        $name = fast_wordwrap($name, FORUM_FORCE_WRAP_POST, '`');
        $name = html_unicode($name);
        $name = str_replace('&#96;', FORUM_WRAP, $name);
    }
    else
    {
        $name = fast_wordwrap($name, FORUM_FORCE_WRAP_POST, FORUM_WRAP);
    }
    
    print('<span class="forum_message"><span class="forum_brighter"><b>'.$name.'</b></span> '. date(DATE_FORMAT, $post['time']+TIME_OFFSET).'<br />');
    print($subject.'<br />'.FORUM_HR);
    print($message.'</span>');

    # Show the entire thread this message is in (recursively)
    print(FORUM_HR.'<ul class="forum_ulr"><div style="width:100%; overflow:hidden; margin-left:-'.FORUM_MARGIN_ADJUST.';">'.BR);
    forum_show($forum_db, $root_index, true, 1);
    print('</div></ul>'.BR);
    print(FORUM_HR.'<br />'.BR);
    print('<h2>'.TEXT_REPLYTOMSG.'</h2><br />'.BR);

    # Show the reply form
    forum_form($form_name, $form_subject, $form_message, $id, $warn, $post['subject']);
    
    # End the page
    page_end();
}


#============================================================================#
# forum_new - Displays the page for posting a new message starting a thread  #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#      $form_name - Name entered in reply form (only set on error!)          #
#   $form_subject - Subject entered in reply form (only set on error!)       #
#   $form_message - Message entered in reply form (only set on error!)       #
#           $warn - Set on user reply error                                  #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_new ($form_name = '', $form_subject = '', $form_message = '', $warn = '')
{
    # Start the page
    page_start(PAGE_TITLE_NEW);
    print(FORUM_TITLE);
    print('<div align="center"><a href="'.php_self().'">'.TEXT_BACKTOMSG.'</a></div><br />'.BR);
    print(FORUM_HR.'<br />'.BR);
    print('<h2>'.TEXT_POSTNEWMSG.'</h2><br />'.BR);

    # Show the new message form
    forum_form($form_name, $form_subject, $form_message, '', $warn);

    # End the page
    page_end();
}


#============================================================================#
# forum_form - Displays the actual form for replies/new posts                #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#        $form_name - Name entered in form (only set on error!)              #
#     $form_subject - Subject entered in form (only set on error!)           #
#     $form_message - Message entered in form (only set on error!)           #
#             $warn - Set on user reply error                                #
#   $parent_subject - Subject of parent, set for reply forms only            #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_form ($form_name, $form_subject, $form_message, $id = '', $warn = '', $parent_subject = '')
{
    # Warn user if name or subject are missing
    if ($warn != '')
    {
        print('<i>'.TEXT_MISSINGNS.'</i><br /><br />'.BR);
    }

    # Make trap field at random location
    $trap = array('', '', '', '');
    if (TRAP_ENABLED) $trap[mt_rand(0, 3)] = '<input type="text" size="'.FORUM_SUBJECT_FIELDLEN.'" maxlength="'.FORUM_SUBJECT_MAXLEN.'" name="'.encode_field('trap').'" class="'.TRAP_CLASS.'">';

    # Add auto-subject if needed
    if (($form_subject == '') && (trim(AUTO_SUBJECT) != '') && ($parent_subject != '')) $form_subject = AUTO_SUBJECT.$parent_subject;

    # Generate string telling what tags are allowed
    $allowed = '';
    if (TAG_CODE_ENABLED) $allowed .= ' &lt;code&gt;';
    if (TAG_B_ENABLED) $allowed .= ' &lt;b&gt;';
    if (TAG_I_ENABLED) $allowed .= ' &lt;i&gt;';
    if (TAG_U_ENABLED) $allowed .= ' &lt;u&gt;';
    if ($allowed != '') $allowed = ', '.TEXT_EXCEPTFOR.$allowed.' '.TEXT_INMSGONLY;

    # Display form
    print('<table><form action="'.php_self().'?action=post" method="POST"><tr><td align="right">'.html_unicode(TEXT_NAME).'</td><td>'.$trap[0].'<input type="text" size="'.FORUM_NAME_FIELDLEN.'" maxlength="'.FORUM_NAME_MAXLEN.'" name="'.encode_field('name').'" value="'.$form_name.'" class="'.NAME_CLASS.'">'.$trap[1].'</td></tr>'.BR);
    print('<tr><td align="right">'.html_unicode(TEXT_SUBJECT).'</td><td>'.$trap[2].'<input type="text" size="'.FORUM_SUBJECT_FIELDLEN.'" maxlength="'.FORUM_SUBJECT_MAXLEN.'" name="'.encode_field('subject').'" value="'.$form_subject.'" class="'.SUBJECT_CLASS.'">'.$trap[3].'</td></tr>'.BR);
    print('<tr><td height="10" colspan="2"></td></tr>'.BR);
    print('<tr><td valign="top" align="right">'.html_unicode(TEXT_MESSAGE).'</td><td><textarea name="'.encode_field('message').'" rows="'.FORUM_MSG_FIELDHEIGHT.'" cols="'.FORUM_MSG_FIELDWIDTH.'" wrap="soft">'.$form_message.'</textarea></td></tr>'.BR);
    print('<tr><td height="10" colspan="2"></td></tr>'.BR);
    print('<tr><td></td><td><input type="submit" value=" '.TEXT_POSTMSG.' "></td></tr>'.BR);
    if ($id) print ('<input type="hidden" name="reply" value="'.$id.'">'.BR);
    print('<tr><td height="10" colspan="2"></td></tr>'.BR);
    print('<tr><td colspan="2"><p class="forum_small">'.TEXT_NOHTML.$allowed.'.<br />'.BR);
    print(TEXT_LINKS.'</p></td></tr>'.BR);
    print('</form></table>'.BR);
}


#============================================================================#
# forum_post - Adds a new post to the forum                                  #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_post ()
{
    # Get the id of message we're replying to (will be null if starting a new thread)
    $replyto = post_var('reply');

    # Decode post vars to get field values from form
    $name = '';
    $subject = '';
    $message = '';
    $trap = '';
    foreach ($_POST as $index => $value)
    {
        if (strlen($index) == 16)
        {
            list($field, $time) = decode_field($index);
            eval("\$$field = array_stripslashes(\$value);");
            
            # Reject as spam if the timestamp from posting form is not within the threshold
            if (TIMER_ENABLED && (($time < TIMER_MIN) || ($time > TIMER_MAX))) reject_spam();
        }
    }

    # Reject as spam if the trap field was filled in, or if any fields are beyond the max length
    if ( (strlen($trap) > 0) || (strlen($name) > FORUM_NAME_MAXLEN) ||
         (strlen($subject) > FORUM_SUBJECT_MAXLEN) || (strlen($message) > FORUM_MSG_MAXLEN) ) reject_spam();
         
    # Reject as spam if post does not pass SLV test
    if (SLV_ENABLED && slv_is_spam(SLV_URL, $name, $subject, $message)) reject_spam();

    # Load the filters/triggers
    list($filter_db, $trigger_db) = forum_load_filters();
    
    # Check for spam triggers if enabled
    if (TRIGGERS_ENABLED)
    {
        $all = $name.' '.$subject.' '.$message;
        foreach ($trigger_db as $trigger)
        {
            if (stripos($all, $trigger) !== false) reject_spam();
        }
    }

    # Format the name and subject
    $name = htmlspecialchars($name);
    $subject = htmlspecialchars($subject);

    # Did the user forget their name or the subject?
    if ( ($name == '') || ($subject == '') )
    {
        # Yes; make them go back and fix it
        $message = htmlspecialchars($message);
        if ($replyto == '')
        {
            forum_new($name, $subject, $message, 1);
        }
        else
        {
            forum_view($name, $subject, $message, $replyto);
        }

    }
    else
    {
        # No; we're good to go
        
        # Filter words if the filters are enabled
        if (FILTERS_ENABLED)
        {
            $from = array();
            $to = array();
            foreach ($filter_db as $filter)
            {
                $from[] = '/(\b'.$filter['from'].'\b)/i';
                $to[] = $filter['to'];
            }

            $name = preg_replace($from, $to, trim($name));
            $subject = preg_replace($from, $to, trim($subject));
            $message = forum_autoformat(preg_replace($from, $to, trim($message)));
        }
        else
        {
            $name = trim($name);
            $subject = trim($subject);
            $message = forum_autoformat(trim($message));
        }
        
        # Get the post time and user's IP address
        $time = time();
        $ip = get_ip();

        # Set the no-text marker if the post message is blank
        if ($message == '')
            $notext = '1';
        else
            $notext = '0';

        # Open the forum db and lock it so it can't be changed while we post
        $forum_db = forum_load_all();
        $fp = fopen(FORUM_DATA_INDEX, 'w');
        flock($fp, LOCK_EX);

        # Update the forum variables
        $fp2 = fopen(FORUM_DATA_VARS, 'r+');
        flock($fp2, LOCK_EX);
        $id = trim(fgets($fp2))+1;
        $num_msgs = trim(fgets($fp2));
        $num_threads = trim(fgets($fp2));
        fseek($fp2, 0);
        fwrite($fp2, $id.BR);
        fwrite($fp2, ($num_msgs+1).BR);
        if ($replyto == '') $num_threads++;
        fwrite($fp2, $num_threads.BR);
        flock($fp2, LOCK_UN);
        fclose($fp2);

        # Write the message .txt (if there is a message)
        if ($message != '')
        {
            $fp2 = fopen(FORUM_DATA_PATH.$id.'.txt', 'w');
            flock($fp2, LOCK_EX);
            fwrite($fp2, $message);
            flock($fp2, LOCK_UN);
            fclose($fp2);
        }

        # Now update the forum db
        if ($replyto == '')
        {
            # New post, stick it at the top and be done with it
            fwrite($fp, $id.'`'.$id.'`'.$id.'`'.$name.'`'.$subject.'`'.$notext.'`'.$time.'`'.$ip.'`0'.BR);
            foreach ($forum_db as $post)
            {
                fwrite($fp, $post['id'].'`'.$post['root'].'`'.$post['parent'].'`'.$post['name'].'`'.$post['subject'].'`'.$post['notext'].'`'.$post['time'].'`'.$post['ip'].'`'.$post['replies'].BR);
            }
        }
        else
        {
            # Find the post we are replying to
            foreach ($forum_db as $post)
            {
                if ($post['id'] == $replyto)
                {
                    # Insert new post underneath
                    fwrite($fp, $post['id'].'`'.$post['root'].'`'.$post['parent'].'`'.$post['name'].'`'.$post['subject'].'`'.$post['notext'].'`'.$post['time'].'`'.$post['ip'].'`'.($post['replies']+1).BR);
                    fwrite($fp, $id.'`'.$post['root'].'`'.$post['id'].'`'.$name.'`'.$subject.'`'.$notext.'`'.$time.'`'.$ip.'`0'.BR);
                }
                else
                {
                    fwrite($fp, $post['id'].'`'.$post['root'].'`'.$post['parent'].'`'.$post['name'].'`'.$post['subject'].'`'.$post['notext'].'`'.$post['time'].'`'.$post['ip'].'`'.$post['replies'].BR);
                }
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        # Post has been added, now redirect to the main page of the forum
        header('Location: '.php_self());
    }
}


#============================================================================#
# forum_admin - Checks to make sure the user is logged in, and then calls    #
#               the other admin functions                                    #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_admin ()
{
    # Get the admin password and start the session
    require(FORUM_DATA_PATH.'password.php');

    if (isset($_GET['sid'])) session_id($_GET['sid']);
    session_start();
    header('Cache-control: private');
  
    # If we couldn't set the session with a cookie, pass the session id
    if (SID != '')
        $sid = '&sid='.session_id();
    else
        $sid = '';

    # If the user hasn't POSTed a password, get it from the session
    if (!post_var('pass'))
    {
        $pass = session_var('pass');
    }
    else
    {
        $pass = md5(post_var('pass'));
        $_SESSION['pass'] = $pass;
    }

    # Don't let 'em in if the passwords don't match!
    if ($pass != PASSWORD)
    {
        unset($_SESSION['pass']);
        page_start(PAGE_TITLE_ADMIN);
        print(FORUM_TITLE_ADMIN);

        # Prompt for the password
        print('<div align="center"><h2>'.TEXT_LOGIN.'</h2><br />'.BR);
        if ($pass != '') print('<i>'.TEXT_PASSERR.'</i><br /><br />'.BR);
        print('<table border="0"><form action="'.php_self().'?action=admin'.$sid.'" method="POST">'.BR);
        print('<tr><td>'.TEXT_ENTERPASS.' </td><td><input type="password" name="pass" size="24" maxlength="20"></td></tr>'.BR);
        print('<tr><td colspan="2" height="6"></td></tr>'.BR);
        print('<tr><td colspan="2" align="right"><input type="submit" value="  '.TEXT_LOGIN.'  "></td></tr></form></table></div>'.BR);
        page_end();
    }
    else
    {
        # Password matches, successfully logged in
  
        $cmd = get_var('cmd');
        if ($cmd == 'logout')
        {
            forum_admin_logout();
        }
        elseif ($cmd == 'filters')
        {
            forum_admin_filters($sid);
        }
        elseif ($cmd == 'chpass')
        {
            forum_admin_chpass($sid);
        }
        elseif ($cmd == 'resync')
        {
            forum_admin_resync($sid);
        }
        else
        {
            # If the user doesn't want to edit/remove any messages, then display the main admin forum
            if ((forum_admin_edit($sid) == false) && (forum_admin_remove($sid) == false)) forum_admin_main($sid);
        }
    }
}


#============================================================================#
# forum_admin_main - Displays the main forum admin pages                     #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $sid - session id                                                        #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_admin_main ($sid)
{
    # Start the page
    page_start(PAGE_TITLE_ADMIN);
    print(FORUM_TITLE_ADMIN);

    # Get the id of the start post and load the forum db for this page only
    $start = get_var('start');
    if (($start=='') || ($start<0)) $start = 0;
    $forum_db = forum_load_page($start);

    # Get the forum stats to display at the bottom of the page
    $fp = fopen(FORUM_DATA_VARS, 'r');
    fgets($fp);
    $num_msgs = trim(fgets($fp));
    $num_threads = trim(fgets($fp));
    fclose($fp);

    # Display the admin links and help
    print('<div align="center"><table cellpadding="0" cellspacing="0"><form action="'.php_self().'?action=admin'.$sid.'" method="POST">'.BR);
    print('<tr><td><div align="center">'.BR);
    print('<table border="0" width="75%"><tr><td align="center"><a href="'.php_self().'?action=admin'.$sid.'&cmd=filters">'.TEXT_EDITFILTERS.'</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="'.php_self().'?action=admin'.$sid.'&cmd=chpass">'.TEXT_CHPASS.'</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="'.php_self().'?action=admin'.$sid.'&cmd=resync">'.TEXT_RESYNCDB.'</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="'.php_self().'?action=admin'.$sid.'&cmd=logout">'.TEXT_LOGOUT.'</a></td></tr>'.BR);
    print('<tr><td height="6"></td></tr>');
    print('<td>'.TEXT_ADMINHELP.'</td></tr></table>'.BR);
    print('</div></td></tr></table>'.BR);
    
    print('<br /><input type="submit" value="'.TEXT_REMOVESELECT.'"></div><br />');

    # Display all the threads on this page
    # (very easy using recursion...)
    print('<ul class="forum_ul">'.BR);
    for ($index=$start; ($index<$num_msgs) && (($index-$start)<FORUM_MSG_MAX); $index++)
    {
        print(FORUM_HR);
        print('<div style="width:100%; overflow:hidden; margin-left:-'.FORUM_MARGIN_ADJUST.';">'.BR);
        $index = forum_admin_show($forum_db, $index, $sid, 1);
        print('</div>'.BR);
        
    }
    print(FORUM_HR.'</ul><br />'.BR);
    print('<div align="center">');

    # If we're not on the last page, show a link to the next page
    if ($index < $num_msgs)
    {
        print('<a href="'.php_self().'?action=admin'.$sid.'&start='.$index.'">'.TEXT_NEXTPAGE.'</a><br /><br />');
    }

    # Finish off the page
    print('<input type="submit" value="'.TEXT_REMOVESELECT.'"></div><table cellpadding="0" cellspacing="0"></form><tr><td></td></tr></table><br />'.BR);

    print('<table width="100%" border="0" cellpadding="0" cellspacing="0">'.BR);
    print('<tr><td class="forum_small">'.$num_msgs.' '.TEXT_MESSAGESIN.' '.$num_threads.' '.TEXT_THREADSDISP.' '.FORUM_MSG_MAX.' '.TEXT_MSGPERPAGE.'</td>'.BR);
    print('<td align="right" class="forum_small">'.TEXT_POWEREDBY.' <a href="http://www.phatcode.net/" target="_blank">FlatForum '.FORUM_VERSION.'</a></td></tr></table>'.BR);
    page_end();
}


#============================================================================#
# forum_admin_show - Recursive function used to display message threads in   #
#                    the admin section                                       #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $forum_db - forum database                                               #
#      $index - forum db array index of post to display                      #
#        $sid - session id                                                   #
#      $level - Thread level (used to adjust spacing)                        #
# Returns:                                                                   #
#   (function) - Updated index after all child posts have been displayed     #
#============================================================================#
function forum_admin_show ($forum_db, $index, $sid, $level=1)
{
    # Display our post
    $post = $forum_db[$index];

    $subject = $post['subject'];
    if (strpos($subject, '@') !== false)
    {
        $subject = fast_wordwrap($subject, FORUM_FORCE_WRAP_MAIN, '`');
        $subject = html_unicode($subject);
        $subject = str_replace('&#96;', FORUM_WRAP, $subject);
    }
    else
    {
        $subject = fast_wordwrap($subject, FORUM_FORCE_WRAP_MAIN, FORUM_WRAP);
    }

    $name = $post['name'];
    if (strpos($name, '@') !== false)
    {
        $name = fast_wordwrap($name, FORUM_FORCE_WRAP_MAIN, '`');
        $name = html_unicode($name);
        $name = str_replace('&#96;', FORUM_WRAP, $name);
    }
    else
    {
        $name = fast_wordwrap($name, FORUM_FORCE_WRAP_MAIN, FORUM_WRAP);
    }

    $notext = '';
    if ($post['notext']) $notext = FORUM_NT_MARKER.' ';

    if ($level == 1)
        print('<li class="forum_li1" style="margin-left:'.FORUM_MARGIN_ADJUST.'; text-indent:-'.FORUM_MARGIN_ADJUST.';">');
    else
        print('<li class="forum_li2">');
    print('<input type="checkbox" class="forum_default" name="remove[]" value="'.$post['id'].'"> <a href="'.php_self().'?action=admin'.$sid.'&edit='.$post['id'].'">'.$subject.'</a> '.$notext.' - <b>'.$name.'</b> <a href="'.php_self().'?action=admin'.$sid.'&removeip='.$post['ip'].'">'.$post['ip'].'</a> <span class="forum_date">('.date(DATE_FORMAT, $post['time']+TIME_OFFSET).')</span>');

    # If there are any replies, display them by calling ourself recursively
    if ($post['replies'] > 0)
    {
        print('<ul class="forum_ul">');
        $level++;
        for ($i=0; $i<$post['replies']; $i++)
        {
            $index++;
            $index = forum_admin_show($forum_db, $index, $sid, $level);
        }
        print('</ul>'.BR);
    }
    print('</li>'.BR);

    # Return the updated db array index
    return $index;
}


#============================================================================#
# forum_admin_filters - Edits the filters/spam triggers                      #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $sid - session id                                                        #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_admin_filters ($sid)
{
    list($filter_db, $trigger_db) = forum_load_filters();
        
    $from = post_var('from');
    $to = post_var('to');
    $trigger = post_var('trigger');
        
    if (!$from && !$to && !$trigger)
    {
        # Display filter/trigger forms
        
        page_start(PAGE_TITLE_ADMIN);
        print(FORUM_TITLE_ADMIN);
        print('<div align="center"><h2>'.TEXT_WORDFILTERS.'</h2><br />'.BR);

        print(TEXT_FILTERHELP.'<br /><br />'.BR);
        print('<table><form action="'.php_self().'?action=admin'.$sid.'&cmd=filters" method="POST"><tr><td width="50%" align="center"><b>'.TEXT_FROM.'</b></td><td width="50%" align="center"><b>'.TEXT_TO.'</b></td></tr>'.BR);
        foreach ($filter_db as $filter)
        {
            print('<tr><td><input type="text" size="20" name="from[]" value="'.$filter['from'].'"></td><td><input type="text" size="20" name="to[]" value="'.$filter['to'].'"></td></tr>'.BR);
        }
        for ($i=0; $i<5; $i++)
        {
            print('<tr><td><input type="text" size="20" name="from[]"></td><td><input type="text" size="20" name="to[]"></td></tr>'.BR);
        }
        print('<tr><td height="10" colspan="2"></td></tr>'.BR);
        print('<tr><td colspan="2" align="center"><input type="submit" value="'.TEXT_SAVECHANGES.'"></td></tr></form></table>'.BR);

        print('<br /><br /><h2>'.TEXT_SPAMTRIGGERS.'</h2><br />'.BR);
        
        print(TEXT_TRIGGERHELP.'<br /><br />'.BR);
        print('<table><form action="'.php_self().'?action=admin'.$sid.'&cmd=filters" method="POST">');
        foreach ($trigger_db as $trigger)
        {
            print('<tr><td><input type="text" size="40" name="trigger[]" value="'.$trigger.'"></td></tr>');
        }
        for ($i=0; $i<5; $i++)
        {
            print('<tr><td><input type="text" size="40" name="trigger[]"></td></tr>'.BR);
        }
        print('<tr><td height="10"></td></tr>'.BR);
        print('<tr><td align="center"><input type="submit" value="'.TEXT_SAVECHANGES.'"></td></tr></form></table>'.BR);

        print('<br /><a href="'.php_self().'?action=admin'.$sid.'">'.TEXT_GOBACK.'</a></div>'.BR);

        page_end();
    }
    elseif ($trigger)
    {
        # Update triggers
        
        page_start(PAGE_TITLE_ADMIN);
        print(FORUM_TITLE_ADMIN);
        print('<div align="center"><h2>'.TEXT_SPAMTRIGGERS.'</h2><br />'.BR);
        
        foreach ($trigger as $index => $this_trigger)
        {
            if (trim($trigger[$index]) == '') unset($trigger[$index]);
        }
        forum_save_filters($filter_db, $trigger);

        print(TEXT_TRIGGERSUPD.'<br />'.BR);
        print('<br /><a href="'.php_self().'?action=admin'.$sid.'&cmd=filters">'.TEXT_GOBACK.'</a></div>'.BR);
        page_end();
    }
    else
    {
        # Update filters
        
        page_start(PAGE_TITLE_ADMIN);
        print(FORUM_TITLE_ADMIN);
        print('<div align="center"><h2>'.TEXT_WORDFILTERS.'</h2><br />'.BR);
        
        $filter = array();
        for ($i=0; $i<count($from); $i++)
        {
            if (trim($from[$i]) != '')
            {
                $filter[$i]['from'] = trim($from[$i]);
                $filter[$i]['to'] = trim($to[$i]);
                if ($filter[$i]['to'] == '~') $filter[$i]['to'] = '-';
            }
        }
        forum_save_filters($filter, $trigger_db);
        
        print(TEXT_FILTERSUPD.'<br />'.BR);
        print('<br /><a href="'.php_self().'?action=admin'.$sid.'&cmd=filters">'.TEXT_GOBACK.'</a></div>'.BR);
        page_end();
    }
}


#============================================================================#
# forum_admin_edit - Edits a message                                         #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $sid - session id                                                        #
# Returns:                                                                   #
#   true - message was edited                                                #
#  false - no message was edited                                             #
#============================================================================#
function forum_admin_edit ($sid)
{
    # Get the post to edit
    $id = get_var('edit');
    if (!$id) return false;
    
    $name = trim(post_var('name'));
    $subject = trim(post_var('subject'));
    $message = trim(post_var('message'));

    if (!$name || !$subject)
    {
        $warn = false;
        
        # Warn if missing the name or subject
        if ($name || $subject || $message)
        {
            $warn = true;
        
            $form_name = htmlspecialchars($name);
            $form_subject = htmlspecialchars($subject);
            $form_message = htmlspecialchars($message);
        }
        else
        {
            # Load the forum db for this thread only
            $forum_db = forum_load_thread($id, $index, $root_index);
            if ($forum_db === false) # invalid message id
            {
                header('Location: '.php_self().'?action=admin'.$sid);
                exit;
            }
            $post = $forum_db[$index];

            # Get the contents of the message (if there is one)
            if ($post['notext'])
            {
                $message = '';
            }
            else
            {
                $message = @file_get_contents(FORUM_DATA_PATH.$id.'.txt');
                if (!$message) $message = '';
            }

            $form_name = htmlspecialchars(html_entity_decode($post['name']));
            $form_subject = htmlspecialchars(html_entity_decode($post['subject']));
            $form_message = htmlspecialchars(trim(forum_unautoformat($message)));
        }

        # Show the form to edit the message

        page_start(PAGE_TITLE_ADMIN);
        print(FORUM_TITLE_ADMIN);
        print('<div align="center"><h2>'.TEXT_EDITMSG.'</h2><br />');

        if ($warn) print('<i>'.TEXT_MISSINGNSA.'</i><br /><br />'.BR);
                    
        print('<table><form action="'.php_self().'?action=admin'.$sid.'&edit='.$id.'" method="POST"><tr><td align="right">'.TEXT_NAME.'</td><td><input type="text" size="'.FORUM_NAME_FIELDLEN.'" name="name" value="'.$form_name.'"></td></tr>'.BR);
        print('<tr><td align="right">'.TEXT_SUBJECT.'</td><td><input type="text" size="'.FORUM_SUBJECT_FIELDLEN.'" name="subject" value="'.$form_subject.'"></td></tr>'.BR);
        print('<tr><td height="10" colspan="2"></td></tr>'.BR);
        print('<tr><td valign="top" align="right">'.TEXT_MESSAGE.'</td><td><textarea name="message" rows="'.FORUM_MSG_FIELDHEIGHT.'" cols="'.FORUM_MSG_FIELDWIDTH.'" wrap="soft">'.$form_message.'</textarea></td></tr>'.BR);
        print('<tr><td height="10" colspan="2"></td></tr>'.BR);
        print('<tr><td></td><td><input type="submit" value="'.TEXT_SAVECHANGES.'"></td></tr>'.BR);
        print('</form></table><br /><a href="'.php_self().'?action=admin'.$sid.'">'.TEXT_GOBACK.'</a></div>'.BR);
        page_end();
    }
    else
    {
        # Save the edited message
        
        $name = htmlspecialchars($name);
        $subject = htmlspecialchars($subject);
        $message = forum_autoformat($message);
        
        page_start(PAGE_TITLE_ADMIN);
        print(FORUM_TITLE_ADMIN);

        # Set the no-text marker if the post message is blank
        if ($message == '')
            $notext = '1';
        else
            $notext = '0';

        # Write the message .txt (if there is a message)
        if ($message != '')
        {
            $fp2 = fopen(FORUM_DATA_PATH . $id . '.txt', 'w');
            flock($fp2, LOCK_EX);
            fwrite($fp2, $message);
            flock($fp2, LOCK_UN);
            fclose($fp2);
        }
        else
        {
            @unlink(FORUM_DATA_PATH.$id.'.txt');
        }

        $forum_db = forum_load_all();
        foreach($forum_db as $index => $post)
        {
            if ($post['id'] == $id)
            {
                $forum_db[$index]['name'] = $name;
                $forum_db[$index]['subject'] = $subject;
                $forum_db[$index]['notext'] = $notext;
                break;
            }
        }
        forum_save_all($forum_db);

        print('<div align="center"><h2>'.TEXT_EDITMSG.'</h2><br />'.BR);
        print(TEXT_CHSAVED.'<br /><br />'.BR);
        print('<a href="'.php_self().'?action=admin'.$sid.'">'.TEXT_GOBACK.'</a></div>'.BR);
        page_end();
    }
    
    return true;
}


#============================================================================#
# forum_admin_remove - Removes either all messages from a specified IP or    #
#                      selected messages                                     #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $sid - session id                                                        #
# Returns:                                                                   #
#   true - messages were removed                                             #
#  false - no messages were removed                                          #
#============================================================================#
function forum_admin_remove ($sid)
{
    # If the user denied confirmation, then unset the remove session variables
    $confirm = get_var('confirm');
    if ($confirm == 'no')
    {
        unset($_SESSION['remove']);
        unset($_SESSION['removeip']);
    }

    # Get the posts to remove from either the POST data or the session
    $remove = post_var('remove');
    if (!$remove)
    {
        $remove = session_var('remove');
    }
    else
    {
        $_SESSION['remove'] = $remove;
    }

    $removeip = get_var('removeip');
    if (!$removeip)
    {
        $removeip = session_var('removeip');
    }
    else
    {
        $_SESSION['removeip'] = $removeip;
    }

    # Is there even anything to remove?
    if ($removeip)
    {
        # Yes, user wants to remove all messages from a specific IP
        page_start(PAGE_TITLE_ADMIN);
        print(FORUM_TITLE_ADMIN);

        if (!$confirm)
        {
            # Need confirmation
            print('<div align="center"><h2>'.TEXT_REMOVEMSG.'</h2><br />'.BR);
            print(TEXT_REMOVEFROM.' <b>'.$removeip.'</b>?<br /><br />'.BR);
            print('<a href="'.php_self().'?action=admin'.$sid.'&confirm=yes">'.TEXT_YES.'</a> &nbsp; &nbsp; ');
            print('<a href="'.php_self().'?action=admin'.$sid.'&confirm=no">'.TEXT_NO.'</a></div>');
        }
        else
        {
            # Confirmed, so remove them
            $forum_db = forum_load_all();

            # Mark each message to remove
            foreach($forum_db as $count => $post)
            {
                if ($post['ip'] == $removeip)
                {
                    $index = $count;
                    forum_admin_remove_post($forum_db, $index);
                }
            }

            # Delete each marked message, recording the number of posts and threads
            # deleted so that we can update the forum variables
            $removed_msgs = 0;
            $removed_threads = 0;
            foreach($forum_db as $count => $post)
            {
                if ($post['ip'] == 'remove')
                {
                    foreach($forum_db as $parent_index => $parent_post)
                    {
                        if ($post['parent'] == $parent_post['id'])
                        {
                            $forum_db[$parent_index]['replies']--;
                            break;
                        }
                    }

                    $removed_msgs++;
                    if ($post['id'] == $post['root']) $removed_threads++;
                    if (!$post['notext']) @unlink(FORUM_DATA_PATH . $post['id'] . '.txt');
                    unset($forum_db[$count]);
                }
            }

            # Save the modified forum db
            forum_save_all($forum_db);

            # Save the updated variables
            $fp = fopen(FORUM_DATA_VARS, 'r+');
            flock($fp, LOCK_EX);
            $id = trim(fgets($fp));
            $num_msgs = trim(fgets($fp))-$removed_msgs;
            $num_threads = trim(fgets($fp))-$removed_threads;
            fseek($fp, 0);
            fwrite($fp, $id.BR);
            fwrite($fp, $num_msgs.BR);
            fwrite($fp, $num_threads.BR);
            flock($fp, LOCK_UN);
            fclose($fp);

            # Removal complete, unset session variables and display success message
            unset($_SESSION['removeip']);
            print('<div align="center"><h2>'.TEXT_REMOVEMSG.'</h2><br />'.BR);
            print(TEXT_ALLMSGSFROM.' <b>'.$removeip.'</b> '.TEXT_HAVEBEENREM.'<br /><br />'.BR);
            print('<a href="'.php_self().'?action=admin'.$sid.'">'.TEXT_GOBACK.'</a></div>'.BR);
        }
        page_end();

    }
    else if ($remove)
    {
        # User wants to remove selected messages
        page_start(PAGE_TITLE_ADMIN);
        print(FORUM_TITLE_ADMIN);

        if (!$confirm)
        {
            # Need confirmation
            print('<div align="center"><h2>'.TEXT_REMOVEMSG.'</h2><br />'.BR);
            print(TEXT_REMOVESURE.'<br /><br />'.BR);

            # Show each selected message
            print('<table border="0">'.BR);
            $forum_db = forum_load_all();
            foreach($remove as $this_post)
            {
                foreach($forum_db as $count => $post)
                {
                    if ($post['id'] == $this_post)
                    {
                        $notext = '';
                        if ($post['notext']) $notext = FORUM_NT_MARKER.' ';
                        
                        print('<tr><td align="right">'.$post['subject'].' '.$notext.'</td><td>- <b>'.$post['name'].'</b> <span class="forum_date">('.date(DATE_FORMAT, $post['time']+TIME_OFFSET).')</span></td></tr>'.BR);
                        break;
                    }
                }
            }
            print('</table>'.BR);

            print('<br /><a href="'.php_self().'?action=admin'.$sid.'&confirm=yes">'.TEXT_YES.'</a> &nbsp; &nbsp; ');
            print('<a href="'.php_self().'?action=admin'.$sid.'&confirm=no">'.TEXT_NO.'</a>');
        }
        else
        {
            # Confirmed, so remove them
            $forum_db = forum_load_all();

            # Mark each message to remove
            foreach($remove as $this_post)
            {
                foreach($forum_db as $count => $post)
                {
                    if ($post['id'] == $this_post)
                    {
                        $index = $count;
                        forum_admin_remove_post($forum_db, $index);
                        break;
                    }
                }
            }

            # Delete each marked message, recording the number of posts and threads
            # deleted so that we can update the forum variables
            $removed_msgs = 0;
            $removed_threads = 0;
            foreach($forum_db as $count => $post)
            {
                if ($post['ip'] == 'remove')
                {
                    foreach($forum_db as $parent_index => $parent_post)
                    {
                        if ($post['parent'] == $parent_post['id'])
                        {
                            $forum_db[$parent_index]['replies']--;
                            break;
                        }
                    }

                    $removed_msgs++;
                    if ($post['id'] == $post['root']) $removed_threads++;
                    if (!$post['notext']) @unlink(FORUM_DATA_PATH . $post['id'] . '.txt');
                    unset($forum_db[$count]);
                }
            }

            # Save the modified forum db
            forum_save_all($forum_db);

            # Save the updated variables
            $fp = fopen(FORUM_DATA_VARS, 'r+');
            flock($fp, LOCK_EX);
            $id = trim(fgets($fp));
            $num_msgs = trim(fgets($fp))-$removed_msgs;
            $num_threads = trim(fgets($fp))-$removed_threads;
            fseek($fp, 0);
            fwrite($fp, $id.BR);
            fwrite($fp, $num_msgs.BR);
            fwrite($fp, $num_threads.BR);
            flock($fp, LOCK_UN);
            fclose($fp);

            # Removal complete, unset session variables and display success message
            unset($_SESSION['remove']);
            print('<div align="center"><h2>'.TEXT_REMOVEMSG.'</h2><br />'.BR);
            print(TEXT_REMOVED.'<br /><br />'.BR);
            print('<a href="'.php_self().'?action=admin'.$sid.'">'.TEXT_GOBACK.'</a></div>'.BR);
        }
        page_end();

    }
    else
    {
        # Nothing to remove!
        return false;
    }
  
    return true;
}


#============================================================================#
# forum_admin_remove_post - Recursively tags the specified message and all   #
#                           its child messages for removal                   #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $forum_db - forum db array                                               #
#      $index - array index of parent message to remove                      #
# Returns:                                                                   #
#   (function) - Current index (updated from recursive calls)                #
#============================================================================#
function forum_admin_remove_post (&$forum_db, $index)
{
    # Tag this message for removal
    $forum_db[$index]['ip'] = 'remove';

    # Recursively tag all child messages for removal
    $post = $forum_db[$index];
    for ($i=0; $i<$post['replies']; $i++)
    {
        $index++;
        $index = forum_admin_remove_post($forum_db, $index);
    }
  
    # Return updated index
    return $index;
}


#============================================================================#
# forum_admin_chpass - Changes the admin password                            #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $sid - session id                                                        #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_admin_chpass ($sid)
{
    # Get the passwords that the user POSTed
    if (isset($_POST['oldpass'])) $oldpass = $_POST['oldpass'];
    $newpass1 = post_var('newpass1');
    $newpass2 = post_var('newpass2');

    # Start the page
    page_start(PAGE_TITLE_ADMIN);
    print(FORUM_TITLE_ADMIN);
    print('<div align="center"><h2>'.TEXT_CHPASS.'</h2><br />');

    # If $oldpass is set then the user is submitting passwords
    if (isset($oldpass))
    {
        if (($oldpass == '') || ($newpass1 == '') || ($newpass2 == ''))
        {
            # At least one password is missing
            print('<i>'.TEXT_MISSINGPASS.'</i><br /><br />'.BR);
        }
        elseif (md5($oldpass) != PASSWORD)
        {
            # Old password is incorrect
            print('<i>'.TEXT_OLDPASSERR.'</i><br /><br />'.BR);
        }
        elseif ($newpass1 != $newpass2)
        {
            # New passwords don't match
            print('<i>'.TEXT_NEWPASSERR.'</i><br /><br />'.BR);
        }
        else
        {
            # Everything's ok, so update the password file and change the session password
            $fp = fopen(FORUM_DATA_PATH.'password.php','w');
            flock($fp, LOCK_EX);
            fwrite($fp, "<?php define('PASSWORD', '".md5($newpass1)."'); ?>".BR);
            flock($fp, LOCK_UN);
            fclose($fp);
            $_SESSION['pass'] = md5($newpass1);
            print('<i>'.TEXT_PASSCH.'</i><br /><br />'.BR);
            print('<a href="'.php_self().'?action=admin'.$sid.'">'.TEXT_GOBACK.'</a></div>'.BR);

            # Bail
            page_end();
            return;
        }
    }
  
    # Display the change password form
    print('<table border="0"><form action="'.php_self().'?action=admin'.$sid.'&cmd=chpass" method="POST">'.BR);
    print('<tr><td align="right">'.TEXT_OLDPASS.'</td><td><input type="password" name="oldpass" size="24" maxlength="20"></td></tr>'.BR);
    print('<tr><td align="right">'.TEXT_NEWPASS.'</td><td><input type="password" name="newpass1" size="24" maxlength="20"></td></tr>'.BR);
    print('<tr><td align="right">'.TEXT_RNEWPASS.'</td><td><input type="password" name="newpass2" size="24" maxlength="20"></td></tr>'.BR);
    print('<tr><td colspan="2" height="6"></td></tr>'.BR);
    print('<tr><td colspan="2" align="right"><input type="submit" value="  '.TEXT_CHANGE.'  "></td></tr></form></table>');
    print('<a href="'.php_self().'?action=admin'.$sid.'">'.TEXT_GOBACK.'</a></div>'.BR);

    # End the page
    page_end();
}


#============================================================================#
# forum_admin_resync - Resyncs the database with the forum variables, in     #
#                      case something gets messed up somehow                 #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $sid - session id                                                        #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_admin_resync ($sid)
{
    # Load the entire db
    $forum_db = forum_load_all();

    # We're going to recheck the variables
    $last_id = 99;
    $num_msgs = 0;
    $num_threads = 0;

    # Check each post and make sure it's really there
    foreach ($forum_db as $index => $post)
    {
        if ($post['subject'] == '')   # Weird stuff, dump it
        {
            unset($forum_db[$index]);
        }
        else                             # It's legit
        {
            # Check the id
            if ($post['id'] > $last_id) $last_id = $post['id'];

            # Increase the post and thread count
            $num_msgs++;
            if ($post['id'] == $post['root']) $num_threads++;
        }
    }

    # Save the entire db
    forum_save_all($forum_db);
  
    # Save the variables
    $fp = fopen(FORUM_DATA_VARS, 'w');
    flock($fp, LOCK_EX);
    fwrite($fp, $last_id.BR);
    fwrite($fp, $num_msgs.BR);
    fwrite($fp, $num_threads.BR);
    flock($fp, LOCK_UN);
    fclose($fp);

    # Success
    page_start(PAGE_TITLE_ADMIN);
    print(FORUM_TITLE_ADMIN);

    print('<div align="center"><h2>'.TEXT_RESYNCDB.'</h2><br />'.BR);
    print(TEXT_RESYNCDBOK.'<br /><br />'.BR);

    print('<a href="'.php_self().'?action=admin'.$sid.'">'.TEXT_GOBACK.'</a></div>'.BR);
    page_end();
}


#============================================================================#
# forum_admin_logout - Logs the user out of the admin section                #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (none)                                                                   #
#============================================================================#
function forum_admin_logout ()
{
    # Log out
//    session_unset();
//    session_destroy();
    header('Location: '.php_self());
}


#============================================================================#
# forum_autoformat - It slices! It dices! It even autolinks *correctly*! :D  #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $text - text to format                                                   #
# Returns:                                                                   #
#   (function) - Formatted text                                              #
#============================================================================#
function forum_autoformat ($text, $link_only = false)
{
    # These are the TLDs that will be autolinked without any "http://" or even "www."
    # in front. Note that adding a lot of TLDs here will slow the autoformat function
    # down considerably, so try to only use common ones.
    $tld = array('.com','.edu','.gov','.net','.org');

    # Symbols allowed in URLs
    $allowed_url = "+-=._/*(),@'$:;&!?%~[]{}\\|#";

    # Symbols NOT allowed in email addresses
    # (the @ is listed here because it can only occur once)
    $not_allowed_email = "()<>@,;:\\\"[]\r\n";

    # We start out not in a <code> tag
    $in_code = false;

    $first_char_on_line = false;

    # Scan the entire message one character at a time
    for ($offset=0; $offset<strlen($text); $offset++)
    {

        # Possibly found an HTML tag
        if ($text{$offset} == '<')
        {
            if (!$link_only && $in_code && (strtolower(substr($text, $offset, 7)) == '</code>'))
            {
                # Closing code tag
                $in_code = false;
                if (substr($text, $offset+7, 4) == "\r\n\r\n")
                {
                    $text = substr_replace($text, '</div><br />', $offset, 11);
                }
                elseif ((substr($text, $offset+7, 2) == "\n\n") || (substr($text, $offset+7, 2) == "\r\n"))
                {
                    $text = substr_replace($text, '</div><br />', $offset, 9);
                }
                elseif (substr($text, $offset+7, 1) == "\n")
                {
                    $text = substr_replace($text, '</div><br />', $offset, 8);
                }
                else
                {
                    $text = substr_replace($text, '</div><br />', $offset, 7);
                }
                $offset += 11;
                $first_char_on_line = true;
            }
            else if (!$link_only && !$in_code)
            {
                if ((strtolower(substr($text, $offset, 6)) == '<code>') && (TAG_CODE_ENABLED))
                {
                    # Opening code tag
                    $in_code = true;
                    if ((substr($text, $offset-14, 14) == "<br />\n<br />\n") || (substr($text, $offset-16, 16) == "<br />\r\n<br />\r\n"))
                    {
                        $text = substr_replace($text, '<div class="forum_code">', $offset, 6);
                        $offset += 23;
                    }
                    elseif ((substr($text, $offset-7, 7) == "<br />\n") || (substr($text, $offset-8, 8) == "<br />\r\n") || (trim(substr($text, 0, $offset-1)) == '') || ($offset == 0))
                    {
                        $text = substr_replace($text, '<br /><div class="forum_code">', $offset, 6);
                        $offset += 29;
                    }
                    else
                    {
                        $text = substr_replace($text, '<br /><br /><div class="forum_code">', $offset, 6);
                        $offset += 35;
                    }

                    if (substr($text, $offset+1, 2) == "\r\n")
                    {
                        $offset += 2;
                    }
                    elseif (substr($text, $offset+1, 1) == "\n")
                    {
                        $offset++;
                    }
                    $first_char_on_line = true;
                }
                elseif ( ((strtolower(substr($text, $offset, 3)) == '<b>') && (TAG_B_ENABLED)) ||
                         ((strtolower(substr($text, $offset, 3)) == '<i>') && (TAG_I_ENABLED)) ||
                         ((strtolower(substr($text, $offset, 3)) == '<u>') && (TAG_U_ENABLED)) )
                {
                    $offset += 2;
                }
                elseif ( ((strtolower(substr($text, $offset, 4)) == '</b>') && (TAG_B_ENABLED)) ||
                         ((strtolower(substr($text, $offset, 4)) == '</i>') && (TAG_I_ENABLED)) ||
                         ((strtolower(substr($text, $offset, 4)) == '</u>') && (TAG_U_ENABLED)) )
                {
                    $offset += 3;
                }
                else
                {
                    # Well, it wasn't a code tag, and HTML isn't allowed, so convert it to text
                    $text = substr_replace($text, '&lt;', $offset, 1);
                    $offset += 3;
                    $bad_link = false;
                    $first_char_on_line = false;
                }
            }
            else
            {
                # Convert HTML literals
                $text = substr_replace($text, '&lt;', $offset, 1);
                $offset += 3;
                $first_char_on_line = false;
            }
        }
        elseif ($text{$offset} == '>')
        {
            # Convert HTML literals
            $text = substr_replace($text, '&gt;', $offset, 1);
            $offset += 3;
            $first_char_on_line = false;
        }
        elseif ($text{$offset} == '&')
        {
            # Convert HTML literals
            $text = substr_replace($text, '&amp;', $offset, 1);
            $offset += 4;
            $first_char_on_line = false;
        }
        elseif (($text{$offset} == ' ') && ($text{$offset+1} == ' '))
        {
            # HTML compresses spaces unless they are non-breaking
            $text = substr_replace($text, '&nbsp; ', $offset, 2);
            $offset += 6;
            $first_char_on_line = false;
        }
        elseif (($text{$offset} == ' ') && ($text{$offset-1} == ' '))
        {
            # HTML compresses spaces unless they are non-breaking
            $text = substr_replace($text, '&nbsp;', $offset, 1);
            $offset += 5;
            $first_char_on_line = false;
        }
        elseif (($text{$offset} == ' ') && $first_char_on_line)
        {
            # HTML compresses spaces unless they are non-breaking
            $text = substr_replace($text, '&nbsp;', $offset, 1);
            $offset += 5;
            $first_char_on_line = false;
        }
        elseif (substr($text, $offset, 2) == "\r\n")
        {
            # Insert hard line breaks
            $text = substr_replace($text, "<br />\r\n", $offset, 2);
            $offset += 7;
            $first_char_on_line = true;
        }
        elseif ((substr($text, $offset, 1) == "\n") && (substr($text, $offset-1, 1) != "\r"))
        {
            # Insert hard line breaks (UNIX-style)
            $text = substr_replace($text, "<br />\n", $offset, 1);
            $offset += 6;
            $first_char_on_line = true;
        }
        elseif (!$in_code)
        {
            # Check for URLs and emails

            if (substr($text, $offset, 3) == '://')
            {
                # Found a URL...parse and replace it with a link
                for ($start=$offset-1; $start>=0; $start--)
                {
                    if (!ctype_alnum($text{$start}))
                    {
                        $start++;
                        break;
                    }
                }
                if ($start < 0) $start = 0;

                if ($start < $offset)
                {
                    for ($end=$offset+1; $end<strlen($text); $end++)
                    {
                        if (!ctype_alnum($text{$end}))
                        {
                            if (strrpos($allowed_url, $text{$end}) === false) break;
                            if ($end == strlen($text)-1) break;
                        }
                    }
                    if ($end == strlen($text)) $end = strlen($text)-1;

                    for ($real_end=$end; $real_end>$offset; $real_end--)
                    {
                        if (ctype_alnum($text{$real_end}) || ($text{$real_end} == '/')) break;
                    }
                    $end = $real_end+1;

                    $host = substr($text, $offset+3, $end-$offset-3);
                    if ( strrpos($host, '.') )
                    {
                        $url = substr($text, $start, $end-$start);
                        $url = '<a href="'.$url.'" target="_blank">'.$url.'</a>';
                        $text = substr_replace($text, $url, $start, $end-$start);
                        $offset = $start+strlen($url)-1;
                        $first_char_on_line = false;
                    }
                }
            }
            elseif ((strtolower(substr($text, $offset, 4)) == 'www.') || (strtolower(substr($text, $offset, 4)) == 'ftp.'))
            {
                # Found a URL...parse and replace it with a link
                $start = $offset;

                for ($end=$offset+1; $end<strlen($text); $end++)
                {
                    if (!ctype_alnum($text{$end}))
                    {
                        if (strrpos($allowed_url, $text{$end}) === false) break;
                        if ($end == strlen($text)-1) break;
                    }
                }
                if ($end == strlen($text)) $end = strlen($text)-1;

                for ($real_end=$end; $real_end>$offset; $real_end--)
                {
                    if (ctype_alnum($text{$real_end}) || ($text{$real_end} == '/')) break;
                }
                $end = $real_end+1;

                $host = substr($text, $offset+3, $end-$offset-3);
                if ( strrpos($host, '.') )
                {
                    $url = substr($text, $start, $end-$start);
                    if (substr($text, $offset, 4) == 'ftp.')
                    {
                        $real_url = 'ftp://'.$url;
                    }
                    else
                    {
                        $real_url = 'http://'.$url;
                    }
                    $url = '<a href="'.$real_url.'" target="_blank">'.$url.'</a>';
                    $text = substr_replace($text, $url, $start, $end-$start);
                    $offset = $start+strlen($url)-1;
                    $first_char_on_line = false;
                }
            }
            elseif ($text{$offset} == '@')
            {
                # Found an email address...parse and replace it with an email link
                for ($start=$offset-1; $start>=0; $start--)
                {
                    if ( (strrpos($not_allowed_email, $text{$start}) !== false) || ($text{$start} == ' ') )
                    {
                        $start++;
                        break;
                    }
                }
                if ($start<0) $start = 0;

                for ($end=$offset+1; $end<strlen($text); $end++)
                {
                    if (!ctype_alnum($text{$end}))
                    {
                        if (strrpos($allowed_url, $text{$end}) === false) break;
                        if ($end == strlen($text)-1) break;
                    }
                }
                if ($end == strlen($text)) $end = strlen($text)-1;

                for ($real_end=$end; $real_end>$offset; $real_end--)
                {
                    if (ctype_alnum($text{$real_end})) break;
                }
                $end = $real_end+1;

                $name = substr($text, $start, $offset-$start);
                $host = substr($text, $offset+1, $end-$offset-1);

                if (($name != '') && strrpos($host, '.'))
                {
                    $email = substr($text, $start, $end-$start);
                    $email = '<a href="'.html_unicode('mailto:'.$email).'">'.html_unicode($email).'</a>';
                    $text = substr_replace($text, $email, $start, $end-$start);
                    $offset = $start+strlen($email)-1;
                    $first_char_on_line = false;
                }

            }
            elseif ($text{$offset} == '.')
            {
                # Possibly found a URL...search through the TLDs and see if it matches
                foreach ($tld as $domain)
                {
                    if ( (strtolower(substr($text, $offset, strlen($domain))) == $domain) && ( (($offset+strlen($domain)) == strlen($text)) || (!ctype_alnum($text{$offset+strlen($domain)})) ) )
                    {
                        # Yep, parse and replace with a link
                        for ($start=$offset-1; $start>=0; $start--)
                        {
                            if (!ctype_alnum($text{$start}) && (strrpos($allowed_url, $text{$start}) === false) )
                            {
                                $start++;
                                break;
                            }
                        }
                        if ($start < 0) $start = 0;

                        for ($real_start=$start; $real_start<$offset; $real_start++)
                        {
                            if (ctype_alnum($text{$real_start}))
                            {
                                if ( (substr($text, $real_start-1, 4) == '&lt;') || (substr($text, $real_start-1, 4) == '&gt;') )
                                {
                                    $real_start += 2;
                                }
                                else
                                {
                                    break;
                                }
                            }
                        }
                        $start = $real_start;

                        if ($start < $offset)
                        {
                            for ($end=$offset+1; $end<strlen($text); $end++)
                            {
                                if (!ctype_alnum($text{$end}))
                                {
                                    if (strrpos($allowed_url, $text{$end}) === false) break;
                                    if ($end == strlen($text)-1) break;
                                }
                            }
                            if ($end == strlen($text)) $end = strlen($text)-1;

                            if ($end>=strlen($text)) $end=strlen($text)-1;
                            for ($real_end=$end; $real_end>$offset; $real_end--)
                            {
                                if (ctype_alnum($text{$real_end}) || ($text{$real_end} == '/')) break;
                            }
                            $end = $real_end+1;

                            $url = substr($text, $start, $end-$start);
                            if (strrpos(substr($text, $start, $offset-$start), '.')===false)
                            {
                                $real_url = 'http://www.'.$url;
                            }
                            else
                            {
                                $real_url = 'http://'.$url;
                            }

                            $url = '<a href="'.$real_url.'" target="_blank">'.$url.'</a>';
                            $text = substr_replace($text, $url, $start, $end-$start);

                            $offset = $start+strlen($url)-1;
                            $first_char_on_line = false;
                            break;
                        }
                    }
                }
            }
        }
        else
        {
            $first_char_on_line = false;
        }
    }

    # Close the code tag if the user forgot to
    if ($in_code) $text = $text.'</div><br />';

    # Return formatted text
    return $text;
}


#============================================================================#
# forum_unautoformat - Removes autoformatting from text (used for editing    #
#                      messages)                                             #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $text - text to unformat                                                 #
# Returns:                                                                   #
#   Unformatted text                                                         #
#============================================================================#
function forum_unautoformat ($text)
{
    $text = str_replace('<div class="forum_code">', '<code>', $text);
    $text = str_replace('</div>', '</code>'.BR.BR, $text);
    $text = str_replace('<br />', '', $text);
    $text = preg_replace('/<a (.*?)>/', '', $text);
    $text = str_replace('</a>', '', $text);
    $text = html_entity_decode($text);
    return $text;
}


#============================================================================#
# fast_wordwrap - Fast, but HTML-unsafe, wordwrap                            #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#      $text - Text to wrap                                                  #
#   $max_len - The maximum length a word can be before it is wrapped         #
#     $break - Character or string to insert as a breaking space             #
# Returns:                                                                   #
#   (function) - Wrapped text                                                #
#============================================================================#
function fast_wordwrap ($text, $max_len, $break)
{
    # Use safe wordwrap if HTML entities could be present
    if (strpos($text, '&') !== false) return safe_wordwrap($text, $max_len, $break);

    $words = explode(' ', $text);
    foreach($words as $index => $this_word)
    {
        $word_len = strlen($this_word);
        if ($word_len > $max_len) $this_word = chunk_split($this_word, floor($word_len/ceil($word_len/$max_len)), $break);
        $words[$index] = $this_word;
    }
    $text = implode(' ', $words);
    return $text;
}


#============================================================================#
# safe_wordwrap - HTML-safe wordwrap                                         #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#      $text - Text to wrap                                                  #
#   $max_len - The maximum length a word can be before it is wrapped         #
#     $break - Character or string to insert as a breaking space             #
# Returns:                                                                   #
#   (function) - Wrapped text                                                #
#============================================================================#
function safe_wordwrap ($text, $max_len, $break)
{
    $word_len = 0;

    for ($offset=0; $offset<strlen($text); $offset++)
    {
        if ($text{$offset} == '<')
        {
            if ((substr($text, $offset, 2) == '<a') || (substr($text, $offset, 4) == '<div'))
            {
                $offset = strpos($text, '</', $offset+1);
                if ($offset === false) break;
            }
            $offset = strpos($text, '>', $offset+1);
            if (($offset === false) || ($offset == strlen($text)-1)) break;
            $offset++;
            $word_len = 0;

        }
        elseif ($text{$offset} == '&')
        {
            $offset = strpos($text, ';', $offset+1);
            if (($offset === false) || ($offset == strlen($text)-1)) break;
            $offset++;
        }

        if ($text{$offset} != ' ')
        {
            $word_len++;
            if ($word_len > $max_len)
            {
                $text = substr_replace($text, $break, $offset, 0);
                $offset += strlen($break)-1;
                $word_len = 0;
            }
        }
        else
        {
            $word_len = 0;
        }
    }

    return $text;
}


#============================================================================#
# encode_field - Encodes a form field name                                   #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $field_name - Field name ('name', 'subject', 'message', or 'trap')       #
# Returns:                                                                   #
#   (function) - Encoded field name                                          #
#============================================================================#
function encode_field ($field_name)
{
    # This obviously isn't a very "secure" way to encode text, as it's only
    # meant to obscure the field names. Anyone with this souce can easily
    # decode the field names, but it should be good enough for spambots.

    switch ($field_name)
    {
        case 'name':
            $code = mt_rand(14, 27);
            break;
        case 'subject':
            $code = mt_rand(32, 46);
            break;
        case 'message':
            $code = mt_rand(60, 93);
            break;
        case 'trap':
            $code = array(mt_rand(10, 13), mt_rand(28, 31), mt_rand(47, 59), mt_rand(94, 99));
            $code = $code[mt_rand(0, 3)];
    }

    $time = substr(strrev(time()), 0, 8);
    $text = mt_rand(33, 74).$code.$time.mt_rand(1354, 6933);

    for ($i=0; $i<strlen($text); $i++)
    {
        $text[$i] = chr($text[$i] + 97 + $i);
    }
    return $text;
}


#============================================================================#
# decode_field - Decodes a form field name                                   #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $text - Encoded field name                                               #
# Returns:                                                                   #
#   (function 1) - Decoded field name                                        #
#   (function 2) - Time elapsed in seconds from when field was encoded       #
#============================================================================#
function decode_field ($text)
{
    for ($i=0; $i<strlen($text); $i++)
    {
        $text[$i] = chr(ord($text[$i]) - 49 - $i);
    }

    $code = substr($text, 2, 2);
    if ($code >= 14 && $code <= 27)
    {
        $field_name = 'name';
    }
    elseif ($code >= 32 && $code <= 46)
    {
        $field_name = 'subject';
    }
    elseif ($code >= 60 && $code <= 93)
    {
        $field_name = 'message';
    }
    else
    {
        $field_name = 'trap';
    }

    $time_elapsed = substr(time(), -8, 8) - strrev(substr($text, 4, 8));

    return array($field_name, $time_elapsed);
}


#============================================================================#
# slv_is_spam - Checks if the specified message registers as spam by SLV     #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#       $url - Complete URL to SLV XML-RPC                                   #
#      $name - Name posted (unformatted)                                     #
#   $subject - Subject posted (unformatted)                                  #
#   $message - Message posted (unformatted)                                  #
# Returns:                                                                   #
#   (function) - True if spam, false otherwise (returns false on errors)     #
#============================================================================#
function slv_is_spam ($url, $name, $subject, $message)
{
    $parsed = parse_url($url);
    $host = $parsed['host'];

    if (isset($parsed['port']))
        $port = $parsed['port'];
    else
        $port = 80;

    $path = $parsed['path'];
    if (isset($parsed['query'])) $path .= '?'.$parsed["query"];

    # Connect to SLV server
    $fp = @fsockopen($host, $port, $dummy, $dummy, SLV_TIMEOUT);
    if ($fp)
    {
        stream_set_blocking($fp, false);
        stream_set_timeout($fp, SLV_TIMEOUT);

        $all_fields = htmlspecialchars($name." ".$subject." ".$message);

        # Send request
        $send = '<?xml version="1.0"?><methodCall><methodName>slv</methodName>';
        $send .= '<params><param><value><string>'.$all_fields.'</string></value></param></params></methodCall>';
        $len = strlen($send);

        fputs($fp, "POST $path HTTP/1.0\r\nUser-Agent: FlatForum\r\nHost: $host\r\n");
        fputs($fp, "Content-Type: text/xml\r\nContent-length: $len\r\n\r\n");
        fputs($fp, $send);

        # Wait for response
        $response = '';
        $status = stream_get_meta_data($fp);
        $start = time();
        while (!feof($fp) && !$status['timed_out'])
        {
            $response .= fread($fp, 4096);
            $status = stream_get_meta_data($fp);
            if ((time()-$start) > SLV_TIMEOUT) $status['timed_out'] = true;
        }
        fclose($fp);

        if (!$status['timed_out'] && (strpos($response, '<fault>') === false))
        {
            # Server didn't timeout and there's no errors, so we're ok
            $start = strpos($response, '<int>');
            $end = strpos($response, '</int>');

            # Couldn't find an integer in the response
            if (($start === false) || ($end === false)) return false;

            # Get the response code
            $code = substr($response, $start+5, $end-$start-5);

            # A response code of '1' means it's not spam
            if ($code == 1) return false;
            return true;
        }
    }

    # SLV server timed out or is down
    return false;
}


#============================================================================#
# reject_spam - Called when a post is determined to be spam                  #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (never)                                                                  #
#============================================================================#
function reject_spam ()
{
    header(SPAM_RESPONSE_HEADER);
    exit();
}


#============================================================================#
# php_self - Returns the filename of the php script currently running        #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (function) - Filename of php script currently running                    #
#============================================================================#
function php_self ()
{
    $self = $_SERVER['PHP_SELF'];
    if ($self == '') return 'index.php?action=admin';
    return $self;
}


#============================================================================#
# get_ip - Attempts to get the client IP address using a variety of methods  #
#          (based on a function posted on php.net)                           #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   (none)                                                                   #
# Returns:                                                                   #
#   (function) - IP address, or "unknown" if unable to get the IP            #
#============================================================================#
function get_ip()
{
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
    {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
    {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
    {
        $ip = getenv('REMOTE_ADDR');
    }
    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    else
    {
        $ip = TEXT_UNKNOWN;
    }
    return($ip);
}


#============================================================================#
# get_var - Returns a variable passed by GET                                 #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $var - Variable name                                                     #
# Returns:                                                                   #
#   (function) - Variable value, or null string if not set                   #
#============================================================================#
function get_var ($var)
{
    if (isset($_GET[$var]))
        return array_stripslashes($_GET[$var]);
    else
        return '';
}


#============================================================================#
# post_var - Returns a variable passed by POST                               #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $var - Variable name                                                     #
# Returns:                                                                   #
#   (function) - Variable value, or null string if not set                   #
#============================================================================#
function post_var ($var)
{
    if (isset($_POST[$var]))
        return array_stripslashes($_POST[$var]);
    else
        return '';
}


#============================================================================#
# session_var - Returns a SESSION variable                                   #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $var - Variable name                                                     #
# Returns:                                                                   #
#   (function) - Variable value, or null string if not set                   #
#============================================================================#
function session_var ($var)
{
    if (isset($_SESSION[$var]))
        return array_stripslashes($_SESSION[$var]);
    else
        return '';
}


#============================================================================#
# array_stripslashes - Recursively strips the slashes out of an array        #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $array - Array to strip slashes from                                     #
# Returns:                                                                   #
#   (function) - Array with slashes stripped                                 #
#============================================================================#
function array_stripslashes ($array)
{
    if (is_array($array))
    {
        foreach ($array as $index => $this_element)
        {
            $array[$index] = array_stripslashes($this_element);
        }
    }
    else
    {
        $array = str_replace('`', "'", stripslashes($array));
    }

    return $array;
}


#============================================================================#
# html_unicode - Converts text to HTML unicode (UTF-8)                       #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $text - Text to convert                                                  #
# Returns:                                                                   #
#   (function) - UTF-8 encoded text                                          #
#============================================================================#
function html_unicode ($text)
{
    $unicode = utf8_encode($text);
    $html = '';
    for ($offset=0; $offset<strlen($unicode); $offset++)
    {
        $html .= '&#'.ord($unicode{$offset}).';';
    }
    return $html;
}


#============================================================================#
# space - Generates a browser-independent space that is the specified        #
#         number of pixels tall                                              #
#----------------------------------------------------------------------------#
# Parameters:                                                                #
#   $height - Height of space, in pixels                                     #
# Returns:                                                                   #
#   (function) - HTML code for space                                         #
#============================================================================#
function space ($height)
{
    return '<div style="line-height:'.$height.'px; margin-top:0px; margin-bottom:0px;">&nbsp;</div>'.BR;
}


#============================================================================#
# FlatForum by Plasma / Jon Petrosky                                         #
#============================================================================#
?>
