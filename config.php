<?php
#Some initial global settings
ini_set('output_buffering', 0);
ini_set('implicit_flush', 1);
ob_end_flush();
ob_start();
$fcpage = "";

#ModRewrite setting. Whether to use it to provide neater links. Change to "false" if you
#do not have it enabled on server or do not want to use it for some reason
$modrw = true;

#Shadow style for marking avatars on search hit
$avashadow="0px 0px 10px #ffd700, 0 0 1em #ffd700";
?>