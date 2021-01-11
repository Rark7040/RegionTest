<?php

declare(strict_types = 1);

namespace rark7040\region_protector\form;

use pocketmine\{Server, Player};
use rark7040\region_protector\Main;
use rark7040\region_protector\region\Region;
use rark7040\region_protector\form\simple\MessageForm;


final class AddUserForm extends CustomForm{
	
	private $region = null;
	private $onlines = null;
	private $online_names = null;

	public function __construct(Region $region){
		$this->createContents();
		$this->onlines = Server::getInstance()->getOnlinePlayers();
		$this->region = $region;
	}

	private function createContents():void{
		$names = [];

		foreach($this->onlines as $player){
			$names[] = $player->getName();
		}
		$this->setDropdown('誰を追加しますか？', $names);
		$this->online_names = $names;
	}

	protected function customFormHandler(Player $player, array $data):void{
		$selected = $this->online_names[$data];

		if($this->region->issetUser($selected)){
			$messae = $selected'さんは既に登録されています';

		}else{
			$this->region->addUser($selected);
			Main::getRegionManager()->updateRegion($this->region);
			$messae = $selected.'さんの利用者への登録が完了しました';
			} 
		}

		$player->sendForm(new MessageForm($message, $this));
	}
}