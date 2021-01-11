<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\custom;

use pocketmine\Player;
use rark7040\region_protector\form\BaseForm;
use function strval;


abstract class CustomForm extends BaseForm{

	abstract protected function customFormHandler(Player $player, array $data):void;

	protected function setLabel(string $text):void{
		$this->contents[] = [
			'type' => 'label',
			'text' => $text
		];
	}

	protected function setDropdown(string $text, array $data, int $default = 0):void{
		$this->contents[] = [
			'type' => 'dropdown',
            'text' => $text,
            'options' => $data,
            'default' => $default
        ];
	}

	protected function setInput(string $text, string $back = '', string $default = ''):void{
		$this->contents[] = [
            'type' => 'input',
            'text' => $text,
            'placeholder' => $back,
            'default' => $default
        ];
	}

	protected function setSlider(string $text, int $min, int $max, int $default = 0):void{
		$this->contents[] = [
        	'type' => 'slider',
        	'text' => $text,
        	'min' => $min,
        	'max' => $max,
        	'default' => $default
        ];
	}

	protected function setStepSlider(string $text, array $data, $default = 0):void{
		$str_data = [];

		foreach($data as $value) {
			$str_data[] = strval($value);
		}
		$this->contents[] = [
            'type' => 'step_slider',
            'text' => $text,
            'steps' => $str_data,
            'default' => $default
        ];
	}

	protected function setToggle(string $text, bool $default = false){
		$this->contents[] = [
            'type' => 'toggle',
            'text' => $text,
            'default' => $default
        ];
	}


	protected function getFormData():array{
		return [
			'type' => 'custom_form',
			'title' => $this->title,
			'contents' => $this->contents,
		];
	}

	protected function formHandler(Player $player, $data):void{
		$this->customFormHandler($player, $data);
	}
}