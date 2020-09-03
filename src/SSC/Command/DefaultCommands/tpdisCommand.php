<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\main;

class tpdisCommand extends VanillaCommand {

	/**
	 * @var $main main
	 */
	private $main;

	public function __construct(main $main) {
		parent::__construct("tpdis","テレポートの要求を拒否します。","/tpdis");
		$this->main=$main;
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player) {
			$name = $sender->getName();
			if ($this->main->tpplayer[$name] != null) {
				$tar = $this->main->tpplayer[$name];
				$target = $this->main->getServer()->getPlayer($tar);
				if ($target === null) {
					$sender->sendMessage("[転送用AI] §4テレポートを拒否しました。");
					$this->main->tpplayer[$name] = null;
					return true;
				} else {
					$sender->getServer()->getPlayer($tar)->sendMessage("[転送用AI] §4" . $sender->getName() . " はあなたの要求を拒否しました");
					$sender->sendMessage("[転送用AI] §4テレポートの要求を拒否しました");
					$this->main->tpplayer[$name] = null;
					return true;
				}
			} else {
				$sender->sendMessage("[転送用AI] §cリクエストはありません。");
				return true;
			}
		}
		return true;
	}
}