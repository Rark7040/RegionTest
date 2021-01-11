<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\simple;

use pocketmine\Player;
use rark7040\region_protector\form\BaseForm;


abstract class SimpleForm extends BaseForm{

	abstract protected function simpleFormHandler(Player $player, int $data):void;

	protected function setButton(string $text, string $icon = null):void{
		$this->contents[] = ['text' => $text, 'data' => $icon];
	}

	protected function getFormData():array{
		return [
			'type' => 'form',
			'title' => $this->title,
			'contents' => $this->label,
			'buttons' => $this->contents
		];
	}

	protected function formHandler(Player $player, $data):void{
		$this->simpleFormHandler($player, $data);
	}
}