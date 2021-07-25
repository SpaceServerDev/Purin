<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;

class suCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("su","自殺します","/su");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$sender instanceof Player) return true;
		if($sender->getLevel()->getFolderName()==="moon"){
			$sender->sendMessage("[謎の声] 月での自殺は危険です...逃げてください...");
			return true;
		}
		$sender->attack(new EntityDamageEvent($sender, EntityDamageEvent::CAUSE_SUICIDE, 1000));
		return true;
	}
}