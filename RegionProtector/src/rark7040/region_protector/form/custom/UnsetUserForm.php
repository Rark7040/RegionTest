<?php

declare(strict_types = 1);

namespace rark7040\region_protector\form;

use pocketmine\Player;
use rark7040\region_protector\Main;
use rark7040\region_protector\region\Region;
use rark7040\region_protector\form\simple\{MenuForm, MessageForm};


class UnsetUserForm extends CustomForm{
	
	private $region = null;

	public function __construct(Region $region){
		$this->setDropdown('誰を利用者から削除しますか？'.$this->region->getUsers());
		$this->region = $region;
	}
	
	protected function customFormHandler(Player $player, array $data):void{
		$selected = $this->region->getUsers()[$data[0]];
		$this->region->removeUser();
		Main::getRegionManager()->updateRegion($this->region);
		$player->sendForm(new MessageForm($selected.'さんを利用者から削除しました'), new MenuForm($player, $this->region));
	}
}