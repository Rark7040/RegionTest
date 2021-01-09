<?php

declare(strict_types = 1);


namespace rark7040\region_protector\listener;

use pocketmine\event\Event;
use pocketmine\level\Position;


function isNeedCancelled(Event $event, Positon $pos):bool{
	$manager = Main::getRegionManager();
	$region = $manager->getRegion($pos);

	if(!is_null($region)){
		$player = $event->getPlayer();
		$holder = $region->getHolder();

		if($region->issetUser($player->getName()) or $player->isOp()){

			if(($block->equals($region->getPos()) and $player->getName() !== $holder) or !$player->isOp()){
				return true;
			}
			return false;
		}

		if($region->isProtected()){
			return true;
		}
	}
	return falce;
}