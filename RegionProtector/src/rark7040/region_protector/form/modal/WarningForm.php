<?php

declare(strict_types = 1);

namespace rark7040\region_protector\form;

use pocketmine\Player;
use rark7040\region_protector\form\BaseForm;


final class WarningForm extends ModalForm{

	private $previous_form = null;
	private $next_form = null;
	
	public function __construct(BaseForm $pervious, BaseForm $next){
		$this->title = 'WARNING';
		$this->label = <<<LABEL
			この操作によって発生したトラブルはすべて自己責任となります。
			それでも操作を続行しますか？
		LABEL;
		$this->setButton(true, 'はい');
		$this->setButton(false, 'いいえ');
		$this->previous_form = $pervious;
		$this->next_form = $next;
	}

	protected function modalFormHandler(Player $player, bool $data):void{

		if($data){
			$player->sendForm($this->next_form);
			
		}else{
			$player->sendForm($this->previous_form);
		}
	}
}