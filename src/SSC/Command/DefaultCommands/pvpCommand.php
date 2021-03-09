<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\Server;

class pvpCommand extends VanillaCommand {

	public function __construct(string $name) {
		parent::__construct($name, "pvpエリアに移動します！", "/pvp [1/2/3/4]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender->getLevel()->getFolderName() != "pvp") {
			if ($sender->getGamemode() == 0) {
				$sender->getPlayer()->setAllowFlight(false);
				$sender->setFlying(false);
			}
			if (isset($args[0])) {
				$sun = Server::getInstance()->getLevelByName("pvp");
				if ($args[0] == "1") {
					if ($sender instanceof Player) {
						$rand = mt_rand(1, 8);

						switch ($rand) {
							case 1:
								$pos = new Position(297, 4, 208, $sun);
								break;
							case 2:
								$pos = new Position(255, 4, 255, $sun);
								break;
							case 3:
								$pos = new Position(240, 4, 291, $sun);
								break;
							case 4:
								$pos = new Position(275, 4, 294, $sun);
								break;
							case 5:
								$pos = new Position(255, 4, 228, $sun);
								break;
							case 6:
								$pos = new Position(187, 4, 254, $sun);
								break;
							case 7:
								$pos = new Position(286, 4, 260, $sun);
								break;
							case 8:
								$pos = new Position(290, 4, 290, $sun);
								break;
						}
						if ($sender->getGamemode() == 0) {
							$sender->getPlayer()->setAllowFlight(false);
							$sender->setFlying(false);
						}
						$sender->teleport($pos);
						$sender->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_TELEPORT);
						$sender->sendMessage("[宇宙船]§aPVPエリアに移動しました！");
						$sender->addEffect(new EffectInstance(Effect::getEffect(11), 20 * 3, 3, true));
					}
				} else if ($args[0] == "2") {
					if ($sender instanceof Player) {
						$rand = mt_rand(1, 5);
						switch ($rand) {
							case 1:
								$pos = new Position(1018, 13, 985, $sun);
								break;
							case 2:
								$pos = new Position(1017, 13, 1017, $sun);
								break;
							case 3:
								$pos = new Position(982, 13, 1017, $sun);
								break;
							case 4:
								$pos = new Position(982, 13, 985, $sun);
								break;
							case 5:
								$pos = new Position(1000, 10, 1000, $sun);
								break;
						}
						$sender->teleport($pos);
						$sender->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_TELEPORT);
						$sender->sendMessage("[宇宙船]§aPVPエリアに移動しました！");
						$sender->addEffect(new EffectInstance(Effect::getEffect(11), 20 * 3, 3, true));
					}
				} else if ($args[0] == "3") {
					if ($sender instanceof Player) {
						$rand = mt_rand(1, 9);
						switch ($rand) {
							case 1:
								$pos = new Position(1629, 6, 1433, $sun);
								break;
							case 2:
								$pos = new Position(1628, 6, 1549, $sun);
								break;
							case 3:
								$pos = new Position(1558, 3, 1499, $sun);
								break;
							case 4:
								$pos = new Position(1516, 6, 1567, $sun);
								break;
							case 5:
								$pos = new Position(1442, 6, 1569, $sun);
								break;
							case 6:
								$pos = new Position(1435, 8, 1401, $sun);
								break;
							case 7:
								$pos = new Position(1377, 3, 1391, $sun);
								break;
							case 8:
								$pos = new Position(1523, 6, 1422, $sun);
								break;
							case 9:
								$pos = new Position(1532, 7, 1498, $sun);
								break;
						}
						$sender->teleport($pos);
						$sender->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_TELEPORT);
						$sender->sendMessage("[宇宙船]§aPVPエリアに移動しました！");
						$sender->addEffect(new EffectInstance(Effect::getEffect(11), 20 * 3, 3, true));
					}
				}else if ($args[0] == "4") {
					if ($sender instanceof Player) {
						$rand = mt_rand(1, 6);
						switch ($rand) {
							case 1:
								$pos = new Position(1677, 11, 1790, $sun);
								break;
							case 2:
								$pos = new Position(1705, 11, 1818, $sun);
								break;
							case 3:
								$pos = new Position(1677, 11, 1846, $sun);
								break;
							case 4:
								$pos = new Position(1649, 11, 1818, $sun);
								break;
							case 5:
								$pos = new Position(1683, 11, 1811, $sun);
								break;
							case 6:
								$pos = new Position(1670, 11, 1825, $sun);
								break;
						}
						$sender->teleport($pos);
						$sender->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_TELEPORT);
						$sender->sendMessage("[宇宙船]§aPVPエリアに移動しました！");
						$sender->addEffect(new EffectInstance(Effect::getEffect(11), 20 * 3, 3, true));
					}
				} else {
					$sender->sendMessage("[管理AI]§a存在しないエリアです。");
				}
			} else {
				$sender->sendMessage("[管理AI]§aエリアを指定してください(1～4)");
			}
		} else {
			$sender->sendMessage("[管理AI]§aエリアでは逃げられません!");
		}
		return true;
	}
}