<?php

declare(strict_types = 1);


namespace rark7040\region_protector\listener;

use pocketmine\event\Listener;
use pocketmine\event\block\{
	BlockBreakEvent,
	BlockPlaceEvent
};


class BlockEventListener implements Listener{

	public function onBreak(BlockBreakEvent $event):void{

		if(isNeedCancelled($event, $event->getBlock())){
			$event->setCancelled();
		}
	}

	public function onPlace(BlockPlaceEvent $event):void{

		if(isNeedCancelled($event, $event->getBlock())){
			$event->setCancelled();
		}
	}
}