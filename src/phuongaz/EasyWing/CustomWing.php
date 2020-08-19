<?php

namespace phuongaz\EasyWing;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

class CustomWing {

	/**@var int*/
	private $scale = 0.3;
	/** @var array */
	private $shape = [];
	/** @var array */
	private $vector3 = [];

	public function __construct(array $shape){
		$this->shape = $shape;
		$l1 = count($this->shape);
		for($y = 0; $y < $l1; $y++) {
			$l2 = count($this->shape[$y]);
			for($x = 0; $x < $l2; $x++) {
				$flag = $shape[$y][$x];
				if($flag == 0) continue;
				$kx = $x - (int) ($l2 / 2);
				$ky = ($y - (int) ($l1 / 2)) * (-1);
				$this->vector3[] = new Vector3($kx, $this->scale * $ky + 1.7);
			}
		}
	}

	/**
	* @param Position $pos
	* @param float $angle
	*/
	public function draw(Position $pos, float $angle) :void{
		$level = $pos->getLevel();
		$sin = sin(deg2rad($angle));
		$cos = cos(deg2rad($angle));
		foreach($this->vector3 as $vector3){
			$r = $this->scale * $vector3->x;
			$px = $r * $cos;			
			$pz = $r * $sin;
			$level->addParticle(Loader::getInstance()->parseWing($pos->add($px, $vector3->y, $pz), $flag));
		}
	}
}
