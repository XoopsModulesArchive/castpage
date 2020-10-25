<?php

#########################################################################
# CastPage                                                              #
# ============================================                          #
#                                                                       #
# Copyright (c) 2006 by Mathew Anderson                                 #
# http://www.snotling.org                                               #
#                                                                       #
# This program is free software. You can redistribute it and/or modify  #
# it under the terms of the GNU General Public License as published by  #
# the Free Software Foundation; either version 2 of the License.        #
#########################################################################

# Default require stuff
require_once '../../mainfile.php';

#############
# global Variables - these you do not need to change.
$Chapter_directory = '';

# Get the setup information from the database
$Query = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Setup') . '';
$LookupResult = $xoopsDB->query($Query) || die('Error looking up Setup information ' . $GLOBALS['xoopsDB']->error());
$foo = $xoopsDB->fetchArray($LookupResult);
$Cast_name = $foo['title'];
$next_image = $foo['next_image'];
$prev_image = $foo['prev_image'];
$Image_size_x = $foo['Image_size_x'];
$Image_size_y = $foo['Image_size_y'];
$First_seen = $foo['First_seen'];
$Last_seen = $foo['Last_seen'];

##############################################################
# Display The Primary Screen
##############################################################
function CastPage_IntroScreen($current_chapter)
{
    global $xoopsDB;

    global $Chapter_directory, $Cast_name, $next_image, $prev_image;

    # If the chapter is NOT defined, assume they want to goto the first chapter.

    if ('' == $current_chapter) {
        $chapquery = 'select MIN(Chapter_id) from ' . $xoopsDB->prefix('CastPage_Chapters') . '';

        $chapResult = $xoopsDB->query($chapquery) || die('Error looking up Chapterid ' . $GLOBALS['xoopsDB']->error());

        $foo = $xoopsDB->fetchArray($chapResult);

        $current_chapter = $foo['MIN(Chapter_id)'];

        $prev = 0;

        $next = 0;
    }

    echo '<center><Font size=+3>' . $Cast_name . '</font><br>';

    echo '<table><tr>';

    echo '<td  class="itemFoot"> ';

    #Get the Chapter Information

    $chapterQuery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Chapters') . " WHERE Chapter_id = $current_chapter";

    $chapterResult = $xoopsDB->query($chapterQuery) || die('Error looking up Chapter table ' . $GLOBALS['xoopsDB']->error());

    $max_chapter = $xoopsDB->getRowsNum($chapterResult);

    $chapterInformation = $xoopsDB->fetchArray($chapterResult);

    $Chapter_name = $chapterInformation['Chapter_name'];

    $Chapter_image = $chapterInformation['Chapter_image'];

    $Chapter_directory = $chapterInformation['Chapter_directory'];

    #select all chapter_ids.  loop through them to get the prev and the next.

    $chapterLookupQuery = 'SELECT Chapter_id FROM ' . $xoopsDB->prefix('CastPage_Chapters') . ' ORDER BY Chapter_id';

    $chapterLookupResult = $xoopsDB->query($chapterLookupQuery) || die('Error looking up Chapter id ' . $GLOBALS['xoopsDB']->error());

    $chapterNumber = $xoopsDB->getRowsNum($chapterLookupResult);

    for ($k = 0; $k < $chapterNumber; $k++) {
        $foo = $xoopsDB->fetchArray($chapterLookupResult);

        $temp = $foo['Chapter_id'];

        if ($temp < $current_chapter) {
            $prev = $temp;
        }

        if ($temp == $current_chapter) {
            #next one will be the next chapter

            $foo = $xoopsDB->fetchArray($chapterLookupResult);

            $next = $foo['Chapter_id'];

            $k = $chapterNumber;
        }
    }

    # print out the pointers

    # if the previous chapter > 0 then we have a previous chapter, so print a left

    # if current_chapter < next, then we have a next chapter, so then print a right

    if ($prev > 0) {
        echo "<td valign=bottom><a href=index.php?chapter=$prev><img bottom border=0 height=50 src=images/" . $prev_image . '></a></td>';
    } else {
        echo '<td></td>';
    }

    echo '<td><IMG height=300  src="images/' . $Chapter_directory . '/' . $Chapter_image . '"></td>';

    if ($current_chapter < $next) {
        echo "<td valign=bottom><a href=index.php?chapter=$next><img border=0 height=50 src=images/" . $next_image . '></a></td>';
    } else {
        echo '<td></td>';
    }

    echo ' </td></tr></table></center>';

    # loop for each section, current chapter only

    $sectionQuery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Sections') . '';

    $result = $xoopsDB->query($sectionQuery) || die('Error looking up Selection table ' . $GLOBALS['xoopsDB']->error());

    $num = $xoopsDB->getRowsNum($result);

    if (0 == $num) {
        $castQuery = 'select * from ' . $xoopsDB->prefix('CastPage') . " where chapter_id=$current_chapter";

        $resultCast = $xoopsDB->query($castQuery) || die('Error looking up Cast table (no section) ' . $GLOBALS['xoopsDB']->error());

        $castCount = $xoopsDB->getRowsNum($result2);

        printgroup('', $resultCast, $castCount);
    }

    # for each section_id

    for ($k = 0; $k < $num; $k++) {
        $sectionInfo = $xoopsDB->fetchArray($result);

        $castQuery = 'select * from ' . $xoopsDB->prefix('CastPage') . " where chapter_id=$current_chapter and section_id=$sectionInfo[Section_id]";

        $resultCast = $xoopsDB->query($castQuery) || die('Error looking up Cast table ' . $GLOBALS['xoopsDB']->error());

        $castCount = $xoopsDB->getRowsNum($resultCast);

        printgroup($sectionInfo['Section_name'], $resultCast, $castCount);
    }
}

