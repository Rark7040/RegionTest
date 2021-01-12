<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\custom;

use pocketmine\Player;
use rark7040\region_protector\region\Region;
use rark7040\region_protector\form\modal\WarningForm;
use rark7040\region_protector\form\simple\{MenuForm, DeletedRegionForm};


final class UnregisterRegionForm extends CustomForm{

	public function __construct(Region $region){
		$this->region->region;
		$this->setToggle('この操作を続行する場合は、ONにして送信してください');
	}

	protected function customFormHandler(Player $player, array $data):void{

		if($data[0]){
			$player->sendForm(new WarningForm(new MenuForm($player, $this->region), new DeletedRegionForm($this->region)));
		}
	}
}