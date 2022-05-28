<?php

namespace phuongaz\EasyWing;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use phuongaz\EasyWing\task\WingTask;
use phuongaz\EasyWing\command\WingsCommand;
use phuongaz\EasyWing\utils\Particles;
use phuongaz\EasyWing\utils\Utils;

Class Loader extends PluginBase implements Listener{
    use SingletonTrait;

	private array $equip_players;
	private array $wings;
	private Config $config;

	public function onLoad() :void{
	    self::setInstance($this);
    }

	public function onEnable() :void{
		$this->saveDefaultConfig();
		$this->saveResource("wings/example.yml");
		$this->getServer()->getCommandMap()->register("EasyWing", new WingsCommand());
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
		$i = 0;
		foreach(glob($this->getDataFolder(). "wings/*.yml") as $wingPath){
			$wingName = pathinfo($wingPath, PATHINFO_FILENAME);
			$this->wings[$wingName] = new Config($wingPath, Config::YAML);
			$i++;
		}
		$this->getServer()->getLogger()->info("Loaded $i wings..");
	}

	public function onQuit(PlayerQuitEvent $event) :void {
		$player = $event->getPlayer();
		$this->unEquip($player);
	}

	public function getWings() :array{
		return $this->wings;
	}

	public function getWingData(string $name) :?Config {
		return $this->wings[$name];
	}

	public function getWing(string $name) :CustomWing{
		$wingData = $this->getWingData($name);
		$shape = $wingData->get("shape");
		$scale = $wingData->get("scale");
		return new CustomWing($name, $shape, $scale);
	}

	public function getSetting() :Config{
 		return $this->config;
	}

	public function equipWing(Player $player, CustomWing $wing) :void {
		if(!Utils::hasPermission($player, $wing->getName())){
			$player->sendMessage("You don't have permission");
			return;
		}
		$tickUpdate = $this->getSetting()->get("tick-update");
		$playerName = $player->getName();
		$wingTask = new WingTask($player, $wing);
		if(!isset($this->equip_players[$playerName])){
			$this->getScheduler()->scheduleRepeatingTask($wingTask, $tickUpdate);
			$this->equip_players[$playerName]["task"] = $wingTask;
			$this->equip_players[$playerName]["name"] = $wing;
			$player->sendMessage($this->getSetting()->get("turn-on"));			
			return;
		}
		if($this->equip_players[$playerName]["name"] == $wing){
			$this->unEquip($player);
		}else{
			$this->unEquip($player);
			$this->getScheduler()->scheduleRepeatingTask($wingTask, $tickUpdate);
			$this->equip_players[$playerName]["task"] = $wingTask;
			$this->equip_players[$playerName]["name"] = $wing;
			$player->sendMessage($this->getSetting()->get("turn-on"));		
		}
	}

	public function unEquip(Player $player) :void{
		if(isset($this->equip_players[$player->getName()])){
			$task = $this->equip_players[$player->getName()]["task"];
			$task->getHandler()->cancel();
			unset($this->equip_players[$player->getName()]);
			$player->sendMessage($this->getSetting()->get("turn-off"));
		}
	}
}
