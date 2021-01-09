<?php

declare(strict_types = 1);


namespace rark7040\region_protector\region;

use pocketmine\level\Position;


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
	}

	public function setName(string $name):void{
		$this->name = $name;
	}

 	public function getName():string{
 		return $this->name;
 	}

 	public function setHolder(string $holderName):void{
 		$this->holder = $holder;
 		$this->user[] = $holder;
 	}

 	public function getHolder():string{
 		return $this->holder;
 	}

 	public function isHolder(string $holderName):bool{
 		return $holderName === $this->holder;
 	}

 	public function addUser(string $userName):void{
 		$this->users[] = $userName;
 	}

 	public function removeUser(string $userName):bool{

 		if($this->issetUser($userName)){
 			$users = array_diff($this->users, [$userName]);
 			$this->users = array_values($users);
 			return true;

 		}
 		return false;
 	}

 	public function issetUser(string $userName):bool{
 		return in_array($userName, $this->users, true);
 	}

 	public function getUsers():array{
 		return $this->users;
 	}

 	public function setUsers(array $users):void{
 		$this->users = $users;
 	}

 	public function setPos(Position $pos):void{
 		$this->pos1 = $pos;
 	}

 	public function getPos():Position{
 		return $this->pos;
 	}

 	public function setDistance(float $distance):void{
 		$this->distance = $distance;
 	}

 	public function getDistance():float{
 		return $this->distacne;
 	}

 	public function setProtect(bool $value):void{
 		$this->protect = $value;
 	}

 	public function isProtected():bool{
 		return $this->protect;
 	}
}