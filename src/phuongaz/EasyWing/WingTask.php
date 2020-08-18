<?php

namespace phuongaz\EasyWing;

use pocketmine\scheduler\Task;
use pocketmine\item\Item;
use pocketmine\Player;

Class WingTask extends Task{

	/** @var Loader*/
	private $loader;

	public function __construct(Loader $loader){
		$this->loader = $loader;
	}

	/**
	* @param int $currentTick
	*/
	public function onRun(int $currentTask){
		foreach($this->loader->getServer()->getOnlinePlayers() as $player){
			$wing = new CustomWing();
			$wing->draw($player, $player->yaw);
		}
	}
}