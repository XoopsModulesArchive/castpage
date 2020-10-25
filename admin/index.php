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

require_once dirname(__DIR__, 3) . '/include/cp_header.php';

# Get all HTTP post or get parameters, prefixed by "param_"
import_request_variables('gp', 'param_');

################################
# Main Menu
################################

function CastPage_Main()
{
    xoops_cp_header();

    global $xoopsDB;

    echo '<center><font class="title"><b> CastPage Administration Pannel</b></font></center>';

    echo '<center>';

    echo 'Chapter/Section Adminisration<br>';

    echo '<a href=index.php?op=newChapter>Add Chapter</a> | <a href=index.php?op=editChapter>Edit Chapter</a> | <a href=index.php?op=delChapter>Remove Chapter</a><br>';

    echo '<a href=index.php?op=newSection>Add Section</a> | <a href=index.php?op=editSection>Edit Section</a> | <a href=index.php?op=delSection>Remove Section</a><br>';

    echo '<a href=index.php?op=setup>Setup</a>';

    echo '</center>';

    $query = 'SELECT ID, name, Chapter_name, description FROM ' . $xoopsDB->prefix('CastPage') . ', ' . $xoopsDB->prefix('CastPage_Chapters') . ' WHERE ' . $xoopsDB->prefix('CastPage_Chapters') . '.Chapter_id = ' . $xoopsDB->prefix('CastPage') . '.chapter_id';

    $result = $xoopsDB->query($query);

    $num = $xoopsDB->getRowsNum($result);

    $cquery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Chapters') . '';

    $cresult = $xoopsDB->query($cquery);

    $cnum = $xoopsDB->getRowsNum($cresult);

    $squery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Sections') . '';

    $sresult = $xoopsDB->query($squery);

    $snum = $xoopsDB->getRowsNum($sresult);

    echo "<a href=index.php?op=newCastMember>Add a New Cast Member</a><p>\n";

    echo "There are currently $num Characters in $snum Sections with $cnum Chapters.";

    echo "<center><table bgcolor=\"$bgcolor4\" border=\"0\" cellpadding=\"4\" width=\"100%\" cellspacing=\"1\">
         <TR><TH bgcolor=\"$bgcolor2\">ID</TH>
         <TH bgcolor=\"$bgcolor2\">Name</TH>
         <TH bgcolor=\"$bgcolor2\">Chapter</TH>
         <TH bgcolor=\"$bgcolor2\">Breif Description</TH>
         <TH  bgcolor=\"$bgcolor2\" colspan=2>Options</TH></TR>\n";

    for ($k = 0; $k < $num; $k++) {
        $castinfo = $xoopsDB->fetchArray($result);

        echo '<TR><TD>' . $castinfo['ID'] . '</TD>
            <TD>' . mb_substr($castinfo['name'], 0, 15) . '</TD>
            <TD>' . $castinfo['Chapter_name'] . '</TD>
            <TD>' . mb_substr($castinfo['description'], 0, 60) . '</TD>
            <TD><a href=index.php?op=editCastMember&castID=' . $castinfo['ID'] . '>Edit</a></TD>
            <TD><form action="index.php?op=delCastMember" method="POST">
                <input type="hidden" name="castID" value="' . $castinfo['ID'] . "\">
                <input type=\"submit\" name=\"addchapter\" value=\"Delete\">
                </form>
            </TD></TR>\n";
    }

    echo '</TABLE></center>';

    xoops_cp_footer();
}

#################################################

# The various ADD functions
#  * addChapter
#  * addSection
#  * addCastMember
#################################################
function addChapter($httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $chapterinfo[$j] = $value;

            #     echo "$j - $key - $value <br> \n";

            $j++;
        }

        $query = 'INSERT INTO ' . $xoopsDB->prefix('CastPage_Chapters') . "(Chapter_name, Chapter_image, Chapter_directory) VALUES('$chapterinfo[0]', '$chapterinfo[1]', '$chapterinfo[2]')";

        $xoopsDB->query($query) || die('Error Adding to Chapter table ' . $GLOBALS['xoopsDB']->error());

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Adding a New Chapter</center>';

        echo '<form action="index.php?op=newChapter" method="POST">
             <b>Chapter Title:</b> <input type="text" size="20" name="title" maxlength="255"><br>
             <b>Picture Filename:</b> <input type="text" size="20" name="picture" maxlength="255"><br>
             <b>Directory:</b> <input type="text" size="20" name="directory" maxlength="255"><br>
             <input type="submit" name="addchapter" value="Add New Chapter">
             <input type="button" value="Cancel" onclick="history.go(-1)">
             <input type="reset" value="Reset Form">
             </form>';

        xoops_cp_footer();
    }
}

