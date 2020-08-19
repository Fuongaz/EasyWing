<?php

namespace phuongaz\EasyWing\command;

use pocketmine\command\{
	Command,
	CommandSender
};
use pocketmine\Player;
use phuongaz\EasyWing\form\WingsForm;

Class WingsCommand extends Command{

	public function __construct(){
		parent::__construct("wings", "open wings form");
	}

	/**
	* @param CommandSender $sender
	* @param string $label
	* @param array $args;
	* @return bool
	*/
	public function execute(CommandSender $sender, string $label, array $args) :bool{
		if(!$sender instanceof Player) return false;
		$form = new WingsForm($sender);
		$form->send();
		return true;
	}
}