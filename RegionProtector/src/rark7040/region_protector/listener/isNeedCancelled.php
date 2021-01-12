<?php

declare(strict_types = 1);


namespace rark7040\region_protector\listener;

use pocketmine\Player;
use pocketmine\level\Position;
use rark7040\region_protector\Main;


function isNeedCancelled(Player $plaeyr, Positon $pos):bool{
	$manager = Main::getRegionManager();
	$region = $manager->getRegion($pos);

	if(!is_null($region)){
		$holder = $region->getHolder();

		if($region->issetUser($player->getName()) or $player->isOp()){

			if(($pos->equals($region->getPos()) and $player->getName() !== $holder) or !$player->isOp()){
				return true;
			}
			return false;
		}

		if($region->isProtected()){
			return true;
		}
	}
	return false;
}