#################################################

#################################################
function addSection($httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $sectioninfo[$j] = $value;

            #     echo "$j - $key - $value <br> \n";

            $j++;
        }

        $query = 'INSERT INTO ' . $xoopsDB->prefix('CastPage_Sections') . "(Section_name) VALUES('$sectioninfo[0]')";

        $xoopsDB->query($query) || die('Error Adding to Section table ' . $GLOBALS['xoopsDB']->error());

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Adding a New Section</center>';

        echo '<form action="index.php?op=newSection" method="POST">
             <b>Section Name:</b> <input type="text" size="20" name="name" maxlength="255"><br>
             <input type="submit" name="addsection" value="Add New Section">
             <input type="button" value="Cancel" onclick="history.go(-1)">
             <input type="reset" value="Reset Form">
             </form>';

        xoops_cp_footer();
    }
}

#################################################
function addCast($httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $castinfo[$j] = $value;

            #echo "$j - $key - $value <br> \n";

            $j++;
        }

        $query = 'INSERT INTO ' . $xoopsDB->prefix('CastPage') . "(name, first_seen, last_seen, picture, chapter_id, section_id, description) VALUES('$castinfo[0]', '$castinfo[1]', '$castinfo[2]', '$castinfo[3]', '$castinfo[4]', '$castinfo[5]', '$castinfo[6]')";

        $xoopsDB->query($query) || die('Error Adding to Cast table ' . $GLOBALS['xoopsDB']->error());

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Adding a New Cast Member</center>';

        echo '<form action="index.php?op=newCastMember" method="POST">
             <b>Name:</b> <input type="text" size="20" name="name" maxlength="255"><br>
             <b>First Seen:</b> <input type="text" size="20" name="firstseen" maxlength="255"><br>
             <b>Last Seen:</b> <input type="text" size="20" name="lastseen" maxlength="255"><br>
             <b>Picture Filename:</b> <input type="text" size="20" name="picture" maxlength="255"><br>';

        echo '<b>Chapter:</b> <select name="Chapter" size="1"> ';

        $chapterLookupQuery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Chapters') . ' ORDER BY Chapter_id';

        $chapterLookupResult = $xoopsDB->query($chapterLookupQuery) || die('Error looking up Chapter id ' . $GLOBALS['xoopsDB']->error());

        $chapterNumber = $xoopsDB->getRowsNum($chapterLookupResult);

        for ($k = 0; $k < $chapterNumber; $k++) {
            $foo = $xoopsDB->fetchArray($chapterLookupResult);

            echo '<option value="' . $foo['Chapter_id'] . '">' . $foo['Chapter_name'] . '</option>';
        }

        echo '</select><br>';

        echo '<b>Section:</b> <select name="Section" size="1"> ';

        $sectionLookupQuery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Sections') . ' ORDER BY Section_id';

        $sectionLookupResult = $xoopsDB->query($sectionLookupQuery) || die('Error looking up Section id ' . $GLOBALS['xoopsDB']->error());

        $sectionNumber = $xoopsDB->getRowsNum($sectionLookupResult);

        for ($k = 0; $k < $sectionNumber; $k++) {
            $foo = $xoopsDB->fetchArray($sectionLookupResult);

            echo '<option value="' . $foo['Section_id'] . '">' . $foo['Section_name'] . '</option>';
        }

        echo '</select><br>';

        echo '<b>Description:</b> <textarea name="description" rows="8" cols="40"></textarea><br>
             <input type="submit" name="addcast" value="Add New Cast">
             <input type="button" value="Cancel" onclick="history.go(-1)">
             <input type="reset" value="Reset Form">
             </form>';

        xoops_cp_footer();
    }
}

