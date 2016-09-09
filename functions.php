<?php
//Function to show level of a class and show that it changed, if it did
function showcurlvl($levels, $class, $curtime, $lvlupdate, $lvlchange, $span) {
	$lvlret = "";
	if ($span == true) {
		$lvlret = "<span title=\"Came to us at level ".$levels['init'][$class];
	} else {
		$lvlret = "Came to us at level ".$levels['init'][$class];
	}
	if ($levels['curr'][$class] > $levels['init'][$class]) {
		$lvlret = $lvlret . " and last record shows growth to ".$levels['curr'][$class];
		if ($levels['curr'][$class] > $levels['prev'][$class] && $levels['prev'][$class] > $levels['init'][$class]) {
			$lvlret = $lvlret . " (with previous to last record showing level ".$levels['prev'][$class].")";
		}
		if ($span == true) {
			$lvlret = $lvlret . "\"";
		}
	} else {
		$lvlret = $lvlret . " and no change has been recorded since";
		if ($span == true) {
			$lvlret = $lvlret . "\"";
		}
	}
	if ($span == true && $levels['curr'][$class] > $levels['prev'][$class] && ($curtime - $lvlupdate) / 86400 <= $lvlchange) {
		$lvlret = $lvlret . " style=\"background-image:url(img/lvlup.png);background-size:contain;box-shadow:0px 0px 3px #000000;\"";
	}
	if ($span == true) {
		$lvlret = $lvlret . ">".$levels['curr'][$class]."</span>";
	}
	return $lvlret;
}

//Function is used to determine lowest (maximum) available value in weekly ranking
function maxValueInArray($array, $keyToSearch) {
    $currentMax = NULL;
    foreach($array as $arr)
    {
        foreach($arr as $key => $value)
        {
            if ($key == $keyToSearch && ($value >= $currentMax))
            {
                $currentMax = $value;
            }
        }
    }

    return $currentMax;
}

//Function is used to determine highest (minimum) available value in weekly ranking
function minValueInArray($array, $keyToSearch) {
    $currentMin = 99999999;
    foreach($array as $arr)
    {
        foreach($arr as $key => $value)
        {
            if ($key == $keyToSearch && ($value <= $currentMin))
            {
                $currentMin = $value;
            }
        }
    }

    return $currentMin;
}

//Function used to check if member has enough class diversity
function classdivcheck($tank, $dps, $healer, $gatherer, $crafter, $classes) {
	$classcount = 0;
	if ($tank > 1) {
		$classcount = $classcount + 1;
	}
	if ($dps > 1) {
		$classcount = $classcount + 1;
	}
	if ($healer > 1) {
		$classcount = $classcount + 1;
	}
	if ($gatherer > 1) {
		$classcount = $classcount + 1;
	}
	if ($crafter > 1) {
		$classcount = $classcount + 1;
	}
	if ($classcount >= $classes) {
		return true;
	} else {
		return false;
	}
}

//Function to use proper forms of he\she depending on sex of a member
function sexcheck($sex) {
	if ($sex == "female") {
		$out[0] = "she";
		$out[1] = "her";
		$out[2] = "her";
		$out[3] = "herself";	
		return $out;
	} elseif ($sex == "male") {
		$out[0] = "he";
		$out[1] = "him";
		$out[2] = "his";
		$out[3] = "himself";	
		return $out;
	}
}

//Functions used to download images
function checkRemoteFile($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}
function imgcaching($imgurl, $newname, $update) {
	if (strpos($imgurl, "?")) {
		$imgurl = substr($imgurl, 0, strpos($imgurl, "?"));
	}
	$ext = pathinfo($imgurl, PATHINFO_EXTENSION);
	if (!file_exists("./cache/".$newname.".".$ext) || ($update == true && time()-filemtime("./cache/ffxiv_fcpage.txt") > 86400)) {
		if (checkRemoteFile($imgurl) === true) {
			set_time_limit(180);
			$content = file_get_contents($imgurl);
			$fp = fopen("./cache/".$newname.".".$ext, "w");
			fwrite($fp, $content);
			fclose($fp);
		}
	}
	if (file_exists("./cache/".$newname.".".$ext)) {
		return "cache/".$newname.".".$ext;
	} else {
		return $imgurl;
	}
}

//Function to sanitize image files' names
function imgnamesane($imgname) {
	return preg_replace("/[^A-Za-z0-9]/", '', $imgname);
}

//Function to create missing directories
function misdircreate() {
	if (!is_dir("./cache")) {
		mkdir("./cache");
	}
	if (!is_dir("./cache/fc")) {
		mkdir("./cache/fc");
	}
	if (!is_dir("./cache/members")) {
		mkdir("./cache/members");
	}
	if (!is_dir("./cache/ranking")) {
		mkdir("./cache/ranking");
	}
	if (!is_dir("./cache/ranks")) {
		mkdir("./cache/ranks");
	}
}

//Function to create or update FC ranking file and return last 10 results
function getlastranks($curfcrank, $fcid) {
	if (file_exists("./cache/ranking/".$fcid.".json")) {
		$lastranks=json_decode(file_get_contents("./cache/ranking/".$fcid.".json"), true);
		if (end($lastranks)['rank'] != $curfcrank) {
			array_push($lastranks, array("date"=>time(), "rank"=>$curfcrank));
			file_put_contents("./cache/ranking/".$fcid.".json", json_encode($lastranks, JSON_PRETTY_PRINT));
		}
	} else {
		$lastranks=[];
		array_push($lastranks, array("date"=>time(), "rank"=>$curfcrank));
		file_put_contents("./cache/ranking/".$fcid.".json", json_encode($lastranks, JSON_PRETTY_PRINT));
	}
	return array_slice($lastranks, -10, 10, true);
}
?>