<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\modal;

use pocketmine\Player;
use rark7040\region_protector\form\BaseForm;


abstract class ModalForm extends BaseForm{

	abstract protected function modalFormHandler(Player $player, ?bool $data):void;

	protected function setButton(bool $value, string $text):void{
		$this->contents[$value] = $text;
	}

	protected function getFormData():array{
		return [
			'type' => 'modal',
			'title' => $this->title,
			'contents' => $this->label,
			'button1' => $this->contents[true],
			'button2' => $this->contents[false]
		];
	}

	protected function formHandler(Player $player, $data):void{
		$this->modalFormHandler($player, $data);
	}
}