#################################################
# The various DEL functions
#  * delChapter
#  * delSection
#  * delCastMember
#################################################
function delChapter($httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $chapterinfo[$j] = $value;

            #    echo "$j - $key - $value <br> \n";

            $j++;
        }

        $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('CastPage_Chapters') . " WHERE Chapter_id = '$chapterinfo[0]'");

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Removing a Chapter</center>';

        echo '<form action="index.php?op=delChapter" method="POST">
             <select name="Chapter" size="1"> ';

        $chapterLookupQuery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Chapters') . ' ORDER BY Chapter_id';

        $chapterLookupResult = $xoopsDB->query($chapterLookupQuery) || die('Error looking up Chapter id ' . $GLOBALS['xoopsDB']->error());

        $chapterNumber = $xoopsDB->getRowsNum($chapterLookupResult);

        for ($k = 0; $k < $chapterNumber; $k++) {
            $foo = $xoopsDB->fetchArray($chapterLookupResult);

            echo '<option value="' . $foo['Chapter_id'] . '">' . $foo['Chapter_name'] . '</option>';
        }

        echo '</select>
             <input type="submit" name="delchap" value="Remove Chapter">
             <input type="button" value="Cancel" onclick="history.go(-1)">
             <input type="reset" value="Reset Form">
             </form>';

        xoops_cp_footer();
    }
}

#################################################
function delSection($httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $sectioninfo[$j] = $value;

            #     echo "$j - $key - $value <br> \n";

            $j++;
        }

        $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('CastPage_Sections') . " WHERE Section_id = '$sectioninfo[0]'");

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Removing a Section</center>';

        echo '<form action="index.php?op=delSection" method="POST">
             <select name="Section" size="1"> ';

        $sectionLookupQuery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Sections') . ' ORDER BY Section_id';

        $sectionLookupResult = $xoopsDB->query($sectionLookupQuery) || die('Error looking up Section id ' . $GLOBALS['xoopsDB']->error());

        $sectionNumber = $xoopsDB->getRowsNum($sectionLookupResult);

        for ($k = 0; $k < $sectionNumber; $k++) {
            $foo = $xoopsDB->fetchArray($sectionLookupResult);

            echo '<option value="' . $foo['Section_id'] . '">' . $foo['Section_name'] . '</option>';
        }

        echo '</select>
             <input type="submit" name="delsect" value="Remove Section">
             <input type="button" value="Cancel" onclick="history.go(-1)">
             <input type="reset" value="Reset Form">
             </form>';

        xoops_cp_footer();
    }
}

#################################################
function delCastMember($httpdvars)
{
    global $xoopsDB;

    while (list($key, $value) = each($httpdvars)) {
        #echo "$key - $value <br> \n";

        if ('castID' == $key) {
            $castID = $value;
        }
    }

    if ($castID) {
        # TODO

        # Should verify that the admin really wants to remove the

        # cast member first.

        $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('CastPage') . " WHERE ID = '$castID'");

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Removing a Cast Member</center>';

        echo ' You should not be here...';

        xoops_cp_footer();
    }
}

#################################################
# The various Edit functions
#  * editChapter
#  * editSection
#  * editCastMember
#################################################
function editChapter($httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $chapterinfo[$j] = $value;

            #  echo "$j - $key - $value <br> \n";

            $j++;
        }

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('CastPage_Chapters') . " SET Chapter_name = '$chapterinfo[1]', Chapter_image = '$chapterinfo[2]', Chapter_directory = '$chapterinfo[3]' WHERE Chapter_id = '$chapterinfo[0]'");

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Editing a Chapter</center>';

        $Query = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Chapters') . '';

        $LookupResult = $xoopsDB->query($Query) || die('Error looking up Chapters ' . $GLOBALS['xoopsDB']->error());

        $Number = $xoopsDB->getRowsNum($LookupResult);

        echo "<table>\n<tr><th>Title</th><th>Image</th><th>Directory</th></tr>\n";

        for ($k = 0; $k < $Number; $k++) {
            $foo = $xoopsDB->fetchArray($LookupResult);

            echo '<tr><td><form action="index.php?op=editChapter" method="POST">
                 <input type="hidden" name="id" value="' . $foo['Chapter_id'] . '">
                 <input type="text" size="20" name="title" maxlength="255" value="' . $foo['Chapter_name'] . '">
                 </td>
                 <td><input type="text" size="20" name="picture" maxlength="255" value="' . $foo['Chapter_image'] . '"></td>
                 <td><input type="text" size="20" name="directory" maxlength="255" value="' . $foo['Chapter_directory'] . '"></td>
                 <td><input type="submit" name="editchapter" value="Submit Changes">
                 </form></td></tr>';
        }

        echo '</table>';

        xoops_cp_footer;
    }
}

