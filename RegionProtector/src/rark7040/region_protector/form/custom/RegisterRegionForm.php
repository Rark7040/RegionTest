<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\custom;

use pocketmine\Player;
use pocketmine\level\Position;
use rark7040\region_protector\Main;
use rark7040\region_protector\region\Region;


final class RegisterRegionForm extends CustomForm{

	private $pos = null;
	private $crystal = null;

	public function __construct(Position $pos){
		$this->pos = $pos;
		$this->createContents();
	}

	private function createContents():void{
		$this->setInput('領域の名前');
		$this->setToggle('保護の有効', true);
	}

	protected function customFormhandler(Player $player, array $data){
		$manager = Main::getRegionManager();
		$region = new Region($data[0], $this->pos);
		$region->setHolder($player->getName());
		$region->setProtect($data[1]);
		$manager->registerRegion($region);
		$player->sendMessage('領域の保存に成功しました');
	}
}