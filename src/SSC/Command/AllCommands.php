<?php

namespace SSC\Command;

use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;
use pocketmine\Player;
use pocketmine\Server;


use pocketmine\command\CommandSender;

use pocketmine\level\Position;


use pocketmine\item\Item;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;



use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

use pocketmine\utils\Config;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use SSC\Form\ClanForm;
use SSC\Form\DailyChangeForm;
use SSC\Form\DailyForm;
use SSC\Form\FishFeedForm;
use SSC\Form\RepeatLoginbonusForm;
use SSC\Gacha\GachaEvent;
use SSC\main;
use SSC\PlayerEvent;
use SSC\Form\ZissekiForm;

class AllCommands implements Listener{

	/**
	 * @var main
	 */
	private $Main;

	/**
	 * AllCommands constructor.
	 * @param main $main
	 * @param CommandSender $sender
	 * @param string $command
	 * @param array $args
	 */
	public function __construct(main $main,CommandSender $sender,string $command,array $args){
		$this->Main= $main;
		$this->execute($sender ,$command,$args);
	}

	public function execute(CommandSender $sender,string $command,array $args) {
		/**
		 * @var $playerdata PlayerEvent
		 */
		$playerdata = $this->Main->getPlayerData($sender->getName());

		switch ($command) {

				case "syo":
					$buttons=[
						['text' => "称号をセットする",],
						['text' => "称号を削除する",]
					];
					$playerdata->normalForm("§d称号","§a項目を選択してください。",$buttons,11111);
					break;

				case "atama":
					$buttons = [
						['text' => "あたまのうえをセットする",],
						['text' => "あたまのうえを削除する",]
					];
					$playerdata->normalForm("§dあたま","§a項目を選択してください。",$buttons,33333);
				break;



				case "profile":
					$formdata=[
						["type" => "input",
						"text" => "調べたいプレイヤーのフルネームを入力してください",]
					];
					$playerdata->customForm("§dprofile",$formdata,78787);
				break;

				case "hitokoto":
					$formdata = [
						["type" => "input",
						"text" => "/profileに表示したい自己紹介を簡潔に入力してください"],
					];
					$playerdata->customForm("§dprofile",$formdata,78788);
				break;

				case "parm":
					if($sender->isOp()) {
						$formdata = [
							["type" => "input",
								"text" => "プレイヤーネーム"],
							["type" => "dropdown",
								"text" => "パーミッション",],
						];
						$formdata[1]["options"] = [
							"鯖民",
							"警察",
							"警察庁長官",
							"VIP",
							"VIP+",
							"OP",
							"オーナー",
						];
						$playerdata->customForm("§dprofile", $formdata, 78789);
					}
				break;

				case "job":
					$formdata = [
						["type" => "label",
						"text" => "Jobを変更できます\n木こりは木を切るとお金が稼げます\n採掘業は石を掘ると稼げます\n整地師は整地すると稼げます\n暗殺者はキルすれば稼げます\n建築士は建物を建てると稼げます\n高度整地師は太陽でネザーラックを掘ると稼げます\n漁師は釣りをすると稼げます\n\n",],
						["type" => "dropdown",
						"text" => "",]
					];
					$formdata[1]["options"] =[
					 "やめる",
					 "木こり",
					 "採掘業",
					 "整地師",
					 "暗殺者",
					 "建築士",
					 "高度整地師",
					 "農家",
					 "漁師",
					 ];
					$playerdata->customForm("§dJOBの変更",$formdata,78912);

				break;


				case "change":
					$buttons = [
						['text' => "§4太陽探索の権限をもらう\n(赤いチケット:30枚)",],
						['text' => "§4ドラゴンの頭もらう\n(赤いチケット:50枚)",],
						['text' => "§aイベントチケットに交換！\n(赤いチケット:10枚)",],
						['text' => "§a重力制御装置\n(赤いチケット:3枚)",],
//						['text' => "§d§lイベント交換所へ！",],
						];
					$playerdata->normalForm("§a§lEXCHANGE Center","§a交換所です！\n\n赤いチケット所持枚数 §c{$playerdata->getTicket("RED")} §a枚\nイベントチケット所持枚数 §c{$playerdata->getTicket("EVENT")} §a枚\n\n",$buttons,29913);
				break;



				case "mywarp":
					$buttons = [
						['text' => "マイワープにワープする",],
						['text' => "マイワープをここに作成",],
						['text' => "マイワープを削除",],
						['text' => "他人の公開されているマイワープにワープ",],
						];
					$playerdata->normalForm("§a§lMywarp","§a項目を選んでください\n\n",$buttons,32123);
				break;








				case "cmd":
					$buttons = [
						['text' => "赤いチケットについてしりたい！",],//0
						['text' => "挨拶をしたい！",],//1
						['text' => "ワープをしたい！",],//2
						['text' => "プロフィールを見たりメッセージ変更をしたい！",],//3
						['text' => "情報欄を消したい！",],//4
						['text' => "最新情報を確認したい!",],//5
						['text' => "マイワープについて",],//6
						['text' => "アイテムを全捨てしたい！",],//7
						['text' => "土地保護をしたい！",],//8
						['text' => "ガチャを引きたい！",],//9
						['text' => "ツールの耐久値を回復させたい！",],//10
						['text' => "ショップで売買したい！",],//11
						['text' => "額縁保護をしたい！",],//12
						['text' => "自殺をしたい！",],//13
						['text' => "特定の座標にワープをしたい！",],//14
						['text' => "チェストロックをしたい！",],//15
						['text' => "土地を共有/譲渡/売却をしたい！",],//16
						['text' => "仕事を変更したい！",],//17
						['text' => "称号の変更をしたい！",], //18
					];
					$playerdata->normalForm("§d§lCOMMANDHELPER","§aここではコマンドの逆引きを見れます\n\n",$buttons,01201);
				break;


			}
			return true;
	}


}