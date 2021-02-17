<?php

namespace phuongaz\EasyWing\task;

use pocketmine\scheduler\Task;
use pocketmine\item\Item;
use pocketmine\Player;

use phuongaz\EasyWing\CustomWing;

Class WingTask extends Task{

	/** @var Player*/
	private $player;
	/** @var CustomWing */
	private $wing;

	public function __construct(Player $player, CustomWing $wing){
		$this->player = $player;
		$this->wing = $wing;
	}

	/**
	* @param int $currentTick
	*/
	public function onRun(int $currentTick){
		$player = $this->player;
		$this->wing->draw($player, $player->yaw);
	}
}
