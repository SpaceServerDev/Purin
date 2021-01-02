<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
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
							return true;
						}
					} else if($sender->getLevel()->getFolderName()==="pluto"){
						if($targetdata->isExpertLevel()){
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
							main::getMain()->getServer()->getPlayer($tar)->sendMessage("[転送用AI] §b" . $sender->getName() . " は冥王星にいるためワープできません");
							main::getMain()->tpplayer[$name] = null;
							return true;
						}
					} else if($target->getLevel()->getFolderName()==="moon"){
						$target->teleport(new Position($sender->getX(), $sender->getY(), $sender->getZ(), $level));
						if($targetdata->getSpaceShipSize()<20){
							$this->main->getServer()->getPlayer($tar)->sendMessage("[転送用AI] §b" . $sender->getName() . " がテレポートを承認しました。テレポートしています。");
							if ($this->main->getServer()->getPlayer($tar)->getGamemode() == 0) {
								$this->main->getServer()->getPlayer($tar)->getPlayer()->setAllowFlight(false);
								$this->main->getServer()->getPlayer($tar)->setFlying(false);
							}
							$sender->sendMessage("[転送用AI] §bテレポートの承認が完了しました。");
							$this->main->tpplayer[$name] = null;
							$sender->addEffect(new EffectInstance(Effect::getEffect(11), 20 * 20, 100, true));
							$sender->addEffect(new EffectInstance(Effect::getEffect(18), 20 * 20, 100, true));
							main::getMain()->getServer()->getPlayer($tar)->sendMessage("[§4⚠WARNING⚠§r] 未開拓地域です。大切なアイテムはしまって他人からの強奪に注意してください。");
							main::getMain()->getServer()->getPlayer($tar)->sendMessage("[§aルール§r] リスキル禁止。ハック禁止。雑堀可能。アイテムドロップあり。キル可能。同クランへの攻撃は不可");
							return true;
						} else {
							main::getMain()->getServer()->getPlayer($tar)->sendMessage("[転送用AI] §b" . $sender->getName() . " は月にいるためワープできません");
							main::getMain()->tpplayer[$name] = null;
							return true;
						}
					}else {
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