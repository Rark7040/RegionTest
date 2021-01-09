<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\simple;

use pocketmine\form\Form;


abstract class SimpleForm extends BaseForm{

	protected $title = null;
	protected $label = null;
	protected $buttons = [];

	public function __construct(string $title, string $label){
		$this->title = $title;
		$this->label = $label;
	}

	public function setButton(string $text, string $icon = null):void{
		$this->buttons[] = ['text' => $text, 'data' => $icon];
	}

	public function getFormData():array{
		return [
			'title' => $this->title,
			'contents' => $this->label,
			'buttons' => $this->buttons
		];
	}
}