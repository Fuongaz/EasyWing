<?php

namespace phuongaz\EasyWing;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\level\particle\{
	DustParticle,
	FlameParticle,
	RedstoneParticle,
	EntityFlameParticle,
	EnchantParticle,
	HeartParticle,
	PortalParticle,
	WaterParticle,
	WaterDripParticle,
	HappyVillagerParticle,
	EnchantmentTableParticle,
	Particle
};
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use phuongaz\EasyWing\task\WingTask;
use phuongaz\EasyWing\command\WingsCommand;
use phuongaz\EasyWing\utils\Particles;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;

Class Loader extends PluginBase implements Listener{

	private static array $equip_players = [];
	private static array $wings = [];
	private static Loader $instance;

	public function onEnable() :void{
		$this->saveDefaultConfig();
		$this->saveResource("wings/example.yml");
		foreach(glob($this->getDataFolder(). "wings/*.yml") as $wingPath){
			$wingName = pathinfo($wingPath, PATHINFO_FILENAME);
			self::$wings[$wingName] = yaml_parse_file($wingPath);
		}
		self::$instance = $this;
		$this->getServer()->getCommandMap()->register("EasyWing", new WingsCommand());
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onQuit(PlayerQuitEvent $event) :void {
		$player = $event->getPlayer();
		$this->unEquip($player);
	}

	public static function getInstance() :self{
		return self::$instance;
	} 

	public static function getWings() :array{
		return self::$wings;
	}

	public function getSetting() :array 
	{
 		return yaml_parse_file($this->getDataFolder(). "config.yml");
	}

	public static function hasPer(Player $player, string $wing) :bool{
		return $player->hasPermission("easywing.on.".$wing);
	}

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
			case "b":
				$particle = new Particles(Particles::BLUE_FLAME, $pos);
				break;
			case "h":
				$particle = new Particles(Particles::VILLAGER_HAPPY, $pos);
				break;
			case "p":
				$particle = new Particles(Particles::VILLAGER_ANGRY, $pos);
				break;
			case "F":
				$particle = new Particles(Particles::FLAME, $pos);
				break;
			case "H":
				$particle = new HeartParticle($pos);
				break;
			case "P":
				$particle = new PortalParticle($pos);
				break;
			case "E":
				$particle = new EntityFlameParticle($pos);
				break;
			case "W":
				$particle = new WaterDripParticle($pos);
				break;
			case "w":
				$particle = new WaterParticle($pos);
				break;
			case "j":
				$particle = new EnchantParticle($pos);
				break;
			case "J":
				$particle = new EnchantmentTableParticle($pos);
				break;
			case "D":
				$particle = new Particles(Particles::DRAGON_BREATH_LINGERING, $pos);
				break;
			default:
				$particle = new RedstoneParticle($pos);
				break;
		}
		return $particle;
	}

	public function equipWing(Player $player, string $wing) :void {
		if(!self::hasPer($player, $wing)){
			$player->sendMessage("You don't have permission");
			return;
		}
		$shape = self::getWings()[$wing]["shape"];
		$scale = self::getWings()[$wing]["scale"];
		$lowername = $player->getLowerCaseName();
		$wing = new CustomWing($player, $shape, $scale);
		$wingtask = new WingTask($wing);
		if(!isset(self::$equip_players[$lowername])){
			$this->getScheduler()->scheduleRepeatingTask($wingtask, $this->getSetting()["tick-update"]);
			self::$equip_players[$lowername]["id"] = $wingtask->getTaskId();
			self::$equip_players[$lowername]["name"] = $wing;
			$player->sendMessage($this->getSetting()["turn-on"]);			
			return;
		}
		if(self::$equip_players[$lowername]["name"] == $wing){
			$player->sendMessage($this->getSetting()["turn-off"]);
			$this->unEquip($player);
			return;
		}else{
			$this->unEquip($player);
			$this->getScheduler()->scheduleRepeatingTask($wingtask, $this->getSetting()["tick-update"]);
			self::$equip_players[$lowername]["id"] = $wingtask->getTaskId();
			self::$equip_players[$lowername]["name"] = $wing;
			$player->sendMessage($this->getSetting()["turn-on"]);			
		}
	}

	public function unEquip(Player $player) :void{
		if(isset(self::$equip_players[$player->getLowerCaseName()])){
			$this->getScheduler()->cancelTask(self::$equip_players[$player->getLowerCaseName()]["id"]);
			unset(self::$equip_players[$player->getLowerCaseName()]);
		}
	}
}
