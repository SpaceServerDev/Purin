<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\level\Position;
use pocketmine\Player;
use SSC\main;

class xtpCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("xtp","ワールドの好きな座標にテレポートします","/xtp x座標 y座標 z座標 ワールド名(地球/人工惑星/太陽/海王星/火星)");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			if (!isset($args[0])or!isset($args[1])or!isset($args[2])or!isset($args[3])) {
				$sender->sendMessage("[転送システム] /xtp x座標 y座標 z座標 ワールド名(地球/人工惑星/太陽/海王星/火星)");
				return false;
			}
			$playerdata=main::getPlayerData($sender->getName());
			if (is_numeric($args[0])&& is_numeric($args[1]) && is_numeric($args[2])) {
				if ($args[1] > 300) {
					$args[1] = 300;
				}
				$maxX=$playerdata->getPlayer()->getFloorX()+$playerdata->getSpaceShipSize()*100;
				$minX=$playerdata->getPlayer()->getFloorX()-$playerdata->getSpaceShipSize()*100;
				if($args[0]>$maxX or $args[0]<$minX){
					$playerdata->getPlayer()->sendMessage("[転送システム]xの値が不正です。 宇宙船のサイズが足りません。 [{$minX}~{$maxX}]");
					return true;
				}
				$maxZ=$playerdata->getPlayer()->getFloorZ()+$playerdata->getSpaceShipSize()*100;
				$minZ=$playerdata->getPlayer()->getFloorZ()-$playerdata->getSpaceShipSize()*100;
				if($args[2]>$maxZ or $args[2]<$minZ){
					$playerdata->getPlayer()->sendMessage("[転送システム]zの値が不正です。 宇宙船のサイズが足りません。 [{$minZ}~{$maxZ}] ");
					return true;
				}
				if ($args[3] === "地球" or $args[3] === "人工惑星" or $args[3] === "太陽" or $args[3] === "海王星" or $args[3] === "火星") {
					if ($args[3] === "人工惑星") {
						$world = main::getMain()->getServer()->getLevelByName("flatworld");
						$pos = new Position((int)$args[0], (int)$args[1], (int)$args[2], $world);
						$sender->getPlayer()->teleport($pos);
						$sender->sendMessage("[転送システム]§a" . $args[3] . "の" . $args[0] . "," . $args[1] . "," . $args[2] . "にワープしました。");
						if ($sender->getGamemode() == 0) {
							$sender->getPlayer()->setAllowFlight(false);
							$sender->setFlying(false);
						}
						return true;
					} else if ($args[3] === "地球") {
						$world = Server::getInstance()->getLevelByName("earth");
						$pos = new Position((int)$args[0], (int)$args[1], (int)$args[2], $world);
						$sender->getPlayer()->teleport($pos);
						$sender->sendMessage("[転送システム]§a" . $args[3] . "の" . $args[0] . "," . $args[1] . "," . $args[2] . "にワープしました。");
						if ($sender->getGamemode() == 0) {
							$sender->getPlayer()->setAllowFlight(false);
							$sender->setFlying(false);
						}

						return true;
					} else if ($args[3] === "太陽") {
						if ($playerdata->isSun()) {
							$world = main::getMain()->getServer()->getLevelByName("sun");
							if ($args[1] > 125) {
								$args[1] = 125;
							}
							$pos = new Position((int)$args[0], (int)$args[1], (int)$args[2], $world);
							$sender->getPlayer()->teleport($pos);
							$sender->sendMessage("[転送システム]§a" . $args[3] . "の" . $args[0] . "," . $args[1] . "," . $args[2] . "にワープしました。");
							if ($sender->getGamemode() == 0) {
								$sender->getPlayer()->setAllowFlight(false);
								$sender->setFlying(false);
							}
						} else {
							$sender->sendMessage("[転送システム]§aあなたはここに行く権限がありません");
						}
					} else if ($args[3] === "海王星") {
						$world = Server::getInstance()->getLevelByName("Neptune");
						$pos = new Position((int)$args[0], (int)$args[1], (int)$args[2], $world);
						$sender->getPlayer()->teleport($pos);
						$sender->sendMessage("[転送システム]§a" . $args[3] . "の" . $args[0] . "," . $args[1] . "," . $args[2] . "にワープしました。");
						if ($sender->getGamemode() == 0) {
							$sender->getPlayer()->setAllowFlight(false);
							$sender->setFlying(false);
						}

						return true;
					} else if ($args[3] === "火星") {
						$world = Server::getInstance()->getLevelByName("mars");
						$pos = new Position((int)$args[0], (int)$args[1], (int)$args[2], $world);
						$sender->getPlayer()->teleport($pos);
						$sender->sendMessage("[転送システム]§a" . $args[3] . "の" . $args[0] . "," . $args[1] . "," . $args[2] . "にワープしました。");
						if ($sender->getGamemode() == 0) {
							$sender->getPlayer()->setAllowFlight(false);
							$sender->setFlying(false);
						}
						return true;
					} else {
						$sender->sendMessage("使用方法:/xtp x座標 y座標 z座標 ワールド名(地球/人工惑星/太陽/海王星/火星)");
						return false;
					}
				} else {
					$sender->sendMessage("使用方法:/xtp x座標 y座標 z座標 ワールド名(地球/人工惑星/太陽/海王星/火星)");
					return false;
				}
			} else {
				$sender->sendMessage("使用方法:/xtp x座標 y座標 z座標 ワールド名(地球/人工惑星/太陽/海王星/火星)");
				return false;
			}
		}
		return true;
	}
}