<?php

declare(strict_types = 1);

namespace rark7040\region_protector\form\simple;

use pocketmine\Player;
use rark7040\region_protector\region\Region;


final class EditUsersForm extends SimpleForm{
	
	private $region = null;

	public function __construct(Region $region){
		$this->title = '利用者の編集';
		$this->label = '何をしますか？';
		$this->setButton('<<Back');
		$this->setButton('利用者の追加');
		$this->setButton('利用者の削除');
		$this->region = $region;
	}

	protected function simpleFormHandler(Player $player, int $data):void{

		switch($data){

			case 0:
				$player->sendForm(new MenuForm($player, $this->region));
			break;

			case 1:
				$player->sendForm(new AddUserForm($this->region));
			break;

			case 2:
				$player->sendForm(new UnsetUserForm($this->region));
			break;
		}
	}
}