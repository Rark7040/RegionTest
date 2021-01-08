<?php

declare(strict_types = 1);


namespace rark7040\region_protector;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use rark7040\region_protector\listener\{PlayerEventListener, BlockEventListener}
use rark7040\region_protector\task\RegionTask;


final class Main extends PluginBase{

	private const CONFIG_SETTING = 'config.setting';
	private const CONFIG_REGION = 'config.region';

	private $configs = [];
	private $server = null;
	private $manager = null;

	public function onEnable(){
		$this->server = $this->getServer();
		$this->createConfig();
		$this->setSchedule();
		$this->registerListener();
		$this->registerCommand();
		$this->addItem();
		$this->manager = new RegionManager($this->configs[self::CONFIG_REGION]);
		return;
	}

	public function onDisable(){
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

	private function createConfig():void{
		$path = $this->getDataFolder();
		$defautlData = [
			'id' => 247,
			'meta' => 0
			'price' => 10000,
			'update' => 50000,
			'amount' => 3
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
			new PlayerEventListener(),
			new BlockEventListener()
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

	private function addItem():void{
		$config = $this->configs[self::CONFIG_SETTING];
		$id = $config['id'];
		$meta = $config['meta'];
		$item = Item::get($id, $meta);
		$item->setCustomName('RegionBlock');
		Item::addCreativeItem($item);
		return;
	}

}