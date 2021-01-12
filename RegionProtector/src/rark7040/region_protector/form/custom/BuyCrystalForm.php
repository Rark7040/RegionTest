<?php

declare(strict_types = 1);

namespace rark7040\region_protector\form;

use pocketmine\Player;
use rark7040\region_protector\Main;
use rark7040\region_protector\region\Region;
use onebone\economyapi\EconomyAPI;


class BuyCrystal extends CustomForm{

	private $player = null;
	private $config = Main::getConfig();
	
	public function __construct(Player $player){
		$this->player = $player;
		$this->createContents();
	}

	private function createContents():void{
		$manager = Main::getRegionManager();
		$has_crystal_amount = $manager->getHasRegions($this->player);
		$max = $this->config->get('amount') - $has_crystal_amount;
		$max = $max <= 0? 0:$max;
		$step_slider = range(0, $max);
		$this->setStepSlider('購入数を選択してください(一個：$'.$this->config->get('price').')', $step_slider);
	}

	protected function customFormHandler(Player $player, array $data){
		$api = EconomyAPI::getInstance();
		$price = $this->config->get('price');
		$amount = intval($data[0]);
		$inventory = $player->getInventory();
		$item = Main::getItem();

		if($api->canReduceMoney($player, $price * $amount) and $inventory->canAddItem($item)){
			$api->reduceMoney($player, $price);
			$messae = 'リージョンクリスタルの購入に成功しました';

			for(; $amount !== 0; $amount--){
				$inventory->addItem($item);
			}

		}else{
			$message = '所持金が不足しているか、インベントリに空きがありません';
		}
		$player->sendMessage($message);
	}
}