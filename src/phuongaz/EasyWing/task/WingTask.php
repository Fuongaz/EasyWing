<?php

namespace phuongaz\EasyWing\task;

use pocketmine\scheduler\Task;
use pocketmine\item\Item;
use pocketmine\Player;

use phuongaz\EasyWing\CustomWing;

Class WingTask extends Task{

	/** @var CustomWing */
	private CustomWing $wing;

	public function __construct(private CustomWing $wing){}

	/**
	* @param int $currentTick
	*/
	public function onRun(int $currentTick){
		if($this->wing->getPlayer() == null){
			$this->getHandler()->cancel();
		}
		$this->wing->draw();
	}
}
