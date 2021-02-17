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

	public function __construct(Player $player, array $shape, int $scale = 0.3){
		$this->player = $player;
		$this->wing = new CustomWing($shape, $scale);
	}

	/**
	* @param int $currentTick
	*/
	public function onRun(int $currentTask){
		$player = $this->player;
		$this->wing->draw($player, $player->yaw);
	}
}
