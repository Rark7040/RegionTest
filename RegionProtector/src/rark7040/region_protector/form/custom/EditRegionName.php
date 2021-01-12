<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\custom;

use pocketmine\Player;
use rark7040\region_protector\Main;
use rark7040\region_protector\region\Region;


final class EditRegionNameForm extends CustomForm{

	private $region = null;

	public function __construct(Region $region){
		$this->region = $region;
		$this->createContents();
	}

	private function createContents():void{
		$this->setInput('領域の名前', '', $this->region->getName());
		$this->setToggle('保護の有効', true);
	}

	protected function customFormHandler(Player $player, array $data):void{
		$manager = Main::getRegionManager();

		if(!$manager->unregisterRegion($this->region)){
			$player->sendMessage('§cデータベースにこの領域の情報が存在しません');
		}
		$this->region->setName($data[0]);
		$this->region->setProtect($data[1]);

		switch($manager->registerRegion($this->region)){
			case 0:
				$player->sendMessage('領域の更新に成功しました');
			break;

			case 2:
				$player->sendMessage('この領域の更新中に他の領域が奇跡的なタイミングで生成されてしまい、領域を保存できませんでした。申し訳ございません');
			break;
		}
		return;
	}
}