<?php

$modversion['name'] = 'CastPage';
$modversion['version'] = 1.00;
$modversion['description'] = 'WebComic Cast Page with Chapters';
$modversion['credits'] = '';
$modversion['author'] = 'Mathew Anderson';
$modversion['help'] = 'docs/help.html';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'images/castpage.png';
$modversion['dirname'] = 'castpage';

# SQL file
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

# Tables created by sql (without prefix!)
$modversion['tables'][] = 'CastPage';
$modversion['tables'][] = 'CastPage_Chapters';
$modversion['tables'][] = 'CastPage_Sections';
$modversion['tables'][] = 'CastPage_Setup';

# Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

# Main contents
$modversion['hasMain'] = 1;
