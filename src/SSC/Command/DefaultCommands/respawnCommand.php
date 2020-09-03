<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\Player;
use SSC\Event\player\WarpPlayerEvent;

class respawnCommand extends VanillaCommand {


	public function __construct(string $name) {
		parent::__construct($name, "ワールドのスポーン地点に移動します","/respawn");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$pos = $sender->getLevel()->getFolderName();
			$cls=new WarpPlayerEvent();
			$cls->execute($sender, $pos);
			$sender->sendMessage("[宇宙船]§a" . $pos . "の初期スポーンに移動しました！");
		}
		return true;
	}
}