#################################################
function editSection($httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $sectioninfo[$j] = $value;

            #  echo "$j - $key - $value <br> \n";

            $j++;
        }

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('CastPage_Sections') . " SET Section_name = '$sectioninfo[1]' WHERE Section_id = '$sectioninfo[0]'");

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Editing a Section</center>';

        $Query = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Sections') . '';

        $LookupResult = $xoopsDB->query($Query) || die('Error looking up Sections ' . $GLOBALS['xoopsDB']->error());

        $Number = $xoopsDB->getRowsNum($LookupResult);

        echo "<table>\n<tr><th>Title</th></tr>\n";

        for ($k = 0; $k < $Number; $k++) {
            $foo = $xoopsDB->fetchArray($LookupResult);

            echo '<tr><td><form action="index.php?op=editSection" method="POST">
                 <input type="hidden" name="id" value="' . $foo['Section_id'] . '">
                 <input type="text" size="20" name="title" maxlength="255" value="' . $foo['Section_name'] . '">
                 </td>
                 <td><input type="submit" name="editsection" value="Submit Changes">
                 </form></td></tr>';
        }

        echo '</table>';

        xoops_cp_footer();
    }
}

#################################################
function editCastMember($castID, $httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $castinfo[$j] = $value;

            #    echo "$j - $key - $value <br> \n";

            $j++;
        }

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('CastPage') . " SET name = '$castinfo[1]', first_seen = '$castinfo[2]', last_seen = '$castinfo[3]', picture = '$castinfo[4]', chapter_id = '$castinfo[5]', section_id = '$castinfo[6]', description = '$castinfo[7]'  WHERE ID = '$castinfo[0]'");

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Editing a Cast Member</center>';

        $Query = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage') . ' where ID = ' . $castID . ' ';

        $LookupResult = $xoopsDB->query($Query) || die('Error looking up Sections ' . $GLOBALS['xoopsDB']->error());

        $Number = $xoopsDB->getRowsNum($LookupResult);

        for ($k = 0; $k < $Number; $k++) {
            $cast = $xoopsDB->fetchArray($LookupResult);

            echo '<form action="index.php?op=editCastMember" method="POST">
                 <input type="hidden" name="ID" value="' . $cast['ID'] . '">
                 <b>Name:</b> <input type="text" size="20" name="name" maxlength="255" value="' . $cast['name'] . '"><br>
                 <b>First Seen:</b> <input type="text" size="20" name="firstseen" maxlength="255" value="' . $cast['first_seen'] . '"><br>
                 <b>Last Seen:</b> <input type="text" size="20" name="lastseen" maxlength="255" value="' . $cast['last_seen'] . '"><br>
                 <b>Picture Filename:</b> <input type="text" size="20" name="picture" maxlength="255" value="' . $cast['picture'] . '"><br>';

            echo '<b>Chapter:</b> <select name="Chapter" size="1" > ';

            $chapterLookupQuery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Chapters') . ' ORDER BY Chapter_id';

            $chapterLookupResult = $xoopsDB->query($chapterLookupQuery) || die('Error looking up Chapter id ' . $GLOBALS['xoopsDB']->error());

            $chapterNumber = $xoopsDB->getRowsNum($chapterLookupResult);

            for ($k = 0; $k < $chapterNumber; $k++) {
                $foo = $xoopsDB->fetchArray($chapterLookupResult);

                if ($foo['Chapter_id'] == $cast['chapter_id']) {
                    echo '<option selected value="' . $foo['Chapter_id'] . '">' . $foo['Chapter_name'] . '</option>';
                } else {
                    echo '<option value="' . $foo['Chapter_id'] . '">' . $foo['Chapter_name'] . '</option>';
                }
            }

            echo '</select><br>';

            echo '<b>Section:</b> <select name="Section" size="1" value="' . $cast[section_id] . '"> ';

            $sectionLookupQuery = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Sections') . ' ORDER BY Section_id';

            $sectionLookupResult = $xoopsDB->query($sectionLookupQuery) || die('Error looking up Section id ' . $GLOBALS['xoopsDB']->error());

            $sectionNumber = $xoopsDB->getRowsNum($sectionLookupResult);

            for ($k = 0; $k < $sectionNumber; $k++) {
                $foo = $xoopsDB->fetchArray($sectionLookupResult);

                if ($foo['Section_id'] == $cast['section_id']) {
                    echo '<option selected value="' . $foo['Section_id'] . '">' . $foo['Section_name'] . '</option>';
                } else {
                    echo '<option value="' . $foo['Section_id'] . '">' . $foo['Section_name'] . '</option>';
                }
            }

            echo '</select><br>';

            echo '<b>Description:</b> <textarea name="description" rows="8" cols="40">';

            echo '' . $cast['description'] . '';

            echo '</textarea><br> <input type="submit" name="editsection" value="Submit Changes"> ';
        }

        xoops_cp_footer();
    }
}

