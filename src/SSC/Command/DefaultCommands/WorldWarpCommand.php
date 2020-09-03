<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Event\player\WarpPlayerEvent;

class WorldWarpCommand extends VanillaCommand {

	public function __construct(string $name, string $description, string $usageMessage) {
		parent::__construct($name, $description, $usageMessage);
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		switch ($commandLabel){
			case "blackhole":
				$world="Blackhole";
				$wn="ブラックホール";
				$bool=true;
			break;
			case "mars":
				$world="mars";
				$wn="火星";
				$bool=false;
			break;
			case "neptune":
				$world="Neptune";
				$wn="海王星";
				$bool=false;
			break;
			case "taucetuse":
				$world="TauCetusE";
				$wn="くじら座τ星e";
				$bool=false;
			break;
			case "taucetusf":
				$world="TauCetusF";
				$wn="くじら座τ星f";
				$bool=false;
			break;
			case "trappist-1e":
				$world="trappist-1e";
				$wn="トラピスト1星e";
				$bool=false;
			break;
			case "flat":
				$world="flatworld";
				$wn="人工惑星";
				$bool=false;
			break;
			case "rule":
				$world="world";
				$wn="ルールエリア";
				$bool=false;
			break;
			default:
				$world="space";
				$wn="ロビー";
				$bool=true;
			break;
		}

		if ($sender instanceof Player) {
			$cls=new WarpPlayerEvent();
			$cls->execute($sender, $world, $bool);
			$sender->sendMessage("[宇宙船]§a{$wn}に移動しました！");
		}
		return true;
	}
}