<?php

declare(strict_types = 1);


namespace b\region;

use pocketmine\level\Position;
use pocketmine\{Player, Server};
use pocketmine\utils\Config;


class RegionManager{

	private $data = null;
	private $activeRegions = [];
	private $vectors = [];

	public function __construct(Config $regionData){
		$this->data = $regionData;
		$this->loadVector();
		return;
	}

	public function registerRegion(Region $region):void{

		if($this->isUsedRegionName($region->getName())){
			$region->setName($region->getName().mt_rand(0, 9999));
			$this->registerRegion($region);
			return;
		}
		$this->data->set($region->getName(), $this->conversionToArray($region));
		$this->data->save();
		return;
	}

	public function setActive(Region $region, bool $value = true):void{

		if($value and !$this->isActive($region)){
			$activeRegions[$region->getName()] = $region;
			return;

		}elseif($this->isActive($region)){
			$regions = array_diff($this->activeRegions, [$region->getName()]);
			$this->activeRegions = array_values($regions);
			return;
		}
	}

	public function isActive(Region $region):bool{
		return in_array($region->getName(), $this->activeRegions(), true);
	}

	public function isUsedRegionName(string $regionName):bool{
		return in_array($regionName, $this->data->getAll(), true);
	}

	public function getRegion(Position $pos):?Region{

		foreach($this->vectors as $regionName => $positions){

			foreach($positions as $position){

				if($pos->equals($position) and $pos->level->getName() === $position->level->getName()){
					return $this->getRegionByName($regionName);
				}
			}
		}
		return null;
	}

	public function getRegionByName(string $regionName):?Region{

		if($this->data->__isset($regionName)){
			return $this->data->get($regionName);
		}

		return null;
	}

	public function getRegionExact(Position $pos):?Region{

		foreach($this->activeRegions as $name => $region){
			$regionPos = $region->getPos();
			$cor = new Coordinate($regionPos->x, $regionPos->z, $regionPos->level);

			if($cor->distance(new Coordinate($pos)) <= $region->getDistacne()){
					return $region;
			}
		}
		return null;
	}

	public function isInRegion(Position $pos):bool{
		return !is_null($this->getRegion($pos));
	}

	public function isInRegionExact(Position $pos):bool{
		return !is_null($this->getRegionExact($pos));
	}

	private function loadVector():void{

		foreach($this->activeRegions as $name => $region){
			$pos = $region->getPos();
			$regionDistance = $region->getDistance();
			$pos1 = $this->floorPos($pos->add(-$regionDistance, 0, -$regionDistacne));
			$pos2 = $this->floorPos($pos->add($regionDistacne, 0, $regionDistance));
			$pos2->y = 255;

			for(;; $pos1->x +=1){
				
				if($this->isInRegion()){
					$this->vectors[$region->getName()][] = new Coordinate($pos1);
				}

				if($pos1->x === $pos2->x){
					$pos1->z += 1;
					$pos1->x -= $regionDistance;

					if($pos1->z === $pos2->z){
						break;
					}
				}
			}
		}
		
	
	}

	private function conversionToArray(Region $region):array{
		$pos = $region->getPos();
		
		return [
			'NAME' => $region->getName(),
			'HOLDER' => $region->getHolder(),
			'USERS' => $region->getUsers(),
			'POS' => [
				'X' => $pos->x,
				'Y' => $pos->y,
				'Z' => $pos->z,
				'LEVEL' => $pos->level->getName(),
			],
			'DISTANCE' => $region->getDistance(),
			'PROTECT' => $region->isProtected(),
		];
	}

	private function conversionToRegion(array $regionData):Region{
		$server = Server::getInstance();
		$posData = $regionData['POS'];
		$x = $posData['X'];
		$y = $posData['Y'];
		$z = $posData['Z'];
		$level = $server->getLevelByName($posData['LEVEL']);
		$pos = new Position($x, $y, $z, $level);
		$region = new Region($regionData['NAME'], $pos);
		$region->setHolder(Server::getPlayer($regionData['HOLDER']));
		$region->setUsers($regionData['USERS']);
		$region->setDistance($regionData['DISTANCE']);
		$region->setProtect($regionData['PROTECT']);
		return $region;
	}

	private function floorPos(Position $pos):Position{
		$x = floor($pos->x);
		$y = floor($pos->y);
		$z = floor($pos->z);
		return new Position($x, $y, $z, $pos->level);
	}
}