#########
function printgroup($title, $result, $castCount)
{
    global $xoopsDB;

    if (0 == $castCount) {
    } else {
        printtitle($title);

        $count = 0;

        for ($k = 0; $k < $castCount; $k++) {
            $castInfo = $xoopsDB->fetchArray($result);

            if (0 == $count) {
                printleft($castInfo);

                $count++;
            } else {
                printright($castInfo);

                $count = 0;
            }
        }
    }
}

#########
function printtitle($title)
{
    echo "<table><th><font size=+2>$title</font></th></table>";
}

#########
function printright($character)
{
    global $Chapter_directory, $First_seen, $Last_seen, $Image_size_x, $Image_size_y;

    echo '<DIV align="right"> <H3>' . $character['name'] . '</H3> </DIV>
        <DIV style="border:1px solid #000000; padding-left:15px">
        <DIV style="float:right; margin: 1px 1px 1px 1px ">';

    if ((0 == $Image_size_x) || (0 == $Image_size_y)) {
        echo "<IMG src=\"images/$Chapter_directory/" . $character['picture'] . '"> ';
    } else {
        echo '<IMG height=' . $Image_size_y . ', width=' . $Image_size_y . " src=\"images/$Chapter_directory/" . $character['picture'] . '"> ';
    }

    echo " </DIV>
	$First_seen " . $character['first_seen'] . ' ';

    if ('' == $character['last_seen']) {
    } else {
        echo "- $Last_seen " . $character['last_seen'] . '</font>';
    }

    echo '   <br>
           ' . $character['description'] . '
           <DIV style="clear:both "> </DIV>
        </DIV> ';
}

function printleft($character)
{
    global $Chapter_directory, $First_seen, $Last_seen, $Image_size_x, $Image_size_y;

    echo "<DIV align=\"left\"> <H3>$character[name]</H3> </DIV>
        <DIV style=\"border:1px solid #000000; padding-right:15px\">
        <DIV style=\"float:left; margin: 1px 1px 1px 1px \">";

    if ((0 == $Image_size_x) || (0 == $Image_size_y)) {
        echo "<IMG  src=\"images/$Chapter_directory/" . $character['picture'] . '">';
    } else {
        echo '<IMG height=' . $Image_size_y . ', width=' . $Image_size_y . " src=\"images/$Chapter_directory/" . $character['picture'] . '">';
    }

    echo " </DIV>
	$First_seen " . $character['first_seen'] . ' ';

    if ('' == $character['last_seen']) {
    } else {
        echo "- $Last_seen " . $character['last_seen'] . '</font>';
    }

    echo '   <br>
           ' . $character['description'] . '
           <DIV style="clear:both "> </DIV>
        </DIV> ';
}

#####################################
# End of functions

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
import_request_variables('gp', 'param_');
require XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('page_title', 'MY TITLE');

# Default switch statement so the module knows what do do next
if (!isset($param_op)) {
    $param_op = 'main';
}

switch ($param_op) {
    case 'main':
        CastPage_IntroScreen($param_chapter);
        break;
    default:
        CastPage_IntroScreen($param_chapter);
        break;
}

require XOOPS_ROOT_PATH . '/footer.php';
