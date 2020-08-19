<?php

namespace phuongaz\EasyWing;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use phuongaz\EasyWing\task\WingTask;
use phuongaz\EasyWing\command\WingsCommand;

use pocketmine\level\particle\{
	DustParticle,
	FlameParticle,
	RedstoneParticle,
	Particle
};
use pocketmine\math\Vector3;

Class Loader extends PluginBase{

	/** @var array */
	private static $equip_players = [];
	/** @var array */
	private static $wings = [];
	/** @var self */
	private static $instance;

	public function onEnable() :void{
		$this->saveResource("wings/example.yml");
		foreach(glob($this->getDataFolder(). "wings/*.yml") as $wingPath){
			$wingName = pathinfo($wingPath, PATHINFO_FILENAME);
			self::$wings[$wingName] = yaml_parse(file_get_contents($this->getDataFolder(). "wings/".$wingName.".yml"));
		}
		self::$instance = $this;
		$this->getServer()->getCommandMap()->register("wings", new WingsCommand());
	}

	/**
	* @return self
	*/
	public static function getInstance() :self{
		return self::$instance;
	} 

	/**
	* @return array
	*/
	public static function getWings() :array{
		return self::$wings;
	}

	/**
	* @param Player $player
	* @param string $wing
	* @return bool
	*/
	public static function hasPer(Player $player, string $wing) :bool{
		return $player->hasPermission("wing.on.".$wing);
	}

	/**
	* @param Vector3 $vec
	* @param null|string $character
	*/
	public function parseWing(Vector3 $pos, $character) :Particle{
		switch($character){
			case "x":
				$particle = new RedstoneParticle($pos);
				break;
			case "1":
				$particle = new DustParticle($pos, 3, 0, 132);
				break;
			case "2":
				$particle = new DustParticle($pos, 0, 102, 0);
				break;
			case "4":
				$particle = new DustParticle($pos, 179, 0, 0);
				break;
			default:
				$particle = new FlameParticle($pos);
				break;
		}
		return $particle;
	}

	/**
	* @param Player $player
	* @param string $wing
	*/
	public function equipWing(Player $player, string $wing) :void {
		if(!self::hasPer($player, $wing)){
			$player->sendMessage("You don't have permission");
			return;
		}
		$shape = self::getWings()[$wing]["shape"];
		$lowername = $player->getLowerCaseName();
		$wingtask = new WingTask($player, $shape);

		if(!isset(self::$equip_players[$lowername])){
			self::$equip_players[$lowername]["id"] = $wingtask->getTaskId();
			self::$equip_players[$lowername]["name"] = $wing;
			$player->sendMessage("Turn on $wing wing");
			$this->getScheduler()->scheduleRepeatingTask($wingtask, 5);
			return;
		}
		if(self::$equip_players[$lowername]["name"] == $wing){
			$player->sendMessage("Turn off $wing wing");
			$this->unEquip($player);
			return;
		}else{
			$this->unEquip($player);
			self::$equip_players[$lowername]["id"] = $wingtask->getTaskId();
			self::$equip_players[$lowername]["name"] = $wing;
			$player->sendMessage("Turn on $wing wing");
			$this->getScheduler()->scheduleRepeatingTask($wingtask, 5);
		}
	}

	/**
	* @param Player $player
	*/
	public function unEquip(Player $player) :void{
		if(isset(self::$equip_players[$player->getLowerCaseName()])){
			$this->getScheduler()->cancelTask(self::$equip_players[$player->getLowerCaseName()]["id"]);
			unset(self::$equip_players[$player->getLowerCaseName()]);
		}
	}
}
