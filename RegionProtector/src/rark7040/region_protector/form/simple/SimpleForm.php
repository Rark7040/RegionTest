<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\simple;

use pocketmine\form\Form;


abstract class SimpleForm extends BaseForm{

	private $label = '';

	abstract public function simpleFormHandler(Player $player, int $data):void;

	protected function setTitle(string $title):void{
		$this->title = $title;
	}

	protected function setLabel(string $label):void{
		$this->label = $label;
	}

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