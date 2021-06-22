<?php

namespace phuongaz\EasyWing;

use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;
class CustomWing {

	private Player $player;
	private float $scale = 0.3;
	private array $shape = [];
	private array $vector3 = [];

	public function __construct(Player $player, array $shape, float $scale){
		$this->player = $player;
		$this->shape = $shape;
		$this->scale = $scale;
	}

	public function getScale(): float{
		return $this->scale;
	}

	public function getPlayer() :?Player{
		return $this->player;
	}

	public function draw() :void{
		$player = $this->getPlayer();
		$loc = clone $player->getLocation();
		$space = $this->getScale();
		$defX = $x = $loc->getX() - $space * count($this->shape[0]) / 2 + $space / 2;
		$y = $loc->getY() + 2.8;
		$angle = -(($loc->getYaw() + 180) / 60);
		$angle += (($loc->getYaw() < -180) ? 3.25 : 2.985);
		for($i = 0; $i < count($this->shape); ++$i){
			for($j = 0; $j < count($this->shape[$i]); ++$j){
				if($this->shape[$i][$j] != 0){
					$target = clone $loc;
					$target->x = $x;
					$target->y = $y;
					$v2 = $this->getBackVector($loc);
					$v = $this->rotateAroundAxisY($target->subtract($loc->add(-0.5, 0, 0.35)), $angle);
					$iT = $i / 18.0;
					$v2->y = 0;
					$newVec = $v->add($v2->multiply(-0.2 - $iT));
					$newLoc = $newVec->add($loc);
					for($k = 0; $k < 3; ++$k){
						$particle = Loader::getInstance()->parseWing($newLoc, $this->shape[$i][$j]);
						$player->getLevel()->addParticle($particle);
					}
				}
				$x += $space;
			}
			$y -= $space;
			$x = $defX;
		}
	}

	public function getBackVector(Location $loc): Vector3{
		$newZ = (float)($loc->getZ() + 0.75 * sin($loc->getYaw() * M_PI / 180));
		$newX = (float)($loc->getX() + 0.75 * cos($loc->getYaw() * M_PI / 180));

		return new Vector3($newX - $loc->getX(), $loc->getY(), $newZ - $loc->getZ());
	}

	public function rotateAroundAxisY(Vector3 $v, float $angle): Vector3{
		$cos = cos($angle);
		$sin = sin($angle);
		$x = $v->getX() * $cos + $v->getZ() * $sin;
		$z = $v->getX() * -$sin + $v->getZ() * $cos;

		return $v->setComponents($x, $v->getY(), $z);
	}
}
