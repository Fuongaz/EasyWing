<?php

namespace phuongaz\EasyWing\form;

use pocketmine\Player;

use jojoe77777\FormAPI\SimpleForm;
use phuongaz\EasyWing\Loader;

Class WingsForm{

	private Player $player;

	public function __construct(Player $player){
		$this->player = $player;
	}

	public function getLoader() :Loader{
		return Loader::getInstance();
	}

	public function send() :void{
		$loader = $this->getLoader();
		$form = new SimpleForm(function(Player $player, ?int $data) use ($loader){
			if(is_null($data)) return;
			$wing = array_keys(Loader::getWings())[$data];
			$loader->equipWing($player, $wing);
		});
		$form->setTitle($loader->getSetting()["title"]);
		foreach($loader->getWings() as $wingName){
			$form->addButton($wingName["Wing-Name"]);
		}
		$form->sendToPlayer($this->player);
	}
}