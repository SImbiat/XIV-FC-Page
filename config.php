<?php
#Some initial global settings
ini_set('output_buffering', 0);
ini_set('implicit_flush', 1);
ob_end_flush();
ob_start();
$fcpage = "";

#Free Company ID
$fcid = "9234631035923213559";

#Rank level to be treated as the one for inactive person
$lazy = 6;
#Minimum (or default) rank level
$defrank = 5;
#Time required to be in the above rank to be suggested for a kick in seconds
$lazytime = 5184000;
#Time to ignore rank checks if member is lazy in seconds
$lazyover = 2592000;

#Time to show rank up\down overlay in seconds
$rankotime = 604799;
#Limit the time between rank changes in seconds
$rankchage = 1296000;

#Life of cache in seconds
$cachelife = 43200;
#$cachelife = 1;

#Settings for forcing update of cached images by type
#If require you can always just remove the files as well
$emblemupd = false;
$ranksupd = false;

#Extra styling (outside) of main .css file
#Number of members to be shown on one line
$memonline = 13;
#Shadow style for hoverable links (activates on hover)
$hovershadow = "0px 0px 5px #ffff99, 0 0 1em #ffff99";
#Shadow style for marking avatars on search hit
$avashadow="0px 0px 10px #ffd700, 0 0 1em #ffd700";
#Style of company name. Use for font styling ONLY
$fcnamecss="font-size:80px;color:gold;font-weight: bold;";
#Style of server name. Use for font styling ONLY
$serverncss="color:green;";
#Style of company foundation date. Use for font styling ONLY
$formeddate="color:darkblue;";
#Style of grand company names. Use for font styling ONLY
$gctwinadder="color:darkorange;";
$gcmaelstorm="color:red;";
$gcimmortalflames="color:brown;";
#Style of estate names. Use for font styling ONLY
$estatename="color:darkgreen;";
#Style of estate address. Use for font styling ONLY
$estateaddress="font-size:xx-small;vertical-align:sub;";
#Style of ranking number. Use for font styling ONLY
$ranknum="color:darkviolet;font-weight: bold;";
#Style of max and min ranking number. Use for font styling ONLY
$rankmax="font-size:xx-small;vertical-align:sub;";
#Style of members number. Use for font styling ONLY
$membercount="color:gold;";
#Style of member tag. Use for font styling ONLY
$membertag="color:darkblue;";
?>