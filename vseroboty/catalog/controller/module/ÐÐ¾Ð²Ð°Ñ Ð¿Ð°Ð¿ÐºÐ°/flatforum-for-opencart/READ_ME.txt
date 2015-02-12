****************************************************
*     Mod: FlatForum - Flatfile Database Forum     *
* Version: 1.0                                     *
*  Author: Best-Byte - www.best-byte.com           *
* Contact: best-byte@gmx.com                       *
*    Date: December 2011, Updated March 2012       *
****************************************************

REQUIREMENTS:
=============
Opencart Versions 1.5.1.x, 1.5.2.x

vQmod Versions 2.1.x (Required for admin and footer links to forum)

WHAT DOES IT DO:
================
This adds a nice, lightweight, easy to use and maintain forum to your store.

FlatForum is a fast, clean, and simple threaded forum that uses a flatfile
database. It's designed for people who have small to medium-sized websites
and don't want a huge, bloated forum like phpBB.

FEATURE OVERVIEW:
=================
>> Small and fast. The required files for FlatForum are less than 150k combined.
>> Uses an optimized flatfile database. Unlike most flatfile forums, FlatForum
   won't bog down when your forum starts to get a lot of posts because it doesn't 
   load the entire database for each page view.
>> Does not use any SQL, so mySQL, PostgreSQL, etc. are not required.   
>> Easy to customize. You can change the appearance by editing the css file and can also add a custom header and footer.
>> Autolinking that WORKS (all URLs and email addresses).
>> Identical appearance in IE, Firefox, Chrome, and Opera.
>> Optional word filters (censors).
>> Advanced spambot protection without requiring user registration or captchas.
>> You administrate the forum through your OpenCart Admin.
>> Adds links to the forum in the admin and the footer of your store. (vQmod required)
>> Detailed instructions included in download.
>> Free installation and support.

COMPATIBILITY:
==============
Successfully tested on OpenCart Versions 1.5.1.x and 1.5.2.x with Default Theme and vQmod Versions 2.1.x.

BEST-BYTE LICENSE:
==================
This is now a free mod made by Best-Byte and can only be used for personal use. 
You can modify it as you like as long as all references to Best-Byte are left intact. 
You can not sell this mod but you can give it away for free as long as the archive 
and all references to Best-Byte are left intact.

FLATFORUM LICENSE:
==================                                                      
You can use this forum on your own site freely, as long as you do not remove or 
change the "Powered by FlatForum" link in any way. You may modify anything else 
in the script as you see fit. If you redistribute the modified script, just make 
sure you give credit where it's due. 

INSTALLATION:
=============
1) ALWAYS make a backup of your site before applying new mods etc. Better to be safe than sorry.

2) Unzip the downloaded archive.

3) You should have a folder called flatforum-for-opencart. In this folder is a folder called upload. 
   
4) Open the readme.txt file located in the upload/forum folder and READ IT. It will tell you all the 
   information you will need to know to customize and use your forum. 
   
5) In the forum folder is the settings.php file that contains all the settings for your forum. Edit this as you like.
   There are 2 css files (nqb.css and phat.css) that control the appearance of the forum. The nqb.css file is the one used by 
   default and is the only one you need to customize the appearance. The footer.php and header.php files can be edited to add 
   a custom footer and header for the forum.   

6) Upload the contents of the folder called upload (which are the folders called admin, catalog, forum, and vqmod) to
   the root directory of your OpenCart installation. This is where you have OpenCart installed at. 
   These are new files and no files should be overwritten.   

7) This will apply the forum to your site. You will need to set the permissions for the forum admin in the Admin for OpenCart.
   To do this go to your OpenCart Admin. Select System > Users > User Groups. Click on Edit for Top Administrator. Under Access
   Permission and Modify Permission select catalog/forum for both. Click on Save.

8) To access the forum click on the Forum link under Customer Service in the footer section of your site.
   To access the administration for the forum go to your OpenCart Admin and under Catalog at the bottom is the link to the Forum admin. 
   Use "password" as the password without the quotes for the first time. Change this to your own password in the forum administration. 
   
9) When you Log Out of the forum admin it will show the forum as it is displayed in the front end of your site. To access the forum 
   admin again select Forum under Catalog in the top menu or just refresh the page. 

10) That is all. Thank you for downloading and using this mod. If you like this mod please give a star rating for it.
    I only created the ability to use this forum in OpenCart and did not create the forum itself. As such I will only 
    support what I have created. For complex questions or problems related to the actual forum itself you will need to 
    contact the creator of it here: http://www.phatcode.net. If you are having problems installing this I will be happy 
    to do it for you for free. I will only install it and make sure it is working for free. 
    ANY customizations are up to you to apply.
   
        