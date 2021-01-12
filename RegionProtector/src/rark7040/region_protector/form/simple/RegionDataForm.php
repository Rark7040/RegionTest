<?php

declare(strict_types = 1);

namespace rark7040\region_protector\form\simple;

use pocketmine\Player;
use rark7040\region_protector\region\Region;


final class RegionDataForm extends SimpleForm{
	
	private $region = null;

	public function __construct(Region $region){
		$this->title = 'データ';
		$this->region = $region;
		$this->createLabel();
		$this->setButton('<<Back');
	}

	private function createLabel():void{
		$name = '領域名>> '.$this->region->getName()."\n";
		$holder = '所有者>> '.$this->region->getHolder()."\n";
		$users = '利用者>>';
		
		foreach($this->region->getUsers() as $userName){
			if($userName !== $this->region->getHolder()){
				$users .= "\n・".$userName;
			}
		}
		$distance = "\n保護範囲>> 半径".$this->region->getDistance()."m\n\n";

		$this->label = $name.$holder.$users.$distance;
	}

	protected function simpleFormHandler(Player $player, int $data):void{
		$player->sendForm(new MenuForm($player, $this->region));
	}
}