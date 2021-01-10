<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\simple;

use pocketmine\Player;
use rark7040\region_protector\region\Region;
use rark7040\region_protector\form\simple\{
	RegionDataForm,
	ViewUsersForm,
	EditRedionForm
};
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
				$this->setButton('領域を編集する');
			break;

			case $this->region->issetUser($player->getName()):

				if($region->isHolder($player->getName())){
					$this->viewer_type = self::TYPE_HOLDER;
					$this->setButton('<<Back');
					$this->setButton('情報を見る');
					$this->setButton('保護範囲を拡大する');
					$this->setButton('領域を編集する');
					$this->setButton('保護範囲を移動する');
					$this->setButton('領域を譲渡する');
					return;
				}
				$this->viewer_type = self::TYPE_USER;
				$this->setButton('<<Back');
				$this->setButton('情報を見る');
				$this->setButton('共同利用者を確認する');
			break;
		}
	}

	public function simpleFormHandler(Player $player, int $data):void{
		$this->viewer = $player;

		if($data === 0){
			$viewer->sendForm(new RegionDataForm($this->region));

		}elseif($data === 1){
			return;
		}

		switch($this->viewer_type){
			case self::TYPE_OP:
				$this->opProcess($data);
			break;

			case self::TYPE_HOLDER:
				$this->holderProcess($data);
			break;

			case self::TYPE_USER:
				$this->viewer->sendForm(new ViewUsersForm($this->region));
			break;
		}
	}

	private function opProcess(int $data):void{
		
		switch($data){
			case 2;
				$this->viewer->sendForm(new ChangeHolderForm($this->region));
			break;

			case 3:
				$this->viewer->sendForm(new EditRegionForm($this->region));
			break;
		}
	}

	private function holderProcess(int $data):void{

		switch($data){
			case 2;
				$this->viewer->sendForm(new SpreadRegionForm($this->region));
			break;

			case 3:
				$this->viewer->sendForm(new EditRegionForm($this->region));
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