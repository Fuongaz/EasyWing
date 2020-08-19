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

	public function __construct(array $shape){
		$this->shape = $shape;
	}
	/**
	* @param Position $pos
	* @param float $angle
	*/
	public function draw(Position $pos, float $angle) :void{
		$level = $pos->getLevel();
		$l1 = count($this->shape);
		$sin = sin(deg2rad($angle));
		$cos = cos(deg2rad($angle));
		for($y = 0; $y < $l1; $y++) {
			$l2 = count($this->shape[$y]);
			for($x = 0; $x < $l2; $x++) {
				$flag = $this->shape[$y][$x];
				if($flag == 0) continue;
				$kx = $x - (int) ($l2 / 2);
				$ky = $y - (int) ($l1 / 2);
				$ky = $y * (-1);
				$r = $this->scale * $kx;
				$px = $r * $cos;
				$py = $this->scale * $ky + 1.7;
				$pz = $r * $sin;
				$vec = $pos->add($px, $py, $pz);
				$particle = Loader::getInstance()->parseWing($vec, $flag);
				$level->addParticle($particle);
			}
		}
	}
}
