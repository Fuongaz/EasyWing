<?php

namespace phuongaz\EasyWing\task;

use pocketmine\scheduler\Task;
use phuongaz\EasyWing\CustomWing;

Class WingTask extends Task{

	private CustomWing $wing;

	public function __construct(CustomWing $wing){
		$this->wing = $wing;
	}

	public function onRun(int $currentTick){
		if($this->wing->getPlayer() == null){
			$this->getHandler()->cancel();
		}
		$this->wing->draw();
	}
}
