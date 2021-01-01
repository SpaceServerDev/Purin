<?php

namespace SSC\Command;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;

use SSC\main;


class ShopCommandReturn implements Listener {
	public function __construct(Main $main, int $id, Player $player, string $data, $result) {
		$this->Main = $main;
		$this->DPRE($id, $player, $data, $result);
	}

	function DPRE(int $id, Player $player, string $data = "", $result) {
		$name = $player->getName();
		$mymoney = $this->Main->economyAPI->myMoney($player->getName());
		switch ($id) {
			case 30718:
				$this->Main->itiji[$name] = null;
				switch ($data) {
					case 0:
						$buttons[] = [
							'text' => "§d§l石系",
						];
						$buttons[] = [
							'text' => "§d§l土系",
						];
						$buttons[] = [
							'text' => "§d§l木系",
						];
						$buttons[] = [
							'text' => "§d§l砂系",
						];
						$buttons[] = [
							'text' => "§d§l羊毛系",
						];
						$buttons[] = [
							'text' => "§d§l花系",
						];
						$buttons[] = [
							'text' => "§d§lネザー系",
						];
						$buttons[] = [
							'text' => "§d§lテラコッタ",
						];
						$buttons[] = [
							'text' => "§d§lコンクリート",
						];
						$buttons[] = [
							'text' => "§d§lステンドグラス",
						];
						$buttons[] = [
							'text' => "§d§lステンドグラス窓",
						];
						$buttons[] = [
							'text' => "§d§l食料",
						];
						$buttons[] = [
							'text' => "§d§l武器",
						];
						$buttons[] = [
							'text' => "§d§lその他",
						];

						$pk = new ModalFormRequestPacket();
						$id = 99991;
						$pk->formId = $id;
						$data = [
							"type" => "form",
							"title" => "§d§lITEMSHOP/list/index.htm",
							"content" => "§a所持金 → §e{$mymoney}￥\n項目を選んでください\n\n",
							"buttons" => $buttons
						];
						$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
						$player->dataPacket($pk);
						break;

					case 1:
						$item = Item::get(1, 0, 2304);
						$test = array();
						if ($player->getInventory()->canAddItem($item) == false) {

							foreach ($player->getInventory()->getContents() as $inv) {
								$id = $inv->getId();
								$damage = $inv->getDamage();
								$IN = $inv->getName();
								if (!in_array($IN, $test, true)) {
									$test[] = $IN;
									$buttons[] = [
										"text" => $IN,
									];
									$this->Main->itiji[$name]["id"][] = $id;
									$this->Main->itiji[$name]["damage"][] = $damage;
								}
							}

							$pk = new ModalFormRequestPacket();
							$id = 99774;
							$pk->formId = $id;
							$formdata = [
								"type" => "form",
								"title" => "§d§lITEMSHOP",
								"content" => "売りたいアイテムを選択してください\n§a現在の所持金 → §e{$mymoney}￥\n\n",
								"buttons" => $buttons
							];
							$pk->formData = json_encode($formdata);
							$player->dataPacket($pk);
						} else {
							$player->sendMessage("[ITEMSHOP]§cインベントリにアイテムがありません。");
						}
						break;
				}
				break;//shop

			case 99774:
				$dt = (int)$data;
				$id = $this->Main->itiji[$name]["id"][$dt];
				$dmg = $this->Main->itiji[$name]["damage"][$dt];
				$this->Main->itemname[$name] = null;
				$itmamount = 0;

				foreach ($player->getInventory()->getContents() as $itm) {
					$id_t = $itm->getId();
					$dmg_t = $itm->getDamage();
					if ($id == $id_t) {
						if ($dmg == $dmg_t) {
							$amt = $itm->getCount();
							$itmamount = (int)$itmamount + (int)$amt;
						}
					}
				}

				$this->Main->itiji[$name]["amount"] = $itmamount;

				if ($id <= 173) {
					$consts = Main::getContains();
					$this->Main->itiji[$name]["data"] = 0;

					foreach ($consts as $key => $value) {
						$this->Main->itiji[$name]["dt"][] = $key;
						$this->Main->itiji[$name]["vl"][] = $value;
					}

					$n = 0;

					While (true) {
						if ($this->Main->itiji[$name]["vl"][$n]["ID"] == $id) {
							if ($this->Main->itiji[$name]["vl"][$n]["DAMAGE"] == $dmg) {
								$this->Main->itemname[$name] = $this->Main->itiji[$name]["vl"][$n];
								break;
							}
						}
						$n++;
					}
				} else {
					$player->sendMessage("[ITEMSHOP]§aまだ未対応の商品です。もう少しお待ち下さい");
					return false;
				}

				$price = (int)floor($this->Main->itemname[$name]["PRICE"] / 3);
				$pricef = $price * 5;
				$stack = $price * 64;
				$pk = new ModalFormRequestPacket();
				$pk->formId = 99775;
				$formdata["type"] = "custom_form";
				$formdata["title"] = "§l§dItemShop/Sell/{$id}/{$dmg}";

				$formdata["content"][] = array(
					"type" => "label",
					"text" => "§a§l{$this->Main->itemname[$name]["NAME"]}§r\n    1個 : §e§l{$price}￥§r\n    5個 : §e§l{$pricef}￥§r\n    64個 : §e§l{$stack}￥",
				);
				$formdata["content"][] = array(
					"type" => "slider",
					"text" => "§a§l個数 §r",
					"min" => 1,
					"max" => $itmamount,
				);

				$pk->formData = json_encode($formdata);
				$player->dataPacket($pk);

				break;//sell途中経

			case 99775:
				if ($this->Main->itemname[$name]["PRICE"] === null) {
					$player->sendMessage("[管理AI]何らかの処理にエラーが発生しました。");
					return true;
				}
				$buttons[] = [
					'text' => "はい",
				];
				$buttons[] = [
					'text' => "いいえ",
				];
				$this->Main->itiji[$name]["PRICE"] = floor($this->Main->itemname[$name]["PRICE"] / 3);
				$this->Main->itiji[$name]["PRICE"] = $this->Main->itiji[$name]["PRICE"] * $result[1];
				$this->Main->itiji[$name]["AMOUNT"] = $result[1];
				$pk = new ModalFormRequestPacket();
				$id = 99776;
				$pk->formId = $id;
				$data = [
					"type" => "form",
					"title" => "§d§lITEMSHOP/sell.htm",
					"content" => "§a所持金 → §e{$mymoney}￥\n\n§c最終確認\n§a§l§a  {$this->Main->itemname[$name]["NAME"]} §c- §b{$this->Main->itiji[$name]["AMOUNT"]}個 \n§e  {$this->Main->itiji[$name]["PRICE"]}￥§cで売却してよろしいですか？\n\n",
					"buttons" => $buttons
				];
				$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
				$player->dataPacket($pk);
				break;//sell最終確認

			case 99776:
				if ($data == 0) {
					$this->Main->economyAPI->addMoney($player->getName(), $this->Main->itiji[$name]["PRICE"]);
					$item = Item::get($this->Main->itemname[$name]["ID"], $this->Main->itemname[$name]["DAMAGE"], $this->Main->itiji[$name]["AMOUNT"]);
					$player->getInventory()->removeItem($item);
					$player->sendMessage("[ITEMSHOP] §l§b" . $this->Main->itemname[$name]["NAME"] . " §aを §e" . $this->Main->itiji[$name]["PRICE"] . "￥ §aで §d" . $this->Main->itiji[$name]["AMOUNT"] . "個 §a売却しました");
					$this->Main->itemitiji[$name] = null;
					$this->Main->itemname[$name] = null;
					return false;
				} else {
					$player->sendMessage("[ITEMSHOP] §a売却を取消しました。");
					$this->Main->itiji[$name] = null;
					$this->Main->itemname[$name] = null;
					return false;
				}
				break;//sell最終

			case 99991:
				switch ($data) {
					case 0:
						$this->Main->itiji[$name] = 0;
						$cas = "石系";
						$buttons[] = [
							'text' => "石 §e(￥10/個)",
						];//0,1
						$buttons[] = [
							'text' => "花崗岩 §e(￥10/個)",
						];//1,1:1
						$buttons[] = [
							'text' => "磨かれた花崗岩 §e(￥20/個)",
						];//2,1:2
						$buttons[] = [
							'text' => "閃緑岩 §e(￥10/個)",
						];//3,1:3
						$buttons[] = [
							'text' => "磨かれた閃緑岩 §e(￥20/個)",
						];//4,1:4
						$buttons[] = [
							'text' => "安山岩 §e(￥10/個)",
						];//5,1:5
						$buttons[] = [
							'text' => "磨かれた安山岩 §e(￥20/個)",
						];//6,1:6
						$buttons[] = [
							'text' => "丸石 §e(￥5/個)",
						];//7,4
						$buttons[] = [
							'text' => "苔の生えた丸石 §e(￥15/個)",
						];//8,48
						$buttons[] = [
							'text' => "石レンガ §e(￥10/個)",
						];//9,98
						$buttons[] = [
							'text' => "苔の生えた石レンガ §e(￥10/個)",
						];//10,98:1
						$buttons[] = [
							'text' => "ヒビの生えた石レンガ §e(￥10/個)",
						];//11,98:2
						$buttons[] = [
							'text' => "模様のある石レンガ §e(￥10/個)",
						];//12,98:3
						$buttons[] = [
							'text' => "丸石の階段 §e(￥30/個)",
						];//13,67
						$buttons[] = [
							'text' => "石の半ブロック §e(￥5/個)",
						];//14,44
						$buttons[] = [
							'text' => "丸石の半ブロック §e(￥2/個)",
						]; //15,44:3
						$buttons[] = [
							'text' => "石レンガの半ブロック §e(￥5/個)",
						]; //16,44:5
						$buttons[] = [
							'text' => "石レンガの階段 §e(￥40/個)",
						]; //17,109
						$buttons[] = [
							'text' => "丸石のフェンス §e(￥20/個)",
						]; //18,139
						$buttons[] = [
							'text' => "苔の生えた丸石フェンス §e(￥20/個)",
						];//19,139:1
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 1:
						$this->Main->itiji[$name] = 1;
						$cas = "土系";
						$buttons[] = [
							'text' => "草ブロック §e(￥10/個)",
						]; //20,2
						$buttons[] = [
							'text' => "土ブロック §e(￥5/個)",
						]; //21,3
						$buttons[] = [
							'text' => "変化しない土 §e(￥10/個)",
						];//22.3:1
						$buttons[] = [
							'text' => "草の道 §e(￥10/個)",
						];//23,198
						$buttons[] = [
							'text' => "ポドソル §e(￥20/個)",
						];//24,3:2
						$buttons[] = [
							'text' => "菌糸 §e(￥20/個)",
						];//25,110
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 2:
						$this->Main->itiji[$name] = 2;
						$cas = "木系";
						$buttons[] = [
							'text' => "オークの原木 §e(￥20/個)",
						]; //26,17
						$buttons[] = [
							'text' => "トウヒの原木 §e(￥20/個)",
						]; //27,17:1
						$buttons[] = [
							'text' => "白樺の原木 §e(￥20/個)",
						]; //28,17:2
						$buttons[] = [
							'text' => "ジャングルの原木 §e(￥20/個)",
						]; //29,17:3
						$buttons[] = [
							'text' => "アカシアの原木 §e(￥20/個)",
						]; //30,162
						$buttons[] = [
							'text' => "ダークオークの原木 §e(￥20/個)",
						];//31,162:1
						$buttons[] = [
							'text' => "オークの木材 §e(￥5/個)",
						]; //32,5
						$buttons[] = [
							'text' => "トウヒの木材 §e(￥5/個)",
						]; //33,5:1
						$buttons[] = [
							'text' => "白樺の木材 §e(￥5/個)",
						]; //34,5:2
						$buttons[] = [
							'text' => "ジャングルの木の木材 §e(￥5/個)",
						]; //35,5:3
						$buttons[] = [
							'text' => "アカシアの木材 §e(￥5/個)",
						]; //36,5:4
						$buttons[] = [
							'text' => "ダークオークの木材 §e(￥5/個)",
						]; //37,5:5
						$buttons[] = [
							'text' => "オークの階段 §e(￥30/個)",
						]; //38,53
						$buttons[] = [
							'text' => "松の階段 §e(￥30/個)",
						]; //39,134
						$buttons[] = [
							'text' => "白樺の階段 §e(￥30/個)",
						];//40,135
						$buttons[] = [
							'text' => "ジャングルの階段 §e(￥30/個)",
						]; //41,136
						$buttons[] = [
							'text' => "アカシアの階段 §e(￥30/個)",
						];//42,163
						$buttons[] = [
							'text' => "ダークオークの階段 §e(￥30/個)",
						];//43,164
						$buttons[] = [
							'text' => "オークの半ブロック §e(￥3/個)",
						]; //44,158
						$buttons[] = [
							'text' => "トウヒの半ブロック §e(￥3/個)",
						]; //45,158:1
						$buttons[] = [
							'text' => "白樺の半ブロック §e(￥3/個)",
						]; //46,158:2
						$buttons[] = [
							'text' => "ジャングルの半ブロック §e(￥3/個)",
						];//47,158:3
						$buttons[] = [
							'text' => "アカシアの半ブロック §e(￥3/個)",
						];//48,158:4
						$buttons[] = [
							'text' => "ダークオークの半ブロック §e(￥3/個)",
						]; //49,158:5
						$buttons[] = [
							'text' => "オークのフェンス §e(￥15/個)",
						]; //50,85
						$buttons[] = [
							'text' => "トウヒのフェンス §e(￥15/個)",
						]; //51,85:1
						$buttons[] = [
							'text' => "白樺のフェンス §e(￥15/個)",
						]; //52,85:2
						$buttons[] = [
							'text' => "ジャングルのフェンス §e(￥15/個)",
						]; //53,85:3
						$buttons[] = [
							'text' => "アカシアのフェンス §e(￥15/個)",
						]; //54,85:4
						$buttons[] = [
							'text' => "ダークオークのフェンス §e(￥15/個)",
						]; //55,85:5
						$buttons[] = [
							'text' => "オークのフェンスゲート §e(￥25/個)",
						]; //56,107
						$buttons[] = [
							'text' => "トウヒのフェンスゲート §e(￥25/個)",
						]; //57,183
						$buttons[] = [
							'text' => "白樺のフェンスゲート §e(￥25/個)",
						]; //58,184
						$buttons[] = [
							'text' => "ジャングルのフェンスゲート §e(￥25/個)",
						]; //59,185
						$buttons[] = [
							'text' => "ダークオークのフェンスゲート §e(￥25/個)",
						]; //60,186
						$buttons[] = [
							'text' => "アカシアのフェンスゲート §e(￥25/個)",
						]; //61,187
						$buttons[] = [
							'text' => "オークのドア §e(￥30/個)",
						]; //62,324
						$buttons[] = [
							'text' => "トウヒのドア §e(￥30/個)",
						]; //63,193
						$buttons[] = [
							'text' => "白樺のドア §e(￥30/個)",
						]; //64,194
						$buttons[] = [
							'text' => "ジャングルのドア §e(￥30/個)",
						]; //65,195
						$buttons[] = [
							'text' => "アカシアのドア §e(￥30/個)",
						]; //66,196
						$buttons[] = [
							'text' => "ダークオークのドア §e(￥30/個)",
						]; //67,197
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 3:
						$this->Main->itiji[$name] = 3;
						$cas = "砂系";
						$buttons[] = [
							'text' => "ガラス §e(￥20/個)",
						]; //68,20
						$buttons[] = [
							'text' => "板ガラス §e(￥10/個)",
						]; //69,102
						$buttons[] = [
							'text' => "砂 §e(￥10/個)",
						];//70,12
						$buttons[] = [
							'text' => "砂岩 §e(￥10/個)",
						]; //71,24
						$buttons[] = [
							'text' => "模様入り砂岩 §e(￥10/個)",
						];//72,24:1
						$buttons[] = [
							'text' => "カットされた砂岩 §e(￥10/個)",
						];//73,24:2
						$buttons[] = [
							'text' => "砂岩の半ブロック §e(￥5/個)",
						]; //74,44:1
						$buttons[] = [
							'text' => "砂岩の階段 §e(￥60/個)",
						]; //75,128
						$buttons[] = [
							'text' => "赤い砂 §e(￥10/個)",
						];//76,12:1
						$buttons[] = [
							'text' => "赤い砂岩 §e(￥10/個)",
						]; //77,179
						$buttons[] = [
							'text' => "模様入り赤い砂岩 §e(￥10/個)",
						];//78,179:1
						$buttons[] = [
							'text' => "カットされた赤い砂岩 §e(￥10/個)",
						];//79,179:2
						$buttons[] = [
							'text' => "赤砂岩の半ブロック §e(￥5/個)",
						]; //80,182
						$buttons[] = [
							'text' => "赤砂岩の階段 §e(￥60/個)",
						]; //81,180
						$buttons[] = [
							'text' => "砂利 §e(￥10/個)",
						]; //82,13
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 4:
						$this->Main->itiji[$name] = 4;
						$cas = "羊毛系";
						$buttons[] = [
							'text' => "白のウール §e(￥20/個)",
						];//83,35
						$buttons[] = [
							'text' => "オレンジのウール §e(￥20/個)",
						]; //84,35:1
						$buttons[] = [
							'text' => "赤紫のウール §e(￥20/個)",
						]; //85,35:2
						$buttons[] = [
							'text' => "空色のウール §e(￥20/個)",
						]; //86,35:3
						$buttons[] = [
							'text' => "黄色のウール §e(￥20/個)",
						]; //87,35:4
						$buttons[] = [
							'text' => "黄緑のウール §e(￥20/個)",
						]; //88,35:5
						$buttons[] = [
							'text' => "ピンクのウール §e(￥20/個)",
						];//89,35:6
						$buttons[] = [
							'text' => "灰色のウール §e(￥20/個)",
						]; //90,35:7
						$buttons[] = [
							'text' => "薄灰色のウール §e(￥20/個)",
						]; //91,35:8
						$buttons[] = [
							'text' => "水色のウール §e(￥20/個)",
						]; //92,35:9
						$buttons[] = [
							'text' => "紫のウール §e(￥20/個)",
						]; //93,35:10
						$buttons[] = [
							'text' => "青のウール §e(￥20/個)",
						]; //94,35:11
						$buttons[] = [
							'text' => "茶色のウール §e(￥20/個)",
						]; //95,35:12
						$buttons[] = [
							'text' => "緑のウール §e(￥20/個)",
						]; //96,35:13
						$buttons[] = [
							'text' => "赤のウール §e(￥20/個)",
						]; //97,35:14
						$buttons[] = [
							'text' => "黒のウール §e(￥20/個)",
						]; //98,35:15
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 5:
						$this->Main->itiji[$name] = 5;
						$cas = "花系";
						$buttons[] = [
							'text' => "§r蒲公英 §e(￥50/個)",
						]; //99,37
						$buttons[] = [
							'text' => "ポピー §e(￥50/個)",
						]; //100,38
						$buttons[] = [
							'text' => "翡翠蘭 §e(￥50/個)",
						]; //101,38:1
						$buttons[] = [
							'text' => "アリウム §e(￥50/個)",
						];//102,38:2
						$buttons[] = [
							'text' => "ヒナソウ §e(￥50/個)",
						]; //103,38:3
						$buttons[] = [
							'text' => "赤のチューリップ §e(￥50/個)",
						]; //104,38:4
						$buttons[] = [
							'text' => "オレンジのチューリップ §e(￥50/個)",
						]; //105,38:5
						$buttons[] = [
							'text' => "白のチューリップ §e(￥50/個)",
						]; //106,38:6
						$buttons[] = [
							'text' => "ピンクのチューリップ §e(￥50/個)",
						]; //107,38;7
						$buttons[] = [
							'text' => "フランスギク §e(￥50/個)",
						]; //108,38,8
						$buttons[] = [
							'text' => "向日葵 §e(￥50/個)",
						]; //109,175
						$buttons[] = [
							'text' => "ライラック §e(￥50/個)",
						]; //110,175:1
						$buttons[] = [
							'text' => "バラ §e(￥50/個)",
						]; //111,175:4
						$buttons[] = [
							'text' => "茶色のきのこ §e(￥50/個)",
						]; //112,39
						$buttons[] = [
							'text' => "赤いきのこ §e(￥50/個)",
						];//113,40
						$buttons[] = [
							'text' => "サボテン §e(￥100/個)",
						]; //114,81
						$buttons[] = [
							'text' => "睡蓮 §e(￥50/個)",
						];//115,111
						$buttons[] = [
							'text' => "かぼちゃ §e(￥50/個)",
						]; //116,86
						$buttons[] = [
							'text' => "茶色のきのこブロック §e(￥50/個)",
						];//117,99
						$buttons[] = [
							'text' => "赤色のきのこブロック §e(￥50/個)",
						]; //118,100
						$buttons[] = [
							'text' => "スイカ §e(￥20/個)",
						]; //119,103
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 6:
						$this->Main->itiji[$name] = 6;
						$cas = "ネザー系";
						$buttons[] = [
							'text' => "ネザークオーツ §e(￥30/個)",
						]; //120,153
						$buttons[] = [
							'text' => "クォーツのブロック §e(￥30/個)",
						]; //121,155
						$buttons[] = [
							'text' => "模様のあるクォーツブロック §e(￥30/個)",
						]; //122,155:1
						$buttons[] = [
							'text' => "柱状のクォーツブロック §e(￥30/個)",
						]; //123,155:2
						$buttons[] = [
							'text' => "クォーツの半ブロック §e(￥15/個)",
						]; //124,44:7
						$buttons[] = [
							'text' => "クォーツの階段 §e(￥180/個)",
						]; //125,126
						$buttons[] = [
							'text' => "ネザーレンガ §e(￥20/個)",
						]; //126,112
						$buttons[] = [
							'text' => "ネザーレンガのフェンス §e(￥100/個)",
						];//127,113
						$buttons[] = [
							'text' => "ネザーレンガの階段 §e(￥120/個)",
						]; //128,114
						$buttons[] = [
							'text' => "ネザーレンガの半ブロック §e(￥10/個)",
						]; //129,44:6
						$buttons[] = [
							'text' => "ネザーラック §e(￥20/個)",
						];//130,87
						$buttons[] = [
							'text' => "ソウルサンド §e(￥50/個)",
						];//131,88
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 7:
						$this->Main->itiji[$name] = 7;
						$cas = "テラコッタ系";
						$buttons[] = [
							'text' => "白のテラコッタ §e(￥6/個)",
						]; //157,159
						$buttons[] = [
							'text' => "オレンジのテラコッタ §e(￥6/個)",
						]; //158,159:1
						$buttons[] = [
							'text' => "赤紫のテラコッタ §e(￥6/個)",
						]; //159,159:2
						$buttons[] = [
							'text' => "水色のテラコッタ §e(￥6/個)",
						]; //160,159:3
						$buttons[] = [
							'text' => "黄色のテラコッタ §e(￥6/個)",
						]; //161,159:4
						$buttons[] = [
							'text' => "黄緑のテラコッタ §e(￥6/個)",
						]; //162,159:5
						$buttons[] = [
							'text' => "ピンクのテラコッタ §e(￥6/個)",
						]; //163,159:6
						$buttons[] = [
							'text' => "灰色のテラコッタ §e(￥6/個)",
						]; //164,159:7
						$buttons[] = [
							'text' => "薄灰色のテラコッタ §e(￥6/個)",
						]; //165,159:8
						$buttons[] = [
							'text' => "空色のテラコッタ §e(￥6/個)",
						]; //166,159:9
						$buttons[] = [
							'text' => "紫のテラコッタ §e(￥6/個)",
						]; //167,159:10
						$buttons[] = [
							'text' => "青のテラコッタ §e(￥6/個)",
						]; //168,159:11
						$buttons[] = [
							'text' => "茶色のテラコッタ §e(￥6/個)",
						]; //169,159:12
						$buttons[] = [
							'text' => "緑のテラコッタ §e(￥6/個)",
						]; //170,159:13
						$buttons[] = [
							'text' => "赤のテラコッタ §e(￥6/個)",
						]; //171,159:14
						$buttons[] = [
							'text' => "黒のテラコッタ §e(￥6/個)",
						]; //172,159:15
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 8:
						$this->Main->itiji[$name] = 8;
						$cas = "コンクリート系";
						$buttons[] = [
							'text' => "白色のコンクリート §e(￥30/個)",
						]; //199,251
						$buttons[] = [
							'text' => "橙色のコンクリート §e(￥30/個)",
						]; //200,251:1
						$buttons[] = [
							'text' => "赤紫色のコンクリート §e(￥30/個)",
						]; //201,251:2
						$buttons[] = [
							'text' => "空色のコンクリート §e(￥30/個)",
						]; //202,251:3
						$buttons[] = [
							'text' => "黄色のコンクリート §e(￥30/個)",
						]; //203,251:4
						$buttons[] = [
							'text' => "黄緑色のコンクリート §e(￥30/個)",
						]; //204,251:5
						$buttons[] = [
							'text' => "桃色のコンクリート §e(￥30/個)",
						]; //205,251:6
						$buttons[] = [
							'text' => "灰色のコンクリート §e(￥30/個)",
						]; //206,251:7
						$buttons[] = [
							'text' => "薄灰色のコンクリート §e(￥30/個)",
						]; //207,251:8
						$buttons[] = [
							'text' => "青緑色のコンクリート §e(￥30/個)",
						]; //208,251:9
						$buttons[] = [
							'text' => "紫色のコンクリート §e(￥30/個)",
						]; //209,251:10
						$buttons[] = [
							'text' => "青色のコンクリート §e(￥30/個)",
						]; //210,251:11
						$buttons[] = [
							'text' => "茶色のコンクリート §e(￥30/個)",
						]; //211,251:12
						$buttons[] = [
							'text' => "緑色のコンクリート §e(￥30/個)",
						]; //212,251:13
						$buttons[] = [
							'text' => "赤色のコンクリート §e(￥30/個)",
						]; //213,251:14
						$buttons[] = [
							'text' => "黒色のコンクリート §e(￥30/個)",
						]; //214,251:15
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 9:
						$this->Main->itiji[$name] = 9;
						$cas = "ステンドグラス系";
						$buttons[] = [
							'text' => "白色のステンドグラス §e(￥30/個)",
						]; //215,241
						$buttons[] = [
							'text' => "橙色のステンドグラス §e(￥30/個)",
						]; //216,241:1
						$buttons[] = [
							'text' => "赤紫色のステンドグラス §e(￥30/個)",
						]; //217,241:2
						$buttons[] = [
							'text' => "空色のステンドグラス §e(￥30/個)",
						]; //218,241:3
						$buttons[] = [
							'text' => "黄色のステンドグラス §e(￥30/個)",
						]; //219,241:4
						$buttons[] = [
							'text' => "黄緑色のステンドグラス §e(￥30/個)",
						]; //220,241:5
						$buttons[] = [
							'text' => "桃色のステンドグラス §e(￥30/個)",
						]; //221,241:6
						$buttons[] = [
							'text' => "灰色のステンドグラス §e(￥30/個)",
						]; //222,241:7
						$buttons[] = [
							'text' => "薄灰色のステンドグラス §e(￥30/個)",
						]; //223,241:8
						$buttons[] = [
							'text' => "青緑色のステンドグラス §e(￥30/個)",
						]; //224,241:9
						$buttons[] = [
							'text' => "紫色のステンドグラス §e(￥30/個)",
						]; //225,241:10
						$buttons[] = [
							'text' => "青色のステンドグラス §e(￥30/個)",
						]; //226,241:11
						$buttons[] = [
							'text' => "茶色のステンドグラス §e(￥30/個)",
						]; //227,241:12
						$buttons[] = [
							'text' => "緑色のステンドグラス §e(￥30/個)",
						]; //228,241:13
						$buttons[] = [
							'text' => "赤色のステンドグラス §e(￥30/個)",
						]; //229,241:14
						$buttons[] = [
							'text' => "黒色のステンドグラス §e(￥30/個)",
						]; //230,241:15
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 10:
						$this->Main->itiji[$name] = 10;
						$cas = "ステンドグラス窓系";
						$buttons[] = [
							'text' => "白色のステンドグラス窓 §e(￥10/個)",
						]; //231,160
						$buttons[] = [
							'text' => "橙色のステンドグラス窓 §e(￥10/個)",
						]; //232,160:1
						$buttons[] = [
							'text' => "赤紫色のステンドグラス窓 §e(￥10/個)",
						]; //233,160:2
						$buttons[] = [
							'text' => "空色のステンドグラス窓 §e(￥10/個)",
						]; //234,160:3
						$buttons[] = [
							'text' => "黄色のステンドグラス窓 §e(￥10/個)",
						]; //235,160:4
						$buttons[] = [
							'text' => "黄緑色のステンドグラス窓 §e(￥10/個)",
						]; //236,160:5
						$buttons[] = [
							'text' => "桃色のステンドグラス窓 §e(￥10/個)",
						]; //237,160:6
						$buttons[] = [
							'text' => "灰色のステンドグラス窓 §e(￥10/個)",
						]; //238,160:7
						$buttons[] = [
							'text' => "薄灰色のステンドグラス窓 §e(￥10/個)",
						]; //239,160:8
						$buttons[] = [
							'text' => "青緑色のステンドグラス窓 §e(￥10/個)",
						]; //240,160:9
						$buttons[] = [
							'text' => "紫色のステンドグラス窓 §e(￥10/個)",
						]; //241,160:10
						$buttons[] = [
							'text' => "青色のステンドグラス窓 §e(￥10/個)",
						]; //242,160:11
						$buttons[] = [
							'text' => "茶色のステンドグラス窓 §e(￥10/個)",
						]; //243,160:12
						$buttons[] = [
							'text' => "緑色のステンドグラス窓 §e(￥10/個)",
						]; //244,160:13
						$buttons[] = [
							'text' => "赤色のステンドグラス窓 §e(￥10/個)",
						]; //245,160:14
						$buttons[] = [
							'text' => "黒色のステンドグラス窓 §e(￥10/個)",
						]; //246,160:15
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 11:
						$this->Main->itiji[$name] = 11;
						$cas = "ご飯";
						$buttons[] = [
							'text' => "りんご §e(￥20/個)",
						]; //176,260
						$buttons[] = [
							'text' => "豚肉 §e(￥20/個)",
						]; //177,319
						$buttons[] = [
							'text' => "焼豚 §e(￥40/個)",
						]; //178,320
						$buttons[] = [
							'text' => "金りんご §e(￥100/個)",
						]; //179,322
						$buttons[] = [
							'text' => "たら §e(￥30/個)",
						];//180,349:1
						$buttons[] = [
							'text' => "焼きたら §e(￥30/個)",
						]; //181,350:1
						$buttons[] = [
							'text' => "クマノミ §e(￥販売中止/個)",
						]; //182,349:2
						$buttons[] = [
							'text' => "ふぐ §e(￥販売中止/個)",
						]; //183,349:3
						$buttons[] = [
							'text' => "魚 §e(￥販売中止/個)",
						]; //184,349
						$buttons[] = [
							'text' => "焼き魚 §e(￥販売中止/個)",
						]; //185,350
						$buttons[] = [
							'text' => "牛肉 §e(￥50/個)",
						]; //186,363
						$buttons[] = [
							'text' => "ステーキ §e(￥100/個)",
						]; //187,364
						$buttons[] = [
							'text' => "生の鳥肉 §e(￥40/個)",
						]; //188,365
						$buttons[] = [
							'text' => "焼き鳥 §e(￥80/個)",
						]; //189,366
						$buttons[] = [
							'text' => "金の西瓜 §e(￥80/個)",
						]; //190,382
						$buttons[] = [
							'text' => "人参 §e(￥30/個)",
						]; //191,391
						$buttons[] = [
							'text' => "馬鈴薯 §e(￥30/個)",
						]; //192,392
						$buttons[] = [
							'text' => "ベイクドポテト §e(￥60/個)",
						]; //193,393
						$buttons[] = [
							'text' => "金の人参 §e(￥100/個)",
						]; //194,396
						$buttons[] = [
							'text' => "生のウサギ肉 §e(￥15/個)",
						]; //195,411
						$buttons[] = [
							'text' => "焼きウサギ §e(￥30/個)",
						]; //196,412
						$buttons[] = [
							'text' => "パンプキンパイ §e(￥60/個)",
						]; //197,400
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 12:
						$this->Main->itiji[$name] = 12;
						$cas = "武器系";
						$buttons[] = [
							'text' => "弓 §e(￥300/個)",
						];//173,261
						$buttons[] = [
							'text' => "矢 §e(￥3/個)",
						]; //174,262
						$buttons[] = [
							'text' => "雪玉 §e(￥12/個)",
						]; //175,332
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
					case 13:
						$this->Main->itiji[$name] = 13;
						$cas = "その他";
						$buttons[] = [
							'text' => "レンガ §e(￥12/個)",
						]; //132,45
						$buttons[] = [
							'text' => "レンガの階段 §e(￥24/個)",
						]; //133,108
						$buttons[] = [
							'text' => "レンガの半ブロック §e(￥6/個)",
						];//134,44:4
						$buttons[] = [
							'text' => "本棚 §e(￥30/個)",
						];//135,47
						$buttons[] = [
							'text' => "松明 §e(￥5/個)",
						];//136,50
						$buttons[] = [
							'text' => "チェスト §e(￥80/個)",
						]; //137,54
						$buttons[] = [
							'text' => "作業台 §e(￥40/個)",
						]; //138,58
						$buttons[] = [
							'text' => "かまど §e(￥80/個)",
						]; //139,61
						$buttons[] = [
							'text' => "看板 §e(￥70/個)",
						]; //140,323
						$buttons[] = [
							'text' => "額 §e(￥80/個)",
						]; //141,389
						$buttons[] = [
							'text' => "はしご §e(￥45/個)",
						]; //141,65
						$buttons[] = [
							'text' => "鉄のドア §e(￥1000/個)",
						]; //142,330
						$buttons[] = [
							'text' => "雪ブロック §e(￥50/個)",
						]; //143,80
						$buttons[] = [
							'text' => "粘土 §e(￥3/個)",
						]; //144,82
						$buttons[] = [
							'text' => "グローストーン §e(￥30/個)",
						]; //145,89
						$buttons[] = [
							'text' => "ジャック・オ・ランタン §e(￥80/個)",
						]; //146,91
						$buttons[] = [
							'text' => "オークのトラップドア §e(￥60/個)",
						]; //147,96
						$buttons[] = [
							'text' => "鉄格子 §e(￥400/個)",
						]; //148,101
						$buttons[] = [
							'text' => "エンドストーン §e(￥30/個)",
						]; //149,121
						$buttons[] = [
							'text' => "レッドストーンランプ §e(￥80/個)",
						]; //150,124
						$buttons[] = [
							'text' => "エンドストーンレンガ §e(￥30/個)",
						];//151,206
						$buttons[] = [
							'text' => "エンドロッド §e(￥100/個)",
						];//152,208
						$buttons[] = [
							'text' => "ストーンカッター §e(￥50/個)",
						]; //153,245
						$buttons[] = [
							'text' => "氷 §e(￥50/個)",
						]; //154,174
						$buttons[] = [
							'text' => "スポンジ §e(￥50/個)",
						]; //155,19
						$buttons[] = [
							'text' => "コンパス §e(￥200/個)",
						];//156,345
						$buttons[] = [
							'text' => "暗黒茸ブロック §e(￥20/個)",
						]; //247,
						$buttons[] = [
							'text' => "亀の帽子 §e(￥30000/個)",
						]; //248
						$buttons[] = [
							'text' => "シーランタン §e(￥50/個)",
						]; //198,438:6
						$buttons[] = [
							'text' => "前のページへ",
						];
						break;
				}

				$pk = new ModalFormRequestPacket();
				$id = 97643;
				$pk->formId = $id;
				$data = [
					"type" => "form",
					"title" => "§d§lITEMSHOP",
					"content" => "§a{$cas}欄です。欲しいアイテムを選択してください\n§a現在の所持金 → §e{$mymoney}￥",
					"buttons" => $buttons
				];
				$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
				$player->dataPacket($pk);

				break;//shop商品選び

			case 97643:
				$this->Main->itemname[$name] = null;
				$this->Main->itemamount[$name] = null;
				$this->Main->itemprice[$name] = null;
				switch ($this->Main->itiji[$name]) {
					case 0:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 1;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "石";
								$this->Main->itemprice[$name] = 10;
								break;
							case 1:
								$this->Main->itemid[$name] = 1;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "花崗岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 2:
								$this->Main->itemid[$name] = 1;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "磨かれた花崗岩";
								$this->Main->itemprice[$name] = 20;
								break;
							case 3:
								$this->Main->itemid[$name] = 1;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "閃緑岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 4:
								$this->Main->itemid[$name] = 1;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "磨かれた閃緑岩";
								$this->Main->itemprice[$name] = 20;
								break;
							case 5:
								$this->Main->itemid[$name] = 1;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "安山岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 6:
								$this->Main->itemid[$name] = 1;
								$this->Main->itemdamage[$name] = 6;
								$this->Main->itemname[$name] = "磨かれた安山岩";
								$this->Main->itemprice[$name] = 20;
								break;
							case 7:
								$this->Main->itemid[$name] = 4;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "丸石";
								$this->Main->itemprice[$name] = 5;
								break;
							case 8:
								$this->Main->itemid[$name] = 48;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "苔の生えた丸石";
								$this->Main->itemprice[$name] = 15;
								break;
							case 9:
								$this->Main->itemid[$name] = 98;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "石レンガ";
								$this->Main->itemprice[$name] = 10;
								break;
							case 10:
								$this->Main->itemid[$name] = 98;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "苔の生えた石レンガ";
								$this->Main->itemprice[$name] = 10;
								break;
							case 11:
								$this->Main->itemid[$name] = 98;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "ヒビの生えた石レンガ";
								$this->Main->itemprice[$name] = 10;
								break;
							case 12:
								$this->Main->itemid[$name] = 98;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "模様のある石レンガ";
								$this->Main->itemprice[$name] = 10;
								break;
							case 13:
								$this->Main->itemid[$name] = 67;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "丸石の階段";
								$this->Main->itemprice[$name] = 30;
								break;
							case 14:
								$this->Main->itemid[$name] = 44;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "石の半ブロック";
								$this->Main->itemprice[$name] = 5;
								break;
							case 15:
								$this->Main->itemid[$name] = 44;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "丸石の半ブロック";
								$this->Main->itemprice[$name] = 2;
								break;
							case 16:
								$this->Main->itemid[$name] = 44;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "石レンガの半ブロック";
								$this->Main->itemprice[$name] = 5;
								break;
							case 17:
								$this->Main->itemid[$name] = 109;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "石レンガの階段";
								$this->Main->itemprice[$name] = 40;
								break;
							case 18:
								$this->Main->itemid[$name] = 139;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "丸石のフェンス";
								$this->Main->itemprice[$name] = 20;
								break;
							case 19:
								$this->Main->itemid[$name] = 139;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "苔の生えた丸石フェンス";
								$this->Main->itemprice[$name] = 20;
								break;
							case 20:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 1:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 2;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "草ブロック";
								$this->Main->itemprice[$name] = 10;
								break;
							case 1:
								$this->Main->itemid[$name] = 3;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "土";
								$this->Main->itemprice[$name] = 5;
								break;
							case 2:
								$this->Main->itemid[$name] = 3;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "変化しない土";
								$this->Main->itemprice[$name] = 10;
								break;
							case 3:
								$this->Main->itemid[$name] = 198;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "草の道";
								$this->Main->itemprice[$name] = 10;
								break;
							case 4:
								$this->Main->itemid[$name] = 3;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "ポドソル";
								$this->Main->itemprice[$name] = 20;
								break;
							case 5:
								$this->Main->itemid[$name] = 110;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "菌糸";
								$this->Main->itemprice[$name] = 20;
								break;
							case 6:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 2:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 17;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "オークの原木";
								$this->Main->itemprice[$name] = 20;
								break;
							case 1:
								$this->Main->itemid[$name] = 17;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "トウヒの原木";
								$this->Main->itemprice[$name] = 20;
								break;
							case 2:
								$this->Main->itemid[$name] = 17;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "白樺の原木";
								$this->Main->itemprice[$name] = 20;
								break;
							case 3:
								$this->Main->itemid[$name] = 17;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "ジャングルの原木";
								$this->Main->itemprice[$name] = 20;
								break;
							case 4:
								$this->Main->itemid[$name] = 162;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "アカシアの原木";
								$this->Main->itemprice[$name] = 20;
								break;
							case 5:
								$this->Main->itemid[$name] = 162;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "ダークオークの原木";
								$this->Main->itemprice[$name] = 20;
								break;
							case 6:
								$this->Main->itemid[$name] = 5;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "オークの木材";
								$this->Main->itemprice[$name] = 5;
								break;
							case 7:
								$this->Main->itemid[$name] = 5;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "トウヒの木材";
								$this->Main->itemprice[$name] = 5;
								break;
							case 8:
								$this->Main->itemid[$name] = 5;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "白樺の木材";
								$this->Main->itemprice[$name] = 5;
								break;
							case 9:
								$this->Main->itemid[$name] = 5;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "ジャングルの木の木材";
								$this->Main->itemprice[$name] = 5;
								break;
							case 10:
								$this->Main->itemid[$name] = 5;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "アカシアの木材";
								$this->Main->itemprice[$name] = 5;
								break;
							case 11:
								$this->Main->itemid[$name] = 5;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "ダークオークの木材";
								$this->Main->itemprice[$name] = 5;
								break;
							case 12:
								$this->Main->itemid[$name] = 53;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "オークの階段";
								$this->Main->itemprice[$name] = 30;
								break;
							case 13:
								$this->Main->itemid[$name] = 134;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "松の階段";
								$this->Main->itemprice[$name] = 30;
								break;
							case 14:
								$this->Main->itemid[$name] = 135;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "白樺の階段";
								$this->Main->itemprice[$name] = 30;
								break;
							case 15:
								$this->Main->itemid[$name] = 136;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ジャングルの階段";
								$this->Main->itemprice[$name] = 30;
								break;
							case 16:
								$this->Main->itemid[$name] = 163;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "アカシアの階段";
								$this->Main->itemprice[$name] = 30;
								break;
							case 17:
								$this->Main->itemid[$name] = 164;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ダークオークの階段";
								$this->Main->itemprice[$name] = 30;
								break;
							case 18:
								$this->Main->itemid[$name] = 158;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "オークの半ブロック";
								$this->Main->itemprice[$name] = 3;
								break;
							case 19:
								$this->Main->itemid[$name] = 158;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "トウヒの半ブロック";
								$this->Main->itemprice[$name] = 3;
								break;
							case 20:
								$this->Main->itemid[$name] = 158;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "白樺の半ブロック";
								$this->Main->itemprice[$name] = 3;
								break;
							case 21:
								$this->Main->itemid[$name] = 158;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "ジャングルの半ブロック";
								$this->Main->itemprice[$name] = 3;
								break;
							case 22:
								$this->Main->itemid[$name] = 158;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "アカシアの半ブロック";
								$this->Main->itemprice[$name] = 3;
								break;
							case 23:
								$this->Main->itemid[$name] = 158;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "ダークオークの半ブロック";
								$this->Main->itemprice[$name] = 3;
								break;
							case 24:
								$this->Main->itemid[$name] = 85;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "オークのフェンス";
								$this->Main->itemprice[$name] = 15;
								break;
							case 25:
								$this->Main->itemid[$name] = 85;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "トウヒのフェンス";
								$this->Main->itemprice[$name] = 15;
								break;
							case 26:
								$this->Main->itemid[$name] = 85;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "白樺のフェンス";
								$this->Main->itemprice[$name] = 15;
								break;
							case 27:
								$this->Main->itemid[$name] = 85;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "ジャングルの半ブロック";
								$this->Main->itemprice[$name] = 15;
								break;
							case 28:
								$this->Main->itemid[$name] = 85;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "アカシアのフェンス";
								$this->Main->itemprice[$name] = 15;
								break;
							case 29:
								$this->Main->itemid[$name] = 85;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "ダークオークのフェンス";
								$this->Main->itemprice[$name] = 15;
								break;
							case 30:
								$this->Main->itemid[$name] = 107;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "オークのフェンスゲート";
								$this->Main->itemprice[$name] = 25;
								break;
							case 31:
								$this->Main->itemid[$name] = 183;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "トウヒのフェンスゲート";
								$this->Main->itemprice[$name] = 25;
								break;
							case 32:
								$this->Main->itemid[$name] = 184;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "白樺のフェンスゲート";
								$this->Main->itemprice[$name] = 25;
								break;
							case 33:
								$this->Main->itemid[$name] = 185;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ジャングルのフェンスゲート";
								$this->Main->itemprice[$name] = 25;
								break;
							case 34:
								$this->Main->itemid[$name] = 186;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ダークオークのフェンスゲート";
								$this->Main->itemprice[$name] = 25;
								break;
							case 35:
								$this->Main->itemid[$name] = 187;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "アカシアのフェンスゲート";
								$this->Main->itemprice[$name] = 25;
								break;
							case 36:
								$this->Main->itemid[$name] = 324;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "オークのドア";
								$this->Main->itemprice[$name] = 30;
								break;
							case 37:
								$this->Main->itemid[$name] = 193;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "トウヒのドア";
								$this->Main->itemprice[$name] = 30;
								break;
							case 38:
								$this->Main->itemid[$name] = 194;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "白樺のドア";
								$this->Main->itemprice[$name] = 30;
								break;
							case 39:
								$this->Main->itemid[$name] = 195;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ジャングルのドア";
								$this->Main->itemprice[$name] = 30;
								break;
							case 40:
								$this->Main->itemid[$name] = 196;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "アカシアのドア";
								$this->Main->itemprice[$name] = 30;
								break;
							case 41:
								$this->Main->itemid[$name] = 197;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ダークオークのドア";
								$this->Main->itemprice[$name] = 30;
								break;
							case 42:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 3:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 20;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ガラス";
								$this->Main->itemprice[$name] = 20;
								break;
							case 1:
								$this->Main->itemid[$name] = 102;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "板ガラス";
								$this->Main->itemprice[$name] = 10;
								break;
							case 2:
								$this->Main->itemid[$name] = 12;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "砂";
								$this->Main->itemprice[$name] = 10;
								break;
							case 3:
								$this->Main->itemid[$name] = 24;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "砂岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 4:
								$this->Main->itemid[$name] = 24;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "模様入り砂岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 5:
								$this->Main->itemid[$name] = 24;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "カットされた砂岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 6:
								$this->Main->itemid[$name] = 44;
								$this->Main->itemdamage[$name] = 9;
								$this->Main->itemname[$name] = "砂岩の半ブロック";
								$this->Main->itemprice[$name] = 5;
								break;
							case 7:
								$this->Main->itemid[$name] = 128;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "砂岩の階段";
								$this->Main->itemprice[$name] = 60;
								break;
							case 8:
								$this->Main->itemid[$name] = 12;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "赤い砂";
								$this->Main->itemprice[$name] = 10;
								break;
							case 9:
								$this->Main->itemid[$name] = 179;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "赤い砂岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 10:
								$this->Main->itemid[$name] = 179;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "模様入り赤い砂岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 11:
								$this->Main->itemid[$name] = 179;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "カットされた赤い砂岩";
								$this->Main->itemprice[$name] = 10;
								break;
							case 12:
								$this->Main->itemid[$name] = 182;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "赤砂岩の半ブロック";
								$this->Main->itemprice[$name] = 5;
								break;
							case 13:
								$this->Main->itemid[$name] = 180;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "赤砂岩の階段";
								$this->Main->itemprice[$name] = 60;
								break;
							case 14:
								$this->Main->itemid[$name] = 13;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "砂利";
								$this->Main->itemprice[$name] = 10;
								break;
							case 15:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 4:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "白のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 1:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "オレンジのウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 2:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "赤紫のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 3:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "空色のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 4:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "黄色のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 5:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "黄緑のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 6:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 6;
								$this->Main->itemname[$name] = "ピンクのウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 7:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 7;
								$this->Main->itemname[$name] = "灰色のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 8:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 8;
								$this->Main->itemname[$name] = "薄灰色のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 9:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 9;
								$this->Main->itemname[$name] = "水色のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 10:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 10;
								$this->Main->itemname[$name] = "紫のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 11:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 11;
								$this->Main->itemname[$name] = "青のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 12:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 12;
								$this->Main->itemname[$name] = "茶色のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 13:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 13;
								$this->Main->itemname[$name] = "緑のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 14:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 14;
								$this->Main->itemname[$name] = "赤のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 15:
								$this->Main->itemid[$name] = 35;
								$this->Main->itemdamage[$name] = 15;
								$this->Main->itemname[$name] = "黒のウール";
								$this->Main->itemprice[$name] = 20;
								break;
							case 16:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 5:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 37;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "蒲公英";
								$this->Main->itemprice[$name] = 50;
								break;
							case 1:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ポピー";
								$this->Main->itemprice[$name] = 50;
								break;
							case 2:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "翡翠蘭";
								$this->Main->itemprice[$name] = 50;
								break;
							case 3:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "アリウム";
								$this->Main->itemprice[$name] = 50;
								break;
							case 4:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "ヒナソウ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 5:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "赤のチューリップ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 6:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "ピンクのチューリップ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 7:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 6;
								$this->Main->itemname[$name] = "白のチューリップ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 8:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 7;
								$this->Main->itemname[$name] = "ピンクのチューリップ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 9:
								$this->Main->itemid[$name] = 38;
								$this->Main->itemdamage[$name] = 8;
								$this->Main->itemname[$name] = "フランスギク";
								$this->Main->itemprice[$name] = 50;
								break;
							case 10:
								$this->Main->itemid[$name] = 175;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "向日葵";
								$this->Main->itemprice[$name] = 50;
								break;
							case 11:
								$this->Main->itemid[$name] = 175;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "ライラック";
								$this->Main->itemprice[$name] = 50;
								break;
							case 12:
								$this->Main->itemid[$name] = 175;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "バラ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 13:
								$this->Main->itemid[$name] = 39;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "茶色のきのこ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 14:
								$this->Main->itemid[$name] = 40;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "赤いきのこ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 15:
								$this->Main->itemid[$name] = 81;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "サボテン";
								$this->Main->itemprice[$name] = 100;
								break;
							case 16:
								$this->Main->itemid[$name] = 111;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "睡蓮";
								$this->Main->itemprice[$name] = 50;
								break;
							case 17:
								$this->Main->itemid[$name] = 86;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "かぼちゃ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 18:
								$this->Main->itemid[$name] = 99;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "茶色のきのこブロック";
								$this->Main->itemprice[$name] = 50;
								break;
							case 19:
								$this->Main->itemid[$name] = 100;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "赤色のきのこブロック";
								$this->Main->itemprice[$name] = 50;
								break;
							case 20:
								$this->Main->itemid[$name] = 103;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "スイカ";
								$this->Main->itemprice[$name] = 20;
								break;
							case 21:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 6:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 153;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ネザークオーツ鉱石";
								$this->Main->itemprice[$name] = 100;
								break;
							case 1:
								$this->Main->itemid[$name] = 155;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "クォーツのブロック";
								$this->Main->itemprice[$name] = 30;
								break;
							case 2:
								$this->Main->itemid[$name] = 155;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "模様のあるクォーツブロック";
								$this->Main->itemprice[$name] = 30;
								break;
							case 3:
								$this->Main->itemid[$name] = 155;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "柱状のクォーツブロック";
								$this->Main->itemprice[$name] = 30;
								break;
							case 4:
								$this->Main->itemid[$name] = 44;
								$this->Main->itemdamage[$name] = 6;
								$this->Main->itemname[$name] = "クォーツの半ブロック";
								$this->Main->itemprice[$name] = 15;
								break;
							case 5:
								$this->Main->itemid[$name] = 156;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "クォーツの階段";
								$this->Main->itemprice[$name] = 180;
								break;
							case 6:
								$this->Main->itemid[$name] = 112;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ネザーレンガ";
								$this->Main->itemprice[$name] = 20;
								break;
							case 7:
								$this->Main->itemid[$name] = 113;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ネザーレンガのフェンス";
								$this->Main->itemprice[$name] = 100;
								break;
							case 8:
								$this->Main->itemid[$name] = 114;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ネザーレンガの階段";
								$this->Main->itemprice[$name] = 120;
								break;
							case 9:
								$this->Main->itemid[$name] = 44;
								$this->Main->itemdamage[$name] = 7;
								$this->Main->itemname[$name] = "ネザーレンガの半ブロック";
								$this->Main->itemprice[$name] = 10;
								break;
							case 10:
								$this->Main->itemid[$name] = 87;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ネザーラック";
								$this->Main->itemprice[$name] = 20;
								break;
							case 11:
								$this->Main->itemid[$name] = 88;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ソウルサンド";
								$this->Main->itemprice[$name] = 50;
								break;
							case 12:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 7:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "白のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 1:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "オレンジのテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 2:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "赤紫のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 3:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "水色のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 4:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "黄色のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 5:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "黄緑のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 6:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 6;
								$this->Main->itemname[$name] = "ピンクのテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 7:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 7;
								$this->Main->itemname[$name] = "灰色のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 8:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 8;
								$this->Main->itemname[$name] = "薄灰色のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 9:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 9;
								$this->Main->itemname[$name] = "薄灰色のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 10:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 10;
								$this->Main->itemname[$name] = "紫のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 11:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 11;
								$this->Main->itemname[$name] = "青のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 12:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 12;
								$this->Main->itemname[$name] = "茶色のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 13:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 13;
								$this->Main->itemname[$name] = "緑のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 14:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 14;
								$this->Main->itemname[$name] = "赤のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 15:
								$this->Main->itemid[$name] = 159;
								$this->Main->itemdamage[$name] = 15;
								$this->Main->itemname[$name] = "黒のテラコッタ";
								$this->Main->itemprice[$name] = 6;
								break;
							case 16:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 8:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "白色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 1:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "橙色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 2:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "赤紫色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 3:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "空色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 4:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "黄色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 5:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "黄緑色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 6:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 6;
								$this->Main->itemname[$name] = "桃色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 7:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 7;
								$this->Main->itemname[$name] = "灰色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 8:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 8;
								$this->Main->itemname[$name] = "薄灰色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 9:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 9;
								$this->Main->itemname[$name] = "青緑色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 10:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 10;
								$this->Main->itemname[$name] = "紫色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 11:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 11;
								$this->Main->itemname[$name] = "青色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 12:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 12;
								$this->Main->itemname[$name] = "茶色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 13:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 13;
								$this->Main->itemname[$name] = "緑色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 14:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 14;
								$this->Main->itemname[$name] = "赤色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 15:
								$this->Main->itemid[$name] = 236;
								$this->Main->itemdamage[$name] = 15;
								$this->Main->itemname[$name] = "黒色のコンクリート";
								$this->Main->itemprice[$name] = 30;
								break;
							case 16:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 9:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "白のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 1:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "橙色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 2:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "赤紫色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 3:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "空色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 4:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "黄色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 5:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "黄緑色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 6:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 6;
								$this->Main->itemname[$name] = "桃色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 7:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 7;
								$this->Main->itemname[$name] = "灰色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 8:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 8;
								$this->Main->itemname[$name] = "薄灰色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 9:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 9;
								$this->Main->itemname[$name] = "青緑のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 10:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 10;
								$this->Main->itemname[$name] = "紫色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 11:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 11;
								$this->Main->itemname[$name] = "青色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 12:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 12;
								$this->Main->itemname[$name] = "茶色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 13:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 13;
								$this->Main->itemname[$name] = "緑色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 14:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 14;
								$this->Main->itemname[$name] = "赤色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 15:
								$this->Main->itemid[$name] = 241;
								$this->Main->itemdamage[$name] = 15;
								$this->Main->itemname[$name] = "黒色のステンドグラス";
								$this->Main->itemprice[$name] = 30;
								break;
							case 16:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 10:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "白色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 1:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "橙色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 2:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 2;
								$this->Main->itemname[$name] = "赤紫色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 3:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 3;
								$this->Main->itemname[$name] = "空色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 4:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "黄色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 5:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 5;
								$this->Main->itemname[$name] = "黄緑色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 6:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 6;
								$this->Main->itemname[$name] = "桃色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 7:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 7;
								$this->Main->itemname[$name] = "灰色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 8:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 8;
								$this->Main->itemname[$name] = "薄灰色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 9:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 9;
								$this->Main->itemname[$name] = "青緑色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 10:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 10;
								$this->Main->itemname[$name] = "紫色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 11:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 11;
								$this->Main->itemname[$name] = "青色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 12:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 12;
								$this->Main->itemname[$name] = "茶色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 13:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 13;
								$this->Main->itemname[$name] = "緑色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 14:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 14;
								$this->Main->itemname[$name] = "赤色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 15:
								$this->Main->itemid[$name] = 160;
								$this->Main->itemdamage[$name] = 15;
								$this->Main->itemname[$name] = "黒色のステンドグラス窓";
								$this->Main->itemprice[$name] = 10;
								break;
							case 16:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 11:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 260;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "青森産のりんご";
								$this->Main->itemprice[$name] = 20;
								break;
							case 1:
								$this->Main->itemid[$name] = 319;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "豚肉";
								$this->Main->itemprice[$name] = 20;
								break;
							case 2:
								$this->Main->itemid[$name] = 320;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "焼豚";
								$this->Main->itemprice[$name] = 40;
								break;
							case 3:
								$this->Main->itemid[$name] = 322;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "(中国産)金りんご";
								$this->Main->itemprice[$name] = 100;
								break;
							case 4:
								$this->Main->itemid[$name] = 349;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "(茨城県沖産)たら";
								$this->Main->itemprice[$name] = 30;
								break;
							case 5:
								$this->Main->itemid[$name] = 350;
								$this->Main->itemdamage[$name] = 1;
								$this->Main->itemname[$name] = "焼きたら";
								$this->Main->itemprice[$name] = 30;
								break;
							/*								   case 6:
																		   $this->>Main->itemid[$name] = 349;
																		   $this->>Main->itemdamage[$name] = 2;
																		   $this->>Main->itemname[$name] = "クマノミ";
																		   $this->>Main->itemprice[$name] = 20;
																		   break;
																	   case 7:
																		   $this->>Main->itemid[$name] = 349;
																		   $this->>Main->itemdamage[$name] = 3;
																		   $this->>Main->itemname[$name] = "ふぐ";
																		   $this->>Main->itemprice[$name] = 20;
																		   break;
																	   case 8:
																		   $this->>Main->itemid[$name] = 349;
																		   $this->>Main->itemdamage[$name] = 0;
																		   $this->>Main->itemname[$name] = "(日本海産)魚";
																		   $this->>Main->itemprice[$name] = 30;
																		   break;
																	   case 9:
																		   $this->>Main->itemid[$name] = 350;
																		   $this->>Main->itemdamage[$name] = 0;
																		   $this->>Main->itemname[$name] = "焼き魚";
																		   $this->>Main->itemprice[$name] = 100;
																		   break;*/
							case 10:
								$this->Main->itemid[$name] = 363;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "(アンガス)牛肉";
								$this->Main->itemprice[$name] = 50;
								break;
							case 11:
								$this->Main->itemid[$name] = 364;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ステーキ";
								$this->Main->itemprice[$name] = 100;
								break;
							case 12:
								$this->Main->itemid[$name] = 365;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "(ブラジル)生の鶏肉";
								$this->Main->itemprice[$name] = 40;
								break;
							case 13:
								$this->Main->itemid[$name] = 366;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "焼き鳥";
								$this->Main->itemprice[$name] = 80;
								break;
							case 14:
								$this->Main->itemid[$name] = 382;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "金の西瓜";
								$this->Main->itemprice[$name] = 80;
								break;
							case 15:
								$this->Main->itemid[$name] = 391;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "人参";
								$this->Main->itemprice[$name] = 30;
								break;
							case 16:
								$this->Main->itemid[$name] = 392;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "じゃがいも";
								$this->Main->itemprice[$name] = 30;
								break;
							case 17:
								$this->Main->itemid[$name] = 393;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ベイクドポテト";
								$this->Main->itemprice[$name] = 60;
								break;
							case 18:
								$this->Main->itemid[$name] = 396;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "金の人参";
								$this->Main->itemprice[$name] = 100;
								break;
							case 19:
								$this->Main->itemid[$name] = 411;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "生のウサギ肉";
								$this->Main->itemprice[$name] = 15;
								break;
							case 20:
								$this->Main->itemid[$name] = 412;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "焼きうさぎ";
								$this->Main->itemprice[$name] = 30;
								break;
							case 21:
								$this->Main->itemid[$name] = 400;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "パンプキンパイ";
								$this->Main->itemprice[$name] = 60;
								break;
							case 22:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
							default:
								$player->sendMessage("[ITEMSHOP]§c現在対応できていません。アップデートをお待ち下さい");
								return false;
								break;
						}
						break;
					case 12:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 261;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "弓";
								$this->Main->itemprice[$name] = 300;
								break;
							case 1:
								$this->Main->itemid[$name] = 262;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "矢";
								$this->Main->itemprice[$name] = 3;
								break;
							case 2:
								$this->Main->itemid[$name] = 332;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "雪玉";
								$this->Main->itemprice[$name] = 12;
								break;
							case 3:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
					case 13:
						switch ($data) {
							case 0:
								$this->Main->itemid[$name] = 45;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "レンガ";
								$this->Main->itemprice[$name] = 12;
								break;
							case 1:
								$this->Main->itemid[$name] = 108;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "レンガの階段";
								$this->Main->itemprice[$name] = 24;
								break;
							case 2:
								$this->Main->itemid[$name] = 44;
								$this->Main->itemdamage[$name] = 4;
								$this->Main->itemname[$name] = "レンガの半ブロック";
								$this->Main->itemprice[$name] = 6;
								break;
							case 3:
								$this->Main->itemid[$name] = 47;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "本棚";
								$this->Main->itemprice[$name] = 30;
								break;
							case 4:
								$this->Main->itemid[$name] = 50;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "松明";
								$this->Main->itemprice[$name] = 5;
								break;
							case 5:
								$this->Main->itemid[$name] = 54;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "チェスト";
								$this->Main->itemprice[$name] = 80;
								break;
							case 6:
								$this->Main->itemid[$name] = 58;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "作業台";
								$this->Main->itemprice[$name] = 40;
								break;
							case 7:
								$this->Main->itemid[$name] = 61;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "かまど";
								$this->Main->itemprice[$name] = 80;
								break;
							case 8:
								$this->Main->itemid[$name] = 323;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "看板";
								$this->Main->itemprice[$name] = 70;
								break;
							case 9:
								$this->Main->itemid[$name] = 389;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "額";
								$this->Main->itemprice[$name] = 80;
								break;
							case 10:
								$this->Main->itemid[$name] = 65;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "はしご";
								$this->Main->itemprice[$name] = 45;
								break;
							case 11:
								$this->Main->itemid[$name] = 330;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "鉄のドア";
								$this->Main->itemprice[$name] = 1000;
								break;
							case 12:
								$this->Main->itemid[$name] = 80;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "雪ブロック";
								$this->Main->itemprice[$name] = 50;
								break;
							case 13:
								$this->Main->itemid[$name] = 82;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "粘土";
								$this->Main->itemprice[$name] = 3;
								break;
							case 14:
								$this->Main->itemid[$name] = 89;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "グローストーン";
								$this->Main->itemprice[$name] = 30;
								break;
							case 15:
								$this->Main->itemid[$name] = 91;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ジャック・オ・ランタン";
								$this->Main->itemprice[$name] = 80;
								break;
							case 16:
								$this->Main->itemid[$name] = 96;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "オークのトラップドア";
								$this->Main->itemprice[$name] = 60;
								break;
							case 17:
								$this->Main->itemid[$name] = 101;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "鉄格子";
								$this->Main->itemprice[$name] = 400;
								break;
							case 18:
								$this->Main->itemid[$name] = 121;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "エンドストーン";
								$this->Main->itemprice[$name] = 30;
								break;
							case 19:
								$this->Main->itemid[$name] = 124;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "レッドストーンランプ";
								$this->Main->itemprice[$name] = 80;
								break;
							case 20:
								$this->Main->itemid[$name] = 206;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "エンドストーンレンガ";
								$this->Main->itemprice[$name] = 30;
								break;
							case 21:
								$this->Main->itemid[$name] = 208;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "エンドロッド";
								$this->Main->itemprice[$name] = 100;
								break;
							case 22:
								$this->Main->itemid[$name] = 245;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "ストーンカッター";
								$this->Main->itemprice[$name] = 50;
								break;
							case 23:
								$this->Main->itemid[$name] = 174;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "氷";
								$this->Main->itemprice[$name] = 50;
								break;
							case 24:
								$this->Main->itemid[$name] = 19;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "スポンジ";
								$this->Main->itemprice[$name] = 50;
								break;
							case 25:
								$this->Main->itemid[$name] = 345;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "コンパス";
								$this->Main->itemprice[$name] = 200;
								break;
							case 26:
								$this->Main->itemid[$name] = 214;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "暗黒茸ブロック";
								$this->Main->itemprice[$name] = 20;
								break;
							case 27:
								$this->Main->itemid[$name] = 469;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "亀の頭";
								$this->Main->itemprice[$name] = 30000;
								break;
							case 28:
								$this->Main->itemid[$name] = 169;
								$this->Main->itemdamage[$name] = 0;
								$this->Main->itemname[$name] = "シーランタン";
								$this->Main->itemprice[$name] = 50;
								break;
							case 29:
								$buttons[] = [
									'text' => "§d§l石系",
								];
								$buttons[] = [
									'text' => "§d§l土系",
								];
								$buttons[] = [
									'text' => "§d§l木系",
								];
								$buttons[] = [
									'text' => "§d§l砂系",
								];
								$buttons[] = [
									'text' => "§d§l羊毛系",
								];
								$buttons[] = [
									'text' => "§d§l花系",
								];
								$buttons[] = [
									'text' => "§d§lネザー系",
								];
								$buttons[] = [
									'text' => "§d§lテラコッタ",
								];
								$buttons[] = [
									'text' => "§d§lコンクリート",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス",
								];
								$buttons[] = [
									'text' => "§d§lステンドグラス窓",
								];
								$buttons[] = [
									'text' => "§d§l食料",
								];
								$buttons[] = [
									'text' => "§d§l武器",
								];
								$buttons[] = [
									'text' => "§d§lその他",
								];
								$pk = new ModalFormRequestPacket();
								$id = 99991;
								$pk->formId = $id;
								$data = [
									"type" => "form",
									"title" => "§d§lITEMSHOP/list/index.htm",
									"content" => "§a所持金 → §e{$mymoney}￥\n\n",
									"buttons" => $buttons
								];
								$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return false;
								break;
						}
						break;
				}
				$pk = new ModalFormRequestPacket();
				$pk->formId = 64435;
				$sIP = (string)$this->Main->itemprice[$name];
				$five = (string)$this->Main->itemprice[$name] * 5;
				$stack = (string)$this->Main->itemprice[$name] * 64;
				$formdata["type"] = "custom_form";
				$formdata["title"] = "§d§lITEMSHOP/item/{$this->Main->itemid[$name]}/{$this->Main->itemdamage[$name]}";
				$formdata["content"][] = array(
					"type" => "label",
					"text" => "§a所持金 → §e{$mymoney}￥",
				);
				$formdata["content"][] = array(
					"type" => "label",
					"text" => "§a§l{$this->Main->itemname[$name]}§r\n    1個 : §e§l{$sIP}￥§r\n    5個 : §e§l{$five}￥§r\n    64個 : §e§l{$stack}￥",
				);
				$formdata["content"][] = array(
					"type" => "input",
					"text" => "個数",
				);
				$pk->formData = json_encode($formdata);
				$player->dataPacket($pk);
				break;//ショップ

			case 64435:
				if ($result[2] === null) {
					return false;
				}
				if ($result[2] === "") {
					$player->sendMessage("[管理AI]§4個数が入力されていません");
					return false;
				}
				if (!is_numeric($result[2])) {
					$player->sendMessage("[管理AI]§4個数が数字ではありません");
					return false;
				}
				if (($this->Main->itemprice[$name]) === null) {
					$player->sendMessage("[管理AI]何らかの処理にエラーが発生しました。");
					return true;
				}
				$buttons[] = [
					'text' => "はい",
				];
				$buttons[] = [
					'text' => "いいえ",
				];
				$this->Main->itemprice[$name] = $this->Main->itemprice[$name] * $result[2];
				$this->Main->itemamount[$name] = $result[2];
				$pk = new ModalFormRequestPacket();
				$id = 23456;
				$pk->formId = $id;
				$data = [
					"type" => "form",
					"title" => "§d§lITEMSHOP/buy.htm",
					"content" => "§a所持金 → §e{$mymoney}￥\n\n§c最終確認\n§a§l§a  {$this->Main->itemname[$name]} §c- §b{$this->Main->itemamount[$name]}個 \n§e  {$this->Main->itemprice[$name]}￥§cで購入してよろしいですか？\n\n",
					"buttons" => $buttons
				];
				$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
				$player->dataPacket($pk);
				break;//ショップ最終確認

			case 23456:
				if ($data == 0) {
					if ($this->Main->itemamount[$name] < 2305) {
						if ($mymoney <= $this->Main->itemprice[$name]) {
							$player->sendMessage("[ITEMSHOP] §aお金が足りません。");
							$this->Main->itemprice[$name] = null;
							$this->Main->itemid[$name] = null;
							$this->Main->itemname[$name] = null;
							$this->Main->itemdamage[$name] = null;
							$this->Main->itemamount[$name] = null;
							return false;
						}
						$this->Main->economyAPI->reduceMoney($player->getName(), $this->Main->itemprice[$name]);
						$item = Item::get($this->Main->itemid[$name], $this->Main->itemdamage[$name], $this->Main->itemamount[$name]);
						if ($player->getInventory()->canAddItem($item) == true) {
							$player->getInventory()->addItem($item);
							$player->sendMessage("[ITEMSHOP] §l§b" . $this->Main->itemname[$name] . " §aを §e" . $this->Main->itemprice[$name] . "￥ §aで §d" . $this->Main->itemamount[$name] . "個 §a買いました");
							$playerdata = $this->Main->getPlayerData($name);
							$playerdata->addVar("SHOPPING");
							$this->Main->itemprice[$name] = null;
							$this->Main->itemid[$name] = null;
							$this->Main->itemname[$name] = null;
							$this->Main->itemdamage[$name] = null;
							$this->Main->itemamount[$name] = null;
							return false;
						} else {
							$player->sendMessage("[ITEMSHOP] §aインベントリの空きが足りません。");
							$this->Main->itemprice[$name] = null;
							$this->Main->itemid[$name] = null;
							$this->Main->itemname[$name] = null;
							$this->Main->itemdamage[$name] = null;
							$this->Main->itemamount[$name] = null;
							return false;
						}
					} else {
						$player->sendMessage("[ITEMSHOP] §aインベントリに入り切りません");
						$this->Main->itemprice[$name] = null;
						$this->Main->itemid[$name] = null;
						$this->Main->itemname[$name] = null;
						$this->Main->itemdamage[$name] = null;
						$this->Main->itemamount[$name] = null;
						return false;
					}
				} else {
					$player->sendMessage("[ITEMSHOP] §a購入を取消しました。");
					$this->Main->itemprice[$name] = null;
					$this->Main->itemid[$name] = null;
					$this->Main->itemname[$name] = null;
					$this->Main->itemdamage[$name] = null;
					$this->Main->itemamount[$name] = null;
					return false;
				}
				break;//ショップアイテム追加

		}
	}
}