<?php

namespace phuongaz\EasyWing\task;

use pocketmine\scheduler\Task;
use pocketmine\item\Item;
use pocketmine\Player;

use phuongaz\EasyWing\CustomWing;

Class WingTask extends Task{

	/** @var Player*/
	private $player;
	/** @var array */
	private $shape = [];

	public function __construct(Player $player, array $shape){
		$this->player = $player;
		$this->shape = $shape;
	}

	/**
	* @param int $currentTick
	*/
	public function onRun(int $currentTask){
		$player = $this->player;
		$wing = new CustomWing($this->shape);
		$wing->draw($player, $player->yaw);
	}
}