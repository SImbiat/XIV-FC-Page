<?php

# File to give source files into a zip and sent it to users

ignore_user_abort(true);

set_time_limit(320);

// ######################### REQUIRE BACK-END ############################

	$zipname = "src.zip";
	$zip = new ZipArchive;
	$zip->open("./".$zipname, ZipArchive::CREATE);
	$zip->addFile("api-autoloader.php");
	$zip->addFile("chardet.php");
	$zip->addFile("Chart.js");
	$zip->addFile("README");
	$zip->addFile("README.md");
	$zip->addFile("jquery-3.1.0.min.js");
	$zip->addFile("composer.json");
	$zip->addFile("defaults/config.php");
	$zip->addFile("defaults/fcranks.json");
	$zip->addFile("fcranks.php");
	$zip->addFile("functions.php");
	$zip->addFile("index.php");
	$zip->addFile("update.php");
	$zip->addFile("defaults/style.css");
	$zip->addFile("img/attention.png");
	$zip->addFile("img/delete.png");
	$zip->addFile("img/loading.gif");
	$zip->addFile("img/lvldown.png");
	$zip->addFile("img/lvlup.png");
	$zip->addFile("img/rankup.png");
	$zip->addFile("img/cities/Gridania.png");
	$zip->addFile("img/cities/LimsaLominsa.png");
	$zip->addFile("img/cities/Uldah.png");
	$zip->addFile("img/focus/Casual.png");
	$zip->addFile("img/focus/Dungeons.png");
	$zip->addFile("img/focus/Guildhests.png");
	$zip->addFile("img/focus/Hardcore.png");
	$zip->addFile("img/focus/Leveling.png");
	$zip->addFile("img/focus/PvP.png");
	$zip->addFile("img/focus/Raids.png");
	$zip->addFile("img/focus/Roleplaying.png");
	$zip->addFile("img/focus/Trials.png");
	$zip->addFile("img/grandcompany/ChiefFlameSergeant.png");
	$zip->addFile("img/grandcompany/ChiefSerpentSergeant.png");
	$zip->addFile("img/grandcompany/ChiefStormSergeant.png");
	$zip->addFile("img/grandcompany/FlameCorporal.png");
	$zip->addFile("img/grandcompany/FlamePrivateFirstClass.png");
	$zip->addFile("img/grandcompany/FlamePrivateSecondClass.png");
	$zip->addFile("img/grandcompany/FlamePrivateThirdClass.png");
	$zip->addFile("img/grandcompany/FlameSergeantFirstClass.png");
	$zip->addFile("img/grandcompany/FlameSergeantSecondClass.png");
	$zip->addFile("img/grandcompany/FlameSergeantThirdClass.png");
	$zip->addFile("img/grandcompany/Recruit.png");
	$zip->addFile("img/grandcompany/SecondFlameLieutenant.png");
	$zip->addFile("img/grandcompany/SecondSerpentLieutenant.png");
	$zip->addFile("img/grandcompany/SecondStormLieutenant.png");
	$zip->addFile("img/grandcompany/SerpentCorporal.png");
	$zip->addFile("img/grandcompany/SerpentPrivateFirstClass.png");
	$zip->addFile("img/grandcompany/SerpentPrivateSecondClass.png");
	$zip->addFile("img/grandcompany/SerpentPrivateThirdClass.png");
	$zip->addFile("img/grandcompany/SerpentSergeantFirstClass.png");
	$zip->addFile("img/grandcompany/SerpentSergeantSecondClass.png");
	$zip->addFile("img/grandcompany/SerpentSergeantThirdClass.png");
	$zip->addFile("img/grandcompany/StormCorporal.png");
	$zip->addFile("img/grandcompany/StormPrivateFirstClass.png");
	$zip->addFile("img/grandcompany/StormPrivateSecondClass.png");
	$zip->addFile("img/grandcompany/StormPrivateThirdClass.png");
	$zip->addFile("img/grandcompany/StormSergeantFirstClass.png");
	$zip->addFile("img/grandcompany/StormSergeantSecondClass.png");
	$zip->addFile("img/grandcompany/StormSergeantThirdClass.png");
	$zip->addFile("img/guardians/AlthyktheKeeper.png");
	$zip->addFile("img/guardians/AzeymatheWarden.png");
	$zip->addFile("img/guardians/ByregottheBuilder.png");
	$zip->addFile("img/guardians/HalonetheFury.png");
	$zip->addFile("img/guardians/LlymlaentheNavigator.png");
	$zip->addFile("img/guardians/MenphinatheLover.png");
	$zip->addFile("img/guardians/NaldthaltheTraders.png");
	$zip->addFile("img/guardians/NophicatheMatron.png");
	$zip->addFile("img/guardians/NymeiatheSpinner.png");
	$zip->addFile("img/guardians/OschontheWanderer.png");
	$zip->addFile("img/guardians/RhalgrtheDestroyer.png");
	$zip->addFile("img/guardians/ThaliaktheScholar.png");
	$zip->addFile("img/jobs/Alchemist.png");
	$zip->addFile("img/jobs/Arcanist.png");
	$zip->addFile("img/jobs/Archer.png");
	$zip->addFile("img/jobs/Armorer.png");
	$zip->addFile("img/jobs/Astrologian.png");
	$zip->addFile("img/jobs/Bard.png");
	$zip->addFile("img/jobs/BlackMage.png");
	$zip->addFile("img/jobs/Blacksmith.png");
	$zip->addFile("img/jobs/Botanist.png");
	$zip->addFile("img/jobs/Carpenter.png");
	$zip->addFile("img/jobs/Conjurer.png");
	$zip->addFile("img/jobs/Culinarian.png");
	$zip->addFile("img/jobs/DarkKnight.png");
	$zip->addFile("img/jobs/Dragoon.png");
	$zip->addFile("img/jobs/Fisher.png");
	$zip->addFile("img/jobs/Gladiator.png");
	$zip->addFile("img/jobs/Goldsmith.png");
	$zip->addFile("img/jobs/Lancer.png");
	$zip->addFile("img/jobs/Leatherworker.png");
	$zip->addFile("img/jobs/Machinist.png");
	$zip->addFile("img/jobs/Marauder.png");
	$zip->addFile("img/jobs/Miner.png");
	$zip->addFile("img/jobs/Monk.png");
	$zip->addFile("img/jobs/Ninja.png");
	$zip->addFile("img/jobs/Paladin.png");
	$zip->addFile("img/jobs/Pugilist.png");
	$zip->addFile("img/jobs/Rogue.png");
	$zip->addFile("img/jobs/Scholar.png");
	$zip->addFile("img/jobs/Summoner.png");
	$zip->addFile("img/jobs/Thaumaturge.png");
	$zip->addFile("img/jobs/Warrior.png");
	$zip->addFile("img/jobs/Weaver.png");
	$zip->addFile("img/jobs/WhiteMage.png");
	$zip->addFile("img/roles/Crafter.png");
	$zip->addFile("img/roles/DPS.png");
	$zip->addFile("img/roles/Gatherer.png");
	$zip->addFile("img/roles/Healer.png");
	$zip->addFile("img/roles/Tank.png");
	$zip->addFile("img/souls/Astrologian.png");
	$zip->addFile("img/souls/Bard.png");
	$zip->addFile("img/souls/BlackMage.png");
	$zip->addFile("img/souls/DarkKnight.png");
	$zip->addFile("img/souls/Dragoon.png");
	$zip->addFile("img/souls/Machinist.png");
	$zip->addFile("img/souls/Monk.png");
	$zip->addFile("img/souls/Ninja.png");
	$zip->addFile("img/souls/Paladin.png");
	$zip->addFile("img/souls/Scholar.png");
	$zip->addFile("img/souls/Summoner.png");
	$zip->addFile("img/souls/Warrior.png");
	$zip->addFile("img/souls/WhiteMage.png");
	$zip->addFile("img/fcranks/0.png");
	$zip->addFile("img/fcranks/1.png");
	$zip->addFile("img/fcranks/2.png");
	$zip->addFile("img/fcranks/3.png");
	$zip->addFile("img/fcranks/4.png");
	$zip->addFile("img/fcranks/5.png");
	$zip->addFile("img/fcranks/6.png");
	$zip->addFile("img/fcranks/7.png");
	$zip->addFile("img/fcranks/8.png");
	$zip->addFile("img/fcranks/9.png");
	$zip->addFile("img/fcranks/10.png");
	$zip->addFile("img/fcranks/11.png");
	$zip->addFile("img/fcranks/12.png");
	$zip->addFile("img/fcranks/13.png");
	$zip->addFile("img/fcranks/14.png");
	$zip->addFile("src/achievements.php");
	$zip->addFile("src/character.php");
	$zip->addFile("src/data.php");
	$zip->addFile("src/freecompany.php");
	$zip->addFile("src/funky.php");
	$zip->addFile("src/lodestoneapi.js");
	$zip->addFile("src/lodestoneapi.php");
	$zip->addFile("src/parse.php");
	$zip->addFile("src/parser.php");
	$zip->addFile("src/search.php");
	$zip->addFile("src/urls.php");
	$zip->addFile("src/xivdb.php");
	$zip->close();
	$filename="./src.zip";
	if (file_exists($filename)) {
		$basename = basename($filename);
		$length   = sprintf("%u", filesize($filename));
		if ( isset($_SERVER['HTTP_RANGE']) ) {
			$partialContent = true;
			preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
			$offset = intval($matches[1]);
			$length = intval($matches[2]) - $offset;
		} else {
			$partialContent = false;
		}
		if ( $partialContent ) {
			header('HTTP/1.1 206 Partial Content');
			header('Content-Range: bytes ' . $offset . '-' . ($offset + $length) . '/' . $filesize);
		}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $basename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . $length);
		header('Accept-Ranges: bytes');
		set_time_limit(0);
		if ($fd = fopen ($filename, "r")) {
			fseek($fd, $offset);
			while(!feof($fd)) {
				$buffer = fread($fd, 1024);
				echo $buffer;
			}
			fclose($fd);
		}
	} else {
		require_once('./css.php');
		Echo "Failed to prepare zip!";
	}

?>