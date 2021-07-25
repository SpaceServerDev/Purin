<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class ptpCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("ptp", "プレイヤーワープを使う", "/ptp [player]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$playerdata = main::getPlayerData($sender->getName());
			if (!isset($args[0])) return false;
			if ($playerdata->getNumberPerm() !== 0) {
				$name = strtolower($args[0]);
				$aiteplayer = Server::getInstance()->getPlayer($name);
				if ($aiteplayer instanceof Player) {
					$aitename = $aiteplayer->getName();
					$aiteplayerdata = main::getPlayerData($aitename);
					if ($aiteplayerdata->getPerm() === "OP" or $aiteplayerdata->getPerm() === "副主" or $aiteplayerdata->getPerm() === "オーナー") {
						$sender->sendMessage("[管理AI]§c権限者にはワープできません");
						return false;
					} else {
						if ($sender->getGamemode() == 0) {
							$sender->getPlayer()->setAllowFlight(false);
							$sender->setFlying(false);
						}
						$y= $aiteplayer->getFloorY();
						if(main::getPlayerData($aiteplayer->getName())->getShitDownNow()){
							$y++;
						}
						if($aiteplayer->getLevel()->getFolderName()==="moon"){
							$sender->addEffect(new EffectInstance(Effect::getEffect(11), 20 * 20, 100, true));
							$sender->addEffect(new EffectInstance(Effect::getEffect(18), 20 * 20, 100, true));
						}
						$sender->teleport(new Position($aiteplayer->getFloorX(),$y, $aiteplayer->getFloorZ(), $aiteplayer->getLevel()));
						$sender->sendMessage("[管理AI]§a権限の適合を確認しましたのでワープいたします");
						foreach (Server::getInstance()->getOnlinePlayers() as $plyr) {
							if ($plyr->isOp()) {
								$plyr->sendMessage("[管理AI]§a" . $sender->getName() . "が" . $aitename . "にワープしました");
							}
						}
					}
				} else {
					$sender->sendMessage("[管理AI]§cそのようなプレイヤーは存在しません");
				}
			} else {
				$sender->sendMessage("[管理AI]§c権限の適合が確認できません");
			}
		}
		return true;
	}
}