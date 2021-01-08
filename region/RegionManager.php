<?php

declare(strict_types = 1);


namespace b\region;

use pocketmine\level\Position;
use pocketmine\{Player, Server};
use pocketmine\utils\Config;
use b\Core;
use b\log\Logger;


class RegionManager{

	private $data = null;
	private $activeRegions = [];

	/*
		土地の保存データを取得
		保存されている土地内の座標を読み込み
	*/
	public function __construct(Config $regionData){
		$this->data = $regionData;
		return;
	}

	/*
		指定された土地を登録
	*/
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

	/*
		指定された土地を削除
	*/
	public function unregisterRegion(Region $region):void{
		$name = $region->getName();

		if($this->data->__isset($name)){
			$this->data->__unset($name);
			return;
		}
		Core::getLogger()->report(null, Logger::REASON_ERROR, '削除しようとした土地は存在しません{'.$name.'}');
		return;
	}

	/*
		指定した土地の保護を有効化・無効化
	*/
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

	/*
		土地が有効化されているか
	*/
	public function isActive(Region $region):bool{
		return in_array($region->getName(), $this->activeRegions(), true);
	}

	/*
		土地の名前が既に使われているか
	*/
	public function isUsedRegionName(string $regionName):bool{
		return in_array($regionName, $this->data->getAll(), true);
	}

	/*
		名前から土地を取得
	*/
	public function getRegionByName(string $regionName):?Region{

		if($this->data->__isset($regionName)){
			return $this->conversionToRegion($this->data->get($regionName));
		}
		return null;
	}

	/*
		指定した領域にある土地を取得
	*/
	public function getRegion(Position $pos):?Region{

		foreach($this->activeRegions as $name => $region){
			$regionPos = $region->getPos();
			$cor = new Coordinate($regionPos);

			if($cor->distance(new Coordinate($pos)) <= $region->getDistacne()){
				if($cor->isEqualLevel(new Coordinate($pos))){
					return $region;
				}
			}
		}
		return null;
	}

	/*
		指定した場所に土地が設定されているか
	*/
	public function isInRegion(Position $pos):bool{
		return !is_null($this->getRegion($pos));
	}

	/*
		土地から配列のデータを取得
	*/
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

	/*
		配列から土地を取得
	*/
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

	/*
		ポジションの値を整数化
	*/
	private function floorPos(Position $pos):Position{
		$x = floor($pos->x);
		$y = floor($pos->y);
		$z = floor($pos->z);
		return new Position($x, $y, $z, $pos->level);
	}
}