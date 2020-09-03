<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\level\Position;
use pocketmine\Player;
use SSC\main;

class tpagreeCommand extends VanillaCommand {

	/**
	 * @var main
	 */
	private $main;

	public function __construct(main $main) {
		parent::__construct("tpagree","テレポートリクエストを承認します","/tpagree");
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
			$playerdata=main::getPlayerData($name);
			if ($this->main->tpplayer[$name] != null) {
				$tar = $this->main->tpplayer[$name];
				$target = $this->main->getServer()->getPlayer($tar);
				if ($target === null) {
					$sender->sendMessage("[転送用AI] §aそのプレイヤーはオフラインです");
					return true;
				} else {
					$targetdata=main::getPlayerData($target->getName());
					$level = $sender->getLevel();
					if ($sender->getLevel()->getFolderName() == "sun") {
						if ($targetdata->isSun()) {
							$target->teleport(new Position($sender->getX(), $sender->getY(), $sender->getZ(), $level));
							$this->main->getServer()->getPlayer($tar)->sendMessage("[転送用AI] §b" . $sender->getName() . " がテレポートを承認しました。テレポートしています。");
							if ($this->main->getServer()->getPlayer($tar)->getGamemode() == 0) {
								$this->main->getServer()->getPlayer($tar)->getPlayer()->setAllowFlight(false);
								$this->main->getServer()->getPlayer($tar)->setFlying(false);
							}
							$sender->sendMessage("[転送用AI] §bテレポートの承認が完了しました。");
							$this->main->tpplayer[$name] = null;
							return true;
						} else {
							$this->main->getServer()->getPlayer($tar)->sendMessage("[転送用AI] §b" . $sender->getName() . " は太陽にいるためワープできません");
							$this->main->tpplayer[$name] = null;
						}
					} else {
						$target->teleport(new Position($sender->getX(), $sender->getY(), $sender->getZ(), $level));
						$this->main->getServer()->getPlayer($tar)->sendMessage("[転送用AI] §b" . $sender->getName() . " がテレポートを承認しました。テレポートしています。");
						if ($this->main->getServer()->getPlayer($tar)->getGamemode() == 0) {
							$this->main->getServer()->getPlayer($tar)->getPlayer()->setAllowFlight(false);
							$this->main->getServer()->getPlayer($tar)->setFlying(false);
						}
						$sender->sendMessage("[転送用AI] §bテレポートの承認が完了しました。");
						$this->main->tpplayer[$name] = null;
						return true;
					}
				}
			} else {
				$sender->sendMessage("[転送用AI] §cリクエストはありません。");
				return true;
			}
		}
		return true;
	}
}