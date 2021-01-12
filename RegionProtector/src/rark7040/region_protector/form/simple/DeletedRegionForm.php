<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\simple;

use pocketmine\Player;
use rark7040\region_protector\Main;
use rark7040\region_protector\region\Region;


final class DeletedForm extends SimpleForm{

	private $region = null;
	private $text = 

	public function __construct(Region $region){
		$this->text = $this->region->getName().'を削除しました';
		$this->title = '削除が完了しました';
		$this->label = $this->text;
		$this->deleteRegion();
	}

	private function deleteReigon():void{
		$manager = Main::getRegionManager();
		$manager->unregisterRegion($this->region);
	}

	protected function simpleFormHandler(Player $player, int $data):void{
		$player->sendMessage($this->text);
	}
}
