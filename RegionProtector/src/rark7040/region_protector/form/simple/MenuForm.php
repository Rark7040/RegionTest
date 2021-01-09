<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\simple;

use pocketmine\Player;
use rark7040\region_protector\region\Region;

class MenuForm extends SimpleForm{

	private const TITLE = 'RegionMenu';
	private const LABEL = '行動を選択してください';
	private const OP = 0;
	private const HOLDER = 1;
	private const USER = 2;

	private $manager = null;
	private $region = null;
	private $viewer_type = false;
	private $viewer = null;

	public function __construct(Player $player, Region $region){
		parent::__construct(self::TITLE, self::LABEL);
		$this->manager = Main::getRegionManager();
		$this->region = $region;
		$this->createButtons($player);
	}

	private function createButtons(Player $player):void{
		
		switch(true){
			case $player->isOp:
				$this->viewer_type = self::OP;
				$this->region('<<Back');
				$this->setButton('情報を見る');
				$this->setButton('所有者を変更する');
				$this->setButton('保護を解除する');
			break;

			case $this->region->issetUser($player->getName()):

				if($region->isHolder($player->getName())){
					$this->viewer_type = self::HOLDER;
					$this->setButton('<<Back');
					$this->setButton('情報を見る');
					$this->setButton('保護範囲を拡大する');
					$this->setButton('領域を編集する');
					$this->setButton('保護範囲を移動する');
					$this->setButton('領域を譲渡する');
					return;
				}
				$this->viewer_type = self::USER;
				$this->setButton('<<Back');
				$this->setButton('情報を見る');
				$this->setButton('共同利用者を確認する');
			break;
		}
	}

	public function formHandler(Player $player, int $data):void{
		$this->viewer = $player;

		if($data === 0){
			$viewer->sendForm(new RegionDataForm());

		}elseif($data === 1){
			return;
		}

		switch($this->viewer_type){
			case self::OP:
				$this->opProcess($data);
			break;

			case self::HOLDER:
				$this->holderProcess($data);
			break;

			case self::USER:
				$this->userProcess($data);
			break;
		}
	}

	private function opProcess(int $data):void{
		
		switch($data){
			case 2;
				$this->viewer->sendForm(new ChangeHolderForm());
			break;

			case 3:
				$this->viewer->sendForm(new UnprotectedForm());
			break;
		}
	}

	private function holderProcess(int $data):void{

		switch($data){
			case 2;
				$this->viewer->sendForm(new SpreadRegionForm($this->viewer));
			break;

			case 3:
				$this->viewer->sendForm(new EditRegionForm());
			break;

			case 4:
				$this->viewer->sendForm(new MoveRegionForm());
			break;

			case 5:
				$this->viewer->sendForm(new ChangeHolderForm());
			break;
		}
	}

	private function userProcess(int $data):void{
		
	}


}