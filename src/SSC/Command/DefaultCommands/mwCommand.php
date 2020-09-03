<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\Config;
use SSC\main;

class mwCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("mw","マイワープを簡略化します","/mw ([player] [ワープ名])");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			if (!isset($args[0])) {
				$sender->getServer()->dispatchCommand($sender, "mywarp");
				return false;
			}
			if (!isset($args[1])) {
				$sender->sendMessage("使用方法:/mw [player] [ワープ名]");
				return false;
			}
			$playerdata=main::getPlayerData($sender->getName());
			if ($sender->getServer()->getPlayer($args[0])) {
				$aitename = $sender->getServer()->getPlayer($args[0])->getName();
				$ufsa = main::getMain()->getDataFolder() . "Mywarp/" . $aitename . ".yml";
				$yourwarp = new Config($ufsa, Config::YAML);
			} else {
				$aitename = $args[0];
				$ufsa = main::getMain()->getDataFolder() . "Mywarp/" . $args[0] . ".yml";
				$yourwarp = new Config($ufsa, Config::YAML);
			}

			if (empty($yourwarp->getAll())) {
				$sender->sendMessage("[管理AI]§4プレイヤーが存在していないかマイワープのデータがありません");
				unlink($ufsa);
				return true;
			}
			if ($aitename === $sender->getName()) {
				if ($yourwarp->exists($args[1])) {
					$pos = new Position($yourwarp->get($args[1])["x1"], $yourwarp->get($args[1])["y1"], $yourwarp->get($args[1])["z1"], main::getMain()->getServer()->getLevelByName($yourwarp->get($args[1])["level"]));
					$sender->sendMessage("[管理AI] §a{$sender->getName()}の{$args[1]}にワープしました!");
					if ($sender->getGamemode() == 0) {
						$sender->getPlayer()->setAllowFlight(false);
						$sender->setFlying(false);
					}
					$sender->teleport($pos);
				} else {
					$sender->sendMessage("[管理AI] §4存在しません");
				}
			} else {
				if ($yourwarp->exists($args[1])) {
					if ($yourwarp->get($args[1])["public"] == true) {
						if ($yourwarp->get($args[1])["level"] == "sun") {
							if ($playerdata->isSun() == false) {
								$sender->sendMessage("[管理AI]§4開放されていないワールドです");
								return false;
							}
						}
						$pos = new Position($yourwarp->get($args[1])["x1"], $yourwarp->get($args[1])["y1"], $yourwarp->get($args[1])["z1"], main::getMain()->getServer()->getLevelByName($yourwarp->get($args[1])["level"]));
						$sender->sendMessage("[管理AI] §a{$aitename}の{$args[1]}にワープしました!");
						if ($sender->getGamemode() == 0) {
							$sender->getPlayer()->setAllowFlight(false);
							$sender->setFlying(false);
						}
						$sender->teleport($pos);
					} else {
						$sender->sendMessage("[管理AI]§4公開されていません");
					}
				} else {
					$sender->sendMessage("[管理AI] §4存在しません");
				}
			}
		}
		return true;
	}
}