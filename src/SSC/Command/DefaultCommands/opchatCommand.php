<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;

class opchatCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("opchat","Staffのみ見れる重要事項を送信","/opchat [message]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender->isOp()) {
			if (!isset($args[0])) {
				return false;
			}
			if ($sender instanceof Player) {
				$sendname = $sender->getName();
				$chat = $args[0];
				foreach (Server::getInstance()->getOnlinePlayers() as $payer) {
					if ($payer->isOp() == true) {
						$payer->sendMessage("<§a{$sendname}§r>→<§eop全員§r> {$chat}");
					}
				}
			}
		}
		return true;
	}
}