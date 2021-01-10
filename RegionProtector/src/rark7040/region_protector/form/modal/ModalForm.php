<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\modal;


abstract class ModalForm extends BaseForm{

	protected $labal = '';

	abstract protected function modalFormHandler(Player $player, int $data):void;

	protected function setTitle(string $title){
		$this->title = $title;
	}

	protected function setText(string $label):void{
		$this->label = $label;
	}

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