<?php

namespace phuongaz\EasyWing\utils;

use pocketmine\level\particle\Particle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;

class Particles extends Particle {

    public const DRAGON_BREATH_TRAIL = "minecraft:dragon_breath_trail";
    public const DRAGON_BREATH_LINGERING = "minecraft:dragon_breath_lingering";
    public const VILLAGER_HAPPY = "minecraft:villager_happy";
    public const MOBSPELL_EMITTER = "minecraft:mobspell_emitter";
    public const FLAME = "minecraft:basic_flame_particle";
    public const VILLAGER_ANGRY = "minecraft:villager_angry";
    public const BLUE_FLAME = "minecraft:blue_flame_particle";
    
    /** @var string $name */
    private $name;

    /**
     * CustomParticle constructor.
     * @param string $particleName
     * @param Vector3 $pos
     */
    public function __construct(string $particleName, Vector3 $pos) {
        $this->name = $particleName;
        parent::__construct($pos->getX(), $pos->getY(), $pos->getZ());
    }

    public function encode() {
        $pk = new SpawnParticleEffectPacket();
        $pk->position = $this->asVector3();
        $pk->particleName = $this->name;
        return $pk;
    }
}