<?php

declare(strict_types = 1);


namespace rark7040\region_protector\region;

use pocketmine\level\Position;
use pocketmine\{Player, Server};
use pocketmine\utils\Config;
use function floor;

 
class RegionManager{

	public const SUCCES_REGISTER = 0;
	public const ERROR_NAME_USED = 1;
	public const ERROR_REGION_DUPLICATE = 2;

	private $data = null;
	private $activeRegions = [];

	/*
		土地の保存データを取得
		保存されている土地内の座標を読み込み
	*/
	public function __construct(Config $regionData){
		$this->data = $regionData;
		$this->load();
	}

	/*
		指定された土地を登録
	*/
	public function registerRegion(Region $region):int{

		if($this->isRegisteredRegion($region)){
			$region->setName($region->getName().mt_rand(0, 9999));
			$this->registerRegion($region);
			return self::ERROR_NAME_USED;

		}elseif($this->isDuplicate($region)){
			return self::ERROR_REGION_DUPLICATE;
		}

		$this->data->set($region->getName(), $this->conversionToArray($region));
		$this->data->save();
		return self::SUCCES_REGISTER;
	}

	/*
		指定された土地を削除
	*/
	public function unregisterRegion(Region $region):bool{
		$name = $region->getName();

		if($this->data->__isset($name)){
			$this->setActive($region, fasle);
			$this->data->__unset($name);
			$this->data->save();
			return true;
		}
		return false;
	}

	public function isRegisteredRegion(Region $region):bool{
		return in_array($region->getName(), $this->data->getAll(), true);
	}

	public function updateRegion(Region $region):bool{

		if($this->isRegisteredRegion($region)){
			$this->data->set($region->getName(), $this->conversionToArray($region));
			$this->data->save();
			return true;
		}
		return false;
	}

	/*
		指定した土地の保護を有効化・無効化
	*/
	public function setActive(Region $region, bool $value = true):void{
		$bool = $this->isActive($region);

		if($value and !$bool){
			$activeRegions[$region->getName()] = $region;

		}elseif($bool){
			$regions = array_diff($this->activeRegions, [$region->getName()]);
			$this->activeRegions = array_values($regions);
		}
	}

	/*
		土地が有効化されているか
	*/
	public function isActive(Region $region):bool{
		return in_array($region->getName(), $this->activeRegions(), true);
	}

	public function getAllRegions():array{
		$regions = [];

		foreach($this->data->getAll() as $name => $regionData){
			$redions[] = $this->conversionToRegion($regionData);
		}
		return $regions;
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

		$regions = $this->getAllRegions();

		foreach($regions as $region){
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

	/*
		土地がほかの土地と重なっているか
	*/
	private function isDuplicate(Region $region):bool{
		$regions = $this->getAllRegions();

		foreach($regions as $reg){
			$cor1 = new Coordinate($reg->getPos());
			$cor2 = new Coordinate($region->getPos());

			if($cor1->isEqualLevel($cor2)){

				if($cor1->distance($cor2) <= $reg->getDistance() or $cor2->distance($cor1) <= $region->getDistacne()){
					return true;
				}
			}
		}
		return false;
	}

	private function load():void{
		$regions = $this->getAllRegions();

		foreach($regions as $region){
			$this->setActive($region);
		}
	}
}