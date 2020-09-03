<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Event\player\WarpPlayerEvent;
use SSC\main;

class sunCommand extends VanillaCommand {

	public function __construct(string $name) {
		parent::__construct($name,"太陽に向かいます。入るには太陽へ入る権限が必要です。","/sun");
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
			if ($playerdata->isSun()) {
				$cls=new WarpPlayerEvent();
				$cls->execute($sender, "sun");
				$sender->sendMessage("[宇宙船]§a太陽に移動しました!");
			} else {
				$sender->sendMessage("[宇宙船]§a未開放のワールドです\n[宇宙船]§aチケットを集めて開放しましょう！");
			}
		}
	}
}