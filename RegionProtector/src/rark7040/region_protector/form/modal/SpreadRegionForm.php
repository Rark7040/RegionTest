<?php

declare(strict_types = 1);

namespace rark7040\region_protector\form;

use pocketmine\Player;
use rark7040\region_protector\region\Region;
use rark7040\region_protector\form\simple\{MenuForm, MessageForm};
use rark7040\region_protector\Main;
use onebone\economyapi\EconomyAPI;


final class SpreadRegionForm extends ModalForm{
	
	private $player = null;
	private $region = null;
	private $api = null;
	private $price = null;
	private $can_reduce = false;

	public function __construct(Player $player, Region $region){
		$this->title = '領域の拡張';
		$this->api = EconomyAPI::getInstance();
		$this->player = $player;
		$this->region = $region;
		$this->price = Main::getConfig()->get('update');
		$this->createLabel();
		$this->setButton(true, '拡張する');
		$this->setButton(false, '戻る');
	}

	private function createLabel():void{
		
		if($this->api->canReduceMoney($this->player, $this->price)){
			$this->can_reduce = true;
			$section = "§a";

		}else{
			$section = "§c";
		}
		$base_text = "この領域の保護範囲を拡大します。\n\n";
		$distance = '現在の範囲>> 半径'.$this->region->getDistance()."m\n";
		$money_head = '所持金>> $';
		$money = $this->api->myMoney($this->player)."\n\n";
		$this->label = $base_text.$distance.$money_head.$section.$money;
	}

	protected function modalFormHandler(Player $player, bool $data):void{
		
		if($data){

			if(!$this->can_reduce){
				return;
			}

			$manager = Main::getRegionManager();
			$distance = $region->getDistance();
			$this->region->setDistance($new_distance++);
			$this->api->reduceMoney($player, $this->price);
			$manager->updateRegion($this->region);
			$player->sendForm(new MessageForm('領域を拡張しました'));

		}else{
			$player->sendForm(new MenuForm($player, $this->region));
		}
	}
}