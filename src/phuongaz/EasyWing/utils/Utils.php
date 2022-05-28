<?php

declare(strict_types=1);

namespace phuongaz\EasyWing\utils;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\particle\{
	DustParticle,
	RedstoneParticle,
	EntityFlameParticle,
	EnchantParticle,
	HeartParticle,
	PortalParticle,
	WaterParticle,
	WaterDripParticle,
	EnchantmentTableParticle,
	Particle
};
use pocketmine\utils\Color;

class Utils {

    public static function parseWing($character) :Particle{
		return match($character){
            "x" => new RedstoneParticle(),
            "1" => new DustParticle(new Color(3, 0, 132)),
            "2" => new DustParticle(new Color(0, 102, 0)),
            "4" => new DustParticle(new Color(179, 0, 0)),
            "b" => new Particles(Particles::BLUE_FLAME),
            "h" => new Particles(Particles::VILLAGER_HAPPY),
            "p" => new Particles(Particles::VILLAGER_ANGRY),
            "F" => new Particles(Particles::FLAME),
            "H" => new HeartParticle(),
            "P" => new PortalParticle(),
            "E" => new EntityFlameParticle(),
            "W" => new WaterDripParticle(),
            "w" => new WaterParticle(),
            "j" => new EnchantParticle(),
            "J" => new EnchantmentTableParticle(),
            default => new RedstoneParticle()
        };
	}

  public static function hasPermission(Player $player, string $wing) :bool{
      if(Server::getInstance()->isOp($player->getName())) return true;
		  return $player->hasPermission("easywing.on.".$wing);
	}
}