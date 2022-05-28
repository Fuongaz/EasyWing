<?php

declare(strict_types=1);

namespace phuongaz\EasyWing\task;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;
use phuongaz\EasyWing\CustomWing;

Class WingTask extends Task{

	public function __construct(
		private Player $player,
		private CustomWing $wing){}

	public function getWing() :CustomWing{
		return $this->wing;
	}

	public function getPlayer() :?Player{
		return $this->player;
	}

	public function onRun() :void{
		if($this->getPlayer() == null){
			$this->getHandler()->cancel();
		}
		$this->getWing()->draw($this->getPlayer()->getLocation());
	}
}
