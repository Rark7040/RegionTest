<?php

declare(strict_types = 1);


namespace rark7040\region_protector;

use pocketmine\event\Listener;
use pocketmine\event\player\{
	PlayerBlockPickEvent,
	PlayerInteractEvent,
};


class PlayerEventListener implements Listener{

	public function onBlockPick(PlayerBlockPickEvent $event):void{
		if(isNeedCancelled($event, $event->getBlock())){
			$event->setCancelled();
			return;
		}
	}

	public function onInteract(PlayerInteractEvent $event):void{
		if(isNeedCancelled($event, $event->getBlock())){
			$event->setCancelled();
			return;
		}
	}
}