<?php

declare(strict_types = 1);


namespace b\region;

use pocketmine\math\Vector2;
use pocketmine\level\{Level, Position};


class Coordinate extends Vector2{


	public $level = null;


	public function __construct($x, float $y = 0, Level $level = null){

		if($x instanceof Position){
			$y = $pos->z;
			$level = $pos->level;
			$x = $pos->x;
		}
		parent::__construct($x, $y);
		$this->level = $level;
	}

	public function asVector2():Vector2{
		return new Vector2($this->x, $this->y);
	}

	public function isEqualLevel(Coordinate $cor):bool{

		if(!is_null($this->level) and !is_null($cor->level)){
			return $this->level->getName() === $cor->getLevel()->getName();
		}
		return false;
	}

	public function equals(Coordinate $cor):bool{
		return $this->x == $cor->x and $this->y == $cor->y;
	}

	public function equalPos(Position $pos):bool{
		return $this->equals(new Coordinate($pos));
	}
}