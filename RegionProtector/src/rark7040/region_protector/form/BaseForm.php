<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form;

use pocketmine\form\Form;
use pocketmine\Player;

abstract class BaseForm implements Form{

	protected const TYPE_OP = 0;
	protected const TYPE_HOLDER = 1;
	protected const TYPE_USER = 2;

	protected $title = '';
	protected $labal = '';
	protected $contents = [];
	protected $viewer = null;
	protected $viewer_type = null;

	abstract protected function getFormData():array;

	abstract protected function formHandler(Player $player, $data):void;


	protected function setTitle(string $title){
		$this->title = $title;
	}

	protected function setLabel(string $label):void{
		$this->label = $label;
	}

	public function jsonSerialize(){
		return $this->getFormData();
	}

	public function handleResponse(Player $player, $data):void{

		if(is_null($data)){
			return;
		}
		$this->formHandler($player, $data);
	}
}