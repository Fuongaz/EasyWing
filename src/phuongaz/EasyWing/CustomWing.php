<?php

namespace phuongaz\EasyWing;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

class CustomWing {

	/**@var float*/
	private float $scale = 0.3;
	/** @var array */
	private array $shape = [];
	/** @var array */
	private array $vector3 = [];

	public function __construct(
		private Player $player
		private array $shape,
		private float $scale){
		$l1 = count($this->shape);
		for($y = 0; $y < $l1; $y++) {
			$l2 = count($this->shape[$y]);
			for($x = 0; $x < $l2; $x++) {
				$flag = $shape[$y][$x];
				if($flag == 0) continue;
				$kx = $x - (int) ($l2 / 2);
				$ky = ($y - (int) ($l1 / 2)) * (-1);
				$this->vector3[] = [new Vector3($kx, $this->scale * $ky + 1.7), $flag];
			}
		}
	}
	/**
	* @return float
	*/
	public function getScale(): float{
		return $this->scale;
	}

	/**
	* @return null|Player
	*/
	public function getPlayer() :?Player{
		return $this->player;
	}

	/**
	* Draw wing
	*/
	public function draw() :void{
		$player = $this->getPlayer();
		$angle = $player->yaw;
		$level = $player->getLevel();
		$sin = sin(deg2rad($angle));
		$cos = cos(deg2rad($angle));
		foreach($this->vector3 as $data){
			$r = $this->getScale() * $data[0]->x;
			$px = $r * $cos;			
			$pz = $r * $sin;
			$level->addParticle(Loader::getInstance()->parseWing($player->add($px, $data[0]->y, $pz), $data[1]));
		}
	}
}
