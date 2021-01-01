<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Form\SpaceShip\SpaceShipForm;
use SSC\main;

class spaceshipCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("spaceship","宇宙船の改造をする","/spaceship");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$sender instanceof Player) return false;
		$sender->sendForm(new SpaceShipForm(main::getPlayerData($sender->getName())));
		return true;
	}
}