#################################################
#  Setting up the Cast Display
#################################################
function setup($httpdvars)
{
    global $xoopsDB;

    if ($httpdvars) {
        $j = 0;

        while (list($key, $value) = each($httpdvars)) {
            $chapterinfo[$j] = $value;

            # echo "$j - $key - $value <br> \n";

            $j++;
        }

        $xoopsDB->query(
            'UPDATE '
            . $xoopsDB->prefix('CastPage_Setup')
            . " SET title = '$chapterinfo[0]', next_image = '$chapterinfo[1]', prev_image = '$chapterinfo[2]', Image_size_x = '$chapterinfo[3]', Image_size_y = '$chapterinfo[4]', First_seen = '$chapterinfo[5]', Last_seen = '$chapterinfo[6]' WHERE id = '1'"
        );

        CastPage_Main();
    } else {
        xoops_cp_header();

        echo '<center>CastPage Admin <br> Site Setup</center>';

        $Query = 'SELECT * FROM ' . $xoopsDB->prefix('CastPage_Setup') . '';

        $LookupResult = $xoopsDB->query($Query) || die('Error looking up Setup information ' . $GLOBALS['xoopsDB']->error());

        $foo = $xoopsDB->fetchArray($LookupResult);

        echo '<form action="index.php?op=setup" method="POST">
             Title: <input type="text" size="20" name="title" maxlength="255" value="' . $foo['title'] . '"><br>
             Next Image: <input type="text" size="20" name="next_image" maxlength="255" value="' . $foo['next_image'] . '"><br>
             Prev Image: <input type="text" size="20" name="prev_image" maxlength="255" value="' . $foo['prev_image'] . '"><br>
             Image size X: <input type="text" size="20" name="Image_size_x" maxlength="255" value="' . $foo['Image_size_x'] . '"> Set either X or Y to 0 to remove size limitation.<br>
             Image size Y: <input type="text" size="20" name="Image_size_y" maxlength="255" value="' . $foo['Image_size_y'] . '"><br>
             First Seen text: <input type="text" size="20" name="First_seen" maxlength="255" value="' . $foo['First_seen'] . '"><br>
             Last Seen text: <input type="text" size="20" name="Last_seen" maxlength="255" value="' . $foo['Last_seen'] . '"><br>
             <td><input type="submit" name="editsetup" value="Submit Changes">
             </form>';

        xoops_cp_footer();
    }
}

#################################################

#################################################

if (!isset($param_op)) {
    $param_op = 'menu';
}

switch ($param_op) {
    case 'menu':
        CastPage_Main();
        break;
    case 'newChapter':
        $httpdvars = $_POST;
        addChapter($httpdvars);
        break;
    case 'delChapter':
        $httpdvars = $_POST;
        delChapter($httpdvars);
        break;
    case 'editChapter':
        $httpdvars = $_POST;
        editChapter($httpdvars);
        break;
    case 'newSection':
        $httpdvars = $_POST;
        addSection($httpdvars);
        break;
    case 'delSection':
        $httpdvars = $_POST;
        delSection($httpdvars);
        break;
    case 'editSection':
        $httpdvars = $_POST;
        editSection($httpdvars);
        break;
    case 'newCastMember':
        $httpdvars = $_POST;
        addCast($httpdvars);
        break;
    case 'delCastMember':
        $httpdvars = $_POST;
        delCastMember($httpdvars);
        break;
    case 'editCastMember':
        $httpdvars = $_POST;
        editCastMember($param_castID, $httpdvars);
        break;
    case 'setup':
        $httpdvars = $_POST;
        setup($httpdvars);
        break;
    default:
        xoops_cp_header();
        print "<h1>Unknown method requested '$param_op' in admin/index.php</h1>";
        xoops_cp_footer();
}
