<?php

declare(strict_tyeps = 1);


namespace rark7040\region_protector\listener;

use pocketmine\event\Listener;
use pocketmine\event\block\{
	BlockBreakEvent,
	BlockPlaceEvent,
	BlockBurnEvent
};


class BlockEventListener implements Listener{

	private $config = null;
	private $manager = null;

	public function __construct(){
		$this->config = Main::getConfig();
		$this->manager = Main::getRegionManager();
		return;
	}

	public function onBreak(BlockBreakEvent $event):void{
		$block = $event->getBlock();

	}

}