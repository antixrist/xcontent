<?php
include_once (MODX_ASSETS_PATH .'components/xcontent/xcontent.config.php');

/* @var modX $modx */
/* @var modResource $resource */

//read text
$content = $modx->resource->get('content');
// check config
// $disable = $modx->resource->getTVValue(23);
$disable = false;
if(!isset($REG) || $disable) { print $content; return '';}
$arr = explode("\n", $content);

// Settings
define(DEBUG, 0);
define(SKIP_LINES, 0);
define(LINKS_LIMIT, 4);


if(DEBUG) print "<pre>";
// Начинаем разбор
$links=0;
$str_num=1;
foreach($arr as $str)
{
    $res=0;
    $i=0;

    // Пропускаем строки уже содержащие ссылки или картинки
    $skip=0;
    if(preg_match('/<a.*?>/', $str)) $skip=1;
    if(preg_match('/<img.*?>/', $str)) $skip=1;

    // Также пропускаем первую строку и если лимит ссылок уже достигнут
    if($links < LINKS_LIMIT && !$skip && $str_num>SKIP_LINES)
    {
        foreach($REG as $reg)
        {
            $res = preg_match($reg, $str, $match);
            if($res==0) {  $i++; continue; }

            if(DEBUG) print "\n -------------------- \n(i=$i) "."Str: ".$str."\n";;

            if($res)
            {
                if(DEBUG) print "$links) Found: ".$match[1]."\n";

                // Replace
                //$tag = "<a href='$LINK[$i]'>$match[1]</a>".$match[2];
                $tag = "<a href='$LINK[$i]'>$match[1]</a>";
                $str = preg_replace($REG[$i], $tag, $str, 1);
            }
            $links++;
            if(DEBUG) print "links = $links\n";
            break;
        }
    }

    if(DEBUG) print ">> ";
    //print $str."\n";
    print $str;
    $str_num++;
}
/**/
if(DEBUG) print "</pre>";
