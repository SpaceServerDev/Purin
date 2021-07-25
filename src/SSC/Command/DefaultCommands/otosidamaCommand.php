<?php


namespace SSC\Command\DefaultCommands;


use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\main;

class otosidamaCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("otosidama","お年玉とおみくじをヒキます！","/otosidama");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		main::getMain()->otosidama->reload();
		if(main::getMain()->otosidama->exists($sender->getName())){
			$sender->sendMessage("[まま]もうもらったでしょ！");
			return true;
		}
		$rand=mt_rand(10000,500000);
		EconomyAPI::getInstance()->addMoney($sender->getName(),$rand);
		$sender->sendMessage("[まま]はい！お年玉！");
		$sender->sendMessage("[{$sender->getName()}]なんと{$rand}￥入ってた！ありがとうりりやまま！");
		if($rand===10000){
			$sender->sendMessage("[神様] 大凶 ある意味運がいいよ いい年になりますように");
			return true;
		}
		if($rand<30000){
			$sender->sendMessage("[神様] 凶 出会いが無いかも。ゆりしーとなら付き合えるかも。");
			return true;
		}
		if($rand<10000){
			$sender->sendMessage("[神様] 吉 出会いが少しならあるかも。かみなりとなら付き合えるかも。");
		}
		if($rand<50000){
			$sender->sendMessage("[神様] 中吉 出会いが少しならあるかも。じむとなら付き合えるかも。");
		}
		if($rand<499999){
			$sender->sendMessage("[神様] 大吉 出会いが少しならあるかも。ゆりしーとなら付き合えるかも。");
		}
		if($rand===500000){
			$sender->sendMessage("[神様] 大大吉 すげえよ！運が良すぎ！ でもここで使い切っちゃったんじゃない？笑");
		}
		main::getMain()->otosidama->reload();
		main::getMain()->otosidama->set($sender->getName());
		main::getMain()->otosidama->save();
		return true;
	}
}