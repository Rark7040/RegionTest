<?php

declare(strict_types = 1);


namespace rark7040\region_protector;

use rark7040\region_protector\Main;
use pocketmine\event\Listener;
use pocketmine\event\level\{
	LevelLoadEvent,
	LevelUnloadEvent
};


class LevelEventListener implements Listener{

	private $manager = null;

	public function __construct(){
		$this->manager = Main::getRegionManager();
		return;
	}

	public function onLoad(LevelLoadEvent $event):void{//手抜きです許してください！　何でもしますから！
		$level = $event->getLevel();
		$regions = $this->manager->getAll();

		foreach($regions as $region){

			if($region->getLevel()->getName() === $level->getName() and !$this->manager->isActive($region)){
				$this->manager->setActive($region);
			}
		}
		return;
	}

	public function onUnload(LevelUnloadEvent $event):void{
		$level = $event->getLevel();
		$regions = $this->manager->getAll();

		foreach($regions as $region){

			if($region->getLevel()->getName() === $level->getName() and $this->manager->isActive($region)){
				$this->manager->setActive($region, false);
			}
		}
		return;
	}
}