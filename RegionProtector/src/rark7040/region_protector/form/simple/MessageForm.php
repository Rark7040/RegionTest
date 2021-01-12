<?php

declare(strict_types = 1);


namespace rark7040\region_protector\form\simple;

use pocketmine\Player;
use rark7040\region_protector\form\BaseForm;


final class MessageForm extends SimpleForm{

	private $previous_form = null;

	public function __construct(string $text, BaseForm $previous){
		$this->title = 'メッセージ';
		$this->label = $reason;
		$this->setButton('<<Back');
		$this->previous_form = $previous;
	}

	protected function simpleFormHandler(Player $player, int $data):void{
		$player->sendForm($this->previous_form);
	}
}