<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form;

use pocketmine\form\Form;
use pocketmine\Player;

abstract class BaseForm implements Form{

	abstract public function getFormData():array;

	abstract public function formHandler(Player $player, $data):void;

	public function jsonSerialize(){
		return $this->getFormData();
	}

	public function handleResponse(Player $player, $data):void{
		$this->handle($player, $data);
	}
}