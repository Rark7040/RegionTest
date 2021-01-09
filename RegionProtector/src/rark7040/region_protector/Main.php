<?php

declare(strict_types = 1);
/*
	！警告！：うんこーどです
			 プラグイン開発の参考になる保証はありません
*/
namespace rark7040\region_protector;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\entity\Entity;
use rark7040\region_protector\listener\{
	PlayerEventListener,
	BlockEventListener,
	LevelEventListener
};
use rark7040\region_protector\task\RegionTask;
use rark7040\region_protector\entity\RegionCrystal;


final class Main extends PluginBase{

	private const CONFIG_SETTING = 'config.setting';
	private const CONFIG_REGION = 'config.region';

	private $configs = [];
	private $server = null;
	private $manager = null;
	private $item = null;

	public function onEnable(){
		$this->server = $this->getServer();
		$this->createConfig();
		$this->setSchedule();
		$this->registerListener();
		$this->registerCommand();
		$this->setEntity();
		$this->addItem();
		$this->manager = new RegionManager($this->configs[self::CONFIG_REGION]);
		return;
	}

	public function onDisable(){
		Item::removeCreativeItem($this->item);

		foreach($this->configs as $key => $config){
			$config->save();
		}
		return;
	}

	public static function getRegionManager():RegionManager{
		return $this->manager;
	}

	public static function getConfig():Config{
		return $this->configs[self::CONFIG_SETTING];
	}

	public static function registerRegionCrystal(RegionCrystal $crystal):void{
		$this->crystals[$crystal->region->getName()] = $crystal;
		return;
	}

	public static function unregisterRegionCrystal():void{
		$crystals = $
	}

	private function createConfig():void{
		$path = $this->getDataFolder();
		$defautlData = [
			'price' => 10000,
			'update' => 50000,
			'amount' => 3,
			'max_distance' => 50
		];
		$this->config[self::CONFIG_SETTING] = new Config($path.'Config.yaml', Config::YAML, $defaultData);
		$this->configs[self::CONFIG_REGION] = new Config($path.'Regions.yaml', Config::YAML);
		return;
	}

	private function setSchedule():void{
		$this->getScheduler()->scheduleRepeatingTask(new RegionTask(), 1);
		return;
	}

	private function registerListener():void{
		$listeners = [
			new PlayerEventListener,
			new BlockEventListener,
			new LevelEventListener
		];

		foreach($listeners as $listener){
			$this->server->getPluginManager()->registerEvents($listener, $this);
		}
		return;
	}

	private function registerCommand():void{
		$this->server->getCommandMap()->registerAll('RegionProtector', [


		]);
	}

	private function setEntity():void{
		Entity::registerEntity(RegionCrystal::class, false, ['RegionCrystal', 'plugin::region_crystal']);
	}

	private function addItem():void{
		$config = $this->configs[self::CONFIG_SETTING];
		$this->item = Item::get(426);
		$this->item->setCustomName('RegionCrystal');
		Item::addCreativeItem($this->item);
		return;
	}

}