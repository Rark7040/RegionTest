<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\simple;

use pocketmine\Player;
use rark7040\region_protector\Main;
use rark7040\region_protector\region\Region;
use rark7040\region_protector\form\modal\SpreadRegionForm;
use rark7040\region_protector\form\custom\{
	ChangeHolderForm,
	MoveRegionForm
};

final class MenuForm extends SimpleForm{

	private $region = null;

	public function __construct(Player $player, Region $region){
		$this->title = 'RegionMenu';
		$this->label = '行動を選択してください';
		$this->region = $region;
		$this->createButtons($player);
	}

	private function createButtons(Player $player):void{
		
		switch(true){
			case $player->isOp:
				$this->viewer_type = self::TYPE_OP;
				$this->region('<<Back');
				$this->setButton('情報を見る');
				$this->setButton('所有者を変更する');
			break;

			case $this->region->issetUser($player->getName()):

				if($region->isHolder($player->getName())){
					$this->viewer_type = self::TYPE_HOLDER;
					$this->setButton('<<Back');
					$this->setButton('情報を見る');
					$this->setButton('利用者を編集する');
					$this->setButton('保護範囲を拡大する');
					$this->setButton('保護範囲を移動する');
					$this->setButton('領域を譲渡する');
					return;
				}
				$this->viewer_type = self::TYPE_USER;
				$this->setButton('<<Back');
				$this->setButton('情報を見る');
			break;
		}
	}

	protected function simpleFormHandler(Player $player, int $data):void{
		$this->viewer = $player;

		if($data === 0){
			$viewer->sendForm(new RegionDataForm($this->region));

		}elseif($data === 1){
			return;
		}

		switch($this->viewer_type){
			case self::TYPE_OP:
				$this->viewer->sendForm(new ChangeHolderForm($this->region));
			break;

			case self::TYPE_HOLDER:
				$this->holderProcess($data);
			break;
		}
	}

	private function holderProcess(int $data):void{

		switch($data){
			case 2;
				$this->viewer->sendForm(new EditUsersForm($this->region));
			break;

			case 3:
				$form = $region->getDistance() <= Main::getConfig()->get('max_distance')?
					new SpreadRegionForm($this->region):
					new MessageForm('これ以上領域を拡張できません', $this);
				$this->viewer->sendForm($form);
			break;

			case 4:
				$this->viewer->sendForm(new MoveRegionForm($this->region));
			break;

			case 5:
				$this->viewer->sendForm(new ChangeHolderForm($this->region));
			break;
		}
	}
}