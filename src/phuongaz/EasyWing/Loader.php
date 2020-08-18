<?php

namespace phuongaz\EasyWing;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;

Class Loader extends PluginBase{

	public function onEnable() :void{
		$this->getScheduler()->scheduleRepeatingTask(new WingTask($this), 1);
	}
}