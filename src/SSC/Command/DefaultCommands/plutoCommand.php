<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Event\player\WarpPlayerEvent;
use SSC\main;

class plutoCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("pluto","冥王星へ行きます。エキスパートレベルになると行くことができます。","/pluto");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$playerdata=main::getPlayerData($sender->getName());
			if ($playerdata->isExpertLevel()) {
				$cls=new WarpPlayerEvent();
				$cls->execute($sender, "pluto");
				$sender->sendMessage("[宇宙船]§a冥王星に移動しました!");
			} else {
				$sender->sendMessage("[宇宙船]§a未開放のワールドです\n[宇宙船]§aプレイヤーレベルをあげて開放しましょう！");
			}
		}
		return true;
	}
}