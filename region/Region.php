<?php

declare(strict_types = 1);


namespace b\region;

use pocketmine\level\Position;
use b\Core;
use b\log\Logger;


class Region{

	private $name = null;
	private $holder = null;
	private $users = [];
	private $pos = null;
	private $distance = null;
	private $protect = false;

	public function __construct(string $name, Position $pos){
		$this->name = $name;
		$this->pos = $pos;
		$this->distacne = 10;
		$this->protect = false;
		return;
	}

	public function setName(string $name):void{
		$this->name = $name;
		return;
	}

 	public function getName():string{
 		return $this->name;
 	}

 	public function setHolder(string $holderName):void{
 		$this->holder = $holder;
 	}

 	public function getHolder():string{
 		return $this->holder;
 	}

 	public function addUser(string $userName):void{
 		$this->users[] = $userName;
 		return;
 	}

 	public function removeUser(string $userName):bool{

 		if($this->issetUser($userName)){
 			$users = array_diff($this->users, [$userName]);
 			$this->users = array_values($users);
 			return true;

 		}else{
 			Core::getLogger()->report(null, Logger::REASON_ERROR, '土地のUSERに除する対象がいません{'.$this->name.'}['.$userName.']');
 			return false;
 		}
 	}

 	public function issetUser(string $userName):bool{
 		return in_array($userName, $this->users, true);
 	}

 	public function getUsers():array{
 		return $this->users;
 	}

 	public function setUsers(array $users):void{
 		$this->users = $users;
 		return;
 	}

 	public function setPos(Position $pos):void{
 		$this->pos1 = $pos;
 		return;
 	}

 	public function getPos():Position{
 		return $this->pos;
 	}

 	public function setDistance(float $distance):void{
 		$this->distance = $distance;
 		return;
 	}

 	public function getDistance():float{
 		return $this->distacne;
 	}

 	public function setProtect(bool $value):void{
 		$this->protect = $value;
 		return;
 	}

 	public function isProtected():bool{
 		return $this->protect;
 	}
}