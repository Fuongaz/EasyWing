<?php

declare(strict_types=1);

namespace phuongaz\EasyWing\form;

use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;
use phuongaz\EasyWing\Loader;

Class WingsForm{

	public function __construct(
		private Player $player){}

	public function getPlayer() :Player{
		return $this->player;
	}

	public function send() :void{
		$loader = Loader::getInstance();
		$form = new SimpleForm(function(Player $player, ?int $data) use ($loader){
			if(is_null($data)) return;
			$wing = $loader->getWing(array_keys($loader->getWings())[$data]);
			$loader->equipWing($player, $wing);
		});
		$form->setTitle($loader->getSetting()->get("title"));
		foreach($loader->getWings() as $wing){
			$form->addButton($wing->get("Wing-Name"));
		}
		$form->sendToPlayer($this->getPlayer());
	}
}
