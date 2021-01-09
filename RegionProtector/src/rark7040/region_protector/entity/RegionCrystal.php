<?php

declare(strict_types = 1);


namespace rark7040\region_protector\entity;

use pocketmine\entity\Entity;
use pocketmine\{Server, Player};
use pocketmine\nbt\tag\{ListTag, DoubleTag};
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\Position;
use rark7040\region_protector\Main;
use rark7040\region_protector\region\Region;
use const M_PI;

class RegionCrystal extends Entity{

	public const NETWORK_ID = self::ENDER_CRYSTAL;
	public $region = null;
	public $height = 2;

	public function __construct(Region $region){
		$this->region = $region;
		$this->namedtag->setString('id', $this->getSaveId(), true);
		$this->namedtag->setString('CustomName', $this->getName());
		$this->nametag->setByte('CustomNameVisible', 1);
		$pos = $region->getPos();
		$nbt = new ListTag('Pos', [
			new DoubleTag('', $pos->x),
			new DoubleTag('', $pos->y),
			new DoubleTag('', $pos->z)
		]);
		parent::__construct($pos->level, $nbt);
		Main::registerRegionCrystal($this);
		$this->spawnToAll();
	}

	public function getName():string{
		return 'RegionCrystal';
	}

	public function kill():void{
		Main::getRegionManager()->unregisterRegion($this->region);
		Main::unregisterRegionCrystal($this);
		$this->teleport($this->add(0, -255, 0));
		$this->health = 0;
		$this->scheduleUpdate();
	}

	public function entityBaseTick(int $tick = 10):bool{
		$hadUpdate = parent::entityBaseTick($tick);
		$manager = Main::getRegionManager();
		$this->teleport($this->region->getPos());

		if($this->region->isProtedted() or !$manager->isActive($this->region)){
			return $hasUpdate;
		}
		$players = [];

		foreach(Server::getInstance()->getOnlinePlayers() as $player){

			if(!$this->region->issetUser($player->getName())){

				if($player->getLevel()->getName() === $this->region->getPos()->level->getName()){

					if($this->distance($player) <= $this->region->getDistance()){
						$players[] = $player;
					}
				}
			}
		}
		$this->atackAction($players);
		return $hasUpdate;
	}

	private function attackAction(array $player):void{
		$pos = $this->region->getPos();
	
		for($d = 0; $d <= $this->regon->getDistance(); $d += 3){

			for($i = 0; $i <= 360; $i += 3){
				$x = $i * cos($d) * M_PI;
				$z = $i * sin($d) * M_PI;
				$pos->add($x, 0, $z);
				$pos->level->addParticle(new ExplodeParticle($pos));
			}
		}

		foreach($players as $player){
			$event = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_MAGIC, 0, [], 3);
			$event->call();
		}
	}
}