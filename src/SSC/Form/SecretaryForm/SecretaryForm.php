<?php


namespace SSC\Form\SecretaryForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\Form\InformationForm;
use SSC\Form\RankForm;
use SSC\Form\SpaceShip\SpaceShipForm;
use SSC\Form\WarpListForm;
use SSC\main;
use SSC\PlayerEvent;

class SecretaryForm implements Form {

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch($data){
			case 0:
				$player->sendForm(new CosmoWarpListForm());
			break;
			case 1:
				$player->sendForm(new LandForm());
			break;
			case 2:
				$player->sendMessage("近日実装！");
			break;
			case 3:
				$player->sendForm(new SpaceShipForm(main::getPlayerData($player->getName())));
			break;
			case 4:
				$player->sendForm(new ShopForm());
			break;
			case 5:
				Server::getInstance()->dispatchCommand($player,"rank");
			break;
			case 6:
				Server::getInstance()->dispatchCommand($player,"gacha");
			break;
			case 7:
				Server::getInstance()->dispatchCommand($player,"hungry");
			break;
			case 8:
				Server::getInstance()->dispatchCommand($player,"loginbonus");
			break;
			case 9:
				$player->sendForm(new RepairForm());
			break;
			case 10:
				$player->sendForm(new MathForm());
			break;
			case 11:
				Server::getInstance()->dispatchCommand($player,"info");
			break;
			case 12:
				$player->sendForm(new TownListForm());
			break;
			case 13:
				Server::getInstance()->dispatchCommand($player,"profile");
			break;
			case 14:
				Server::getInstance()->dispatchCommand($player,"hitokoto");
			break;
			case 15:
				Server::getInstance()->dispatchCommand($player,"change");
			break;
			case 16:
				Server::getInstance()->dispatchCommand($player,"syo");
			break;
			case 17:
				Server::getInstance()->dispatchCommand($player,"option");
			break;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "秘書です。私にできることなら何でもします！",
			"buttons" => [
				[
					'text' => "ワープする",//0
				],
				[
					'text' => "土地保護をする",//1
				],
				[
					'text' => "銀行、経済関連",//2
				],
				[
					'text' => "宇宙船を強化する",//3
				],
				[
					'text' => "ショップ",//4
				],
				[
					'text' => "ランキング",//5
				],
				[
					'text'=> "ガチャを引く"//6
				],
				[
					'text'=> "空腹にする"//7
				],
				[
					'text' => "ログインボーナス",//8
				],
				[
					'text'=> "物品の修復"//9
				],
				[
					'text' => "数学系",//10
				],
				[
					'text' => "最新情報を見る",//11
				],
				[
					'text' => "自治体一覧",//12
				],
				[
					'text' => "プロフィール",//13
				],
				[
					'text' => "一言メッセージ",//14
				],
				[
					'text'=> "交換所",//15
				],
				[
					'text'=> "称号の変更",//16
				],
				[
					'text' => "設定",//17
				],
			],
		];
	}
}

class WarpForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				Server::getInstance()->dispatchCommand($player,"warp");
			return;
			case 1:
				Server::getInstance()->dispatchCommand($player,"mw");
			return;
		}
		$player->sendForm(new SecretaryForm());

	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "ワープリストです！",
			"buttons" => [
				[
					'text' => "ワープリストを表示",//0
				],
				[
					'text' => "マイワープを表示",//1
				],
				[
					'text' => "戻る",//2
				],
			],
		];
	}
}

class ShopForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				Server::getInstance()->dispatchCommand($player,"shop");
			return;
			case 1:
				Server::getInstance()->dispatchCommand($player,"trade");
			return;
		}
		$player->sendForm(new SecretaryForm());

	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "ショップリストです！",
			"buttons" => [
				[
					'text' => "ショップを見る",//0
				],
				[
					'text' =>"フリーマーケットをひらく",//1
				],
				[
					'text' => "戻る",//2
				],
			],
		];
	}
}

class RepairForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				Server::getInstance()->dispatchCommand($player,"repair");
			return;
		}
		$player->sendForm(new SecretaryForm());

	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "修復機能です！今、手に持っているものを修復クリームを使用\nすることで修復できます。",
			"buttons" => [
				[
					'text' => "修復します！",//0
				],
				[
					'text' => "戻る",//1
				],
			],
		];
	}
}

class MathForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				$player->sendForm(new CalcForm());
			return;
			case 1:
				$player->sendForm(new RandForm());
			return;

		}
		$player->sendForm(new SecretaryForm());

	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "数学系の機能です！",
			"buttons" => [
				[
					'text' => "計算機をつかう！",//0
				],
								[
					'text' => "乱数をつかう！",//1
				],
				[
					'text' => "戻る",//2
				],
			],
		];
	}
}

class CalcForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1]) and !is_numeric($data[2])) return;
		switch ($data[3]) {
			case 0:
				$calc = "+";
				break;
			case 1:
				$calc = "-";
				break;
			case 2:
				$calc = "*";
				break;
			case 3:
				$calc = "/";
				break;
			default:
				$player->sendForm(new self);
				return;
		}
			Server::getInstance()->dispatchCommand($player,"calc $data[1] $data[2] $calc");
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "custom_form",
			"title" => "§a秘書 コスモ",
			"content" => [
				["type" => "label",
				"text" => "計算機です！",],
				["type" => "input",
				"text" => "数字1",],
				["type" => "input",
				"text" => "数字2",],
				["type"=>"dropdown",
				"text"=>"符号",
				"options"=>["+","-","×","÷"]],
			],
		];
	}
}

class RandForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1]) and !is_numeric($data[2])) return;
		$private="";
		if($data[3]){
			if(isset($data[4])) {
				$private = $data[4];
			}
		}

			Server::getInstance()->dispatchCommand($player,"rand $data[1] $data[2] $private");
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "custom_form",
			"title" => "§a秘書 コスモ",
			"content" => [
				["type" => "label",
				"text" => "乱数計算機です！",],
				["type" => "input",
				"text" => "最小値 マイナスも可能",],
				["type" => "input",
				"text" => "最大値",],
				["type" => "toggle",
				"text" => "[オプション]プライベートで送信",],
				["type" => "input",
				"text" => "[オプション]プレイヤー名(自分も可能)",],
			],
		];
	}
}

class LandForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch($data) {
			case 0:
				Server::getInstance()->dispatchCommand($player, "land here");
				break;
			case 1:
				$player->sendForm(new LandBuyForm());
				break;
			case 2:
				$player->sendForm(new LandInviteForm());
				break;
			case 3:
				$player->sendForm(new LandInviteeForm());
				break;
			case 4:
				$player->sendForm(new LandSellForm());
				break;
			case 5:
				$player->sendForm(new LandGiveForm());
				break;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "秘書です。私にできることなら何でもします！",
			"buttons" => [
				[
					'text' => "現在いる座標の土地番号を調べる",//0
				],
				[
					'text' => "土地を購入する",//1
				],
				[
					'text' => "土地を共有する",//2
				],
				[
					'text' => "土地の共有を解除する",//3
				],
				[
					'text' => "土地を売る",//4
				],
				[
					'text' => "土地を譲渡する",//5
				],

			],
		];
	}
}

class LandBuyForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch($data) {
			case 0:
				Server::getInstance()->dispatchCommand($player, "startp");
				break;
			case 1:
				Server::getInstance()->dispatchCommand($player, "endp");
				break;
			case 2:
				Server::getInstance()->dispatchCommand($player, "land buy");
				break;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "始点と終点は角同士で大きさを決めてください。高さは関係ありません",
			"buttons" => [
				[
					'text' => "現在の立ち位置の座標で始点を設定",//0
				],
				[
					'text' => "現在の立ち位置の座標で終点を指定",//1
				],
				[
					'text' => "決定した土地を買う",//2
				],

			],
		];
	}
}

class LandInviteForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1]) and !is_numeric($data[2])) return;
		Server::getInstance()->dispatchCommand($player, "land invite ".$data[1]." ".$data[2]);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "custom_form",
			"title" => "§a秘書 コスモ",
			"content" => [
				["type" => "label",
				"text" => "土地を共有します。\nすべての入力欄は必須です。",],
				["type" => "input",
				"text" => "土地番号",],
				["type" => "input",
				"text" => "相手のプレイヤー名",],
			],
		];
	}
}

class LandInviteeForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1]) and !is_numeric($data[2])) return;
		Server::getInstance()->dispatchCommand($player, "land invitee ".$data[1]." ".$data[2]);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "custom_form",
			"title" => "§a秘書 コスモ",
			"content" => [
				["type" => "label",
				"text" => "土地共有を解除します。\nすべての入力欄は必須です。",],
				["type" => "input",
				"text" => "土地番号",],
				["type" => "input",
				"text" => "相手のプレイヤー名",],
			],
		];
	}
}

class LandSellForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1])) return;
		Server::getInstance()->dispatchCommand($player, "landsell ".$data[1]);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "custom_form",
			"title" => "§a秘書 コスモ",
			"content" => [
				["type" => "label",
				"text" => "土地を売却します。\nすべての入力欄は必須です。",],
				["type" => "input",
				"text" => "土地番号",],
			],
		];
	}
}

class LandGiveForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1])) return;
		Server::getInstance()->dispatchCommand($player, "land give ".$data[1]." ".$data[2]);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "custom_form",
			"title" => "§a秘書 コスモ",
			"content" => [
				["type" => "label",
				"text" => "土地を譲渡します。\nすべての入力欄は必須です。",],
				["type" => "input",
				"text" => "土地番号",],
				["type" => "input",
				"text" => "相手のプレイヤー名",],
			],
		];
	}
}

class TownListForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch($data) {
			case 0:
				$player->sendForm(new TownListAboutForm("マリン村","村長:umechazuke63\n座標 : 5979 67 4581 地球付近\n自治体ルール:\n・村内に建築したい時は、umeに前もって申し出ること\n・誰が建立した建築物か分かるように、名前を記した看板を自分の建物に貼り付けること\n・無断で村の宣伝をしないこと"));
				break;
			case 1:
				$player->sendForm(new TownListAboutForm("りんごのダラダラな街","村長:rararingo\n座標 -100000 100 -100000 地球付近\n自治体ルール :\n1.市長(rararinngo)に住民になったことを伝える。\n2.街に必ず一つ建物を建てること。 \n3.中心道路[4本道]から必ず1マス以上離して建築を行うこと。\n4.報告なしの勝手な建築の禁止\n5.プレイヤーレベル100以下の住民の侵入又は勧誘の禁止。"));
				break;
			case 2:
				$player->sendForm(new TownListAboutForm("マムロ村","村長:Tabi7320\n座標：\n218 78 213 火星\nルール：\n窃盗や暴言などの常識内のマナー違反禁止\nマムロ村壁内の建築エリア以外の建築や破壊は原則禁止\nそして関連施設の近くなども同じ\n建築エリアは住民入りしてから1週間以上建物と呼べる建築がされてない、または進歩されてない場合土地に看板を立てmsgで注意しますそして2～4週も同じです。そして1か月以上建築物といえる建築がなく、４回以上注意を受け何か適切な返事(例：自分の村で建築してるので、たぶん家建てられません。　初心者なので材料ない。）をしなかった場合土地没収、または上級による建築が行われます。（土地没収になっても土地が没収されるだけで他のことには関係ありません）\nマムロ村には「マムロ村貢献賞」っていうのがあります。これはマムロ村の発展を助けたプレーヤーに与えられる賞です\nラピスラズリをもらえます\n住民になるとあなたはマムロ村の住民です！ってかんじでエメラルドが渡されます\nマムロ村のレベル：村長、副村長、M.VIP＋、M.VIP、住民\n村長：Tabi7320　副村長RiM325"));
				break;
			case 3:
				$player->sendForm(new TownListAboutForm(" 都心(眠れない街)","市長:める\n座標 : 599 45 114 火星\nその他:sqmeruのmywarpポイントから\n\n自治体ルール:\n・今の所建築は一切受け付けません。\n・住居は現在はまだ提供できません。\n・荒らしは当たり前だけど駄目です。\n・出禁処置になっている人は誠に申し訳無いですが街へは入れません。\n・チームへの参加も信頼者のみです。"));
				break;
			case 4:
				$player->sendForm(new TownListAboutForm("ジェネ国","国王:あぽろ\n座標 337,66,886 地球\nルール:\n・建築する際は役場の受け付けがチャットでアポロにどこにどの範囲で作るか決める\n・倉庫が土地共有されるまで役場の屋根裏か外のチェストを使う\n・荒らしたら報告\n・国開発をする時邪魔な建築は建築者に協力してもらう"));
				break;
			case 5:
				$player->sendForm(new TownListAboutForm("あぱまち","町長:ApateticFoil114\n座標:2115 65 -233\nルール:\n1.荒らしなどは問答無用で処罰します。これは宇宙含めの処罰です。\n2.出禁扱いになっているプレイヤーは訪問を禁止とします。\n3.進行禁止になっているエリアにxtpで突入することを禁止とします。\n4.屋敷への侵入は禁止です。一発出禁案件です。\n5.勝手な建築は街ルールにより破壊権限を持ちます。予告なしに破壊し、その土地代や資材は返却しません。\n6.あぱまち制作メンバー、あぱまち管理チームが不適切行為だと判断することはしないでください。\n7.上記のルールが守れないプレイヤーは守れるようになってから来てください。\nご案内:\n街のショップなどのお知らせをあぱまち訪問者Discordで配信しています。ぜひご参加ください。\n(制作チームのDiscordには参加できません。)"));
				break;
			case 6:
				$player->sendForm(new TownListAboutForm("影武者街","村長:Kuro83060\n座標: 20001 67 967 地球\nルールは:\n・和風の建築以外はしてはいけない。\n\n・植林場の木は切ったら植え替える\n\n・ブランチマイニング場では綺麗に掘る\n\n・建築する時は自分にどこに作るかなにを作るかを教えてから作ること\n\n・勝手に村に住ませたり勝手に住人表に住人を増やしたりなど\n\n・街の倉庫のチェストロックは3つまで旅館のは5個まで\n共用チェストはロックしないこと\n\n・家の中のチェストはロックすること\n\n・畑では作物を取ったらちゃんと種を植え替えるスイカやカボチャ畑は勝手に耕さないこと\n\n:街住む場合まず最初旅館に住むか自分で作るかが選べます！\nわからない事は気軽に聞いてください〜"));
				break;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "秘書です。私にできることなら何でもします！",
			"buttons" => [
				[
					'text' => "マリン村",//0
				],
				[
					'text' => "りんごのダラダラな街",//1
				],
				[
					'text' => "マムロ村",//2
				],
				[
					'text' => "都心(眠れない街)",//3
				],
				[
					'text' => "ジェネ国",//4
				],
				[
					'text' => "あぱまち",//5
				],
				[
					'text' => "影武者街",//6
				],
			]
		];
	}
}

class TownListAboutForm implements Form{

	/**
	 * @var string
	 */
	private $title;
	/**
	 * @var string
	 */
	private $contents;


	public function __construct(string $title, string $contents) {
		$this->title=$title;
		$this->contents=$contents;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_bool($data)){
			return;
		}
		if($data){
			$player->sendForm(new TownListForm());
			return;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			'type'=>'modal',
			'title'=>"§a§l{$this->title}",
			'content'=>$this->contents,
			'button1'=>"もっと見る",
			'button2'=>"おわる"
		];
	}
}

class CosmoWarpListForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch($data) {
			case 0:
				$player->sendForm(new WarpForm());
				break;
			case 1:
				$player->sendForm(new xtpForm(main::getPlayerData($player->getName())));
				break;
			case 2:
				$player->sendForm(new tppForm());
				break;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "テレポートです！",
			"buttons" => [
				[
					'text' => "各地点にテレポート",//0
				],
				[
					'text' => "好きな場所にテレポート",//1
				],
				[
					'text' => "他人にテレポート申請を送る",//2
				],
			]
		];
	}
}

class xtpForm implements Form{
	/**
	 * @var PlayerEvent
	 */
	private $playerEvent;

	/**
	 * @var array
	 */
	private $world;

	public function __construct(PlayerEvent $playerEvent) {
		$this->playerEvent=$playerEvent;
		$this->world=["地球","太陽","人工惑星","海王星","火星"];
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if($data[1]===null or $data[2]===null or $data[3]===null) return;
		Server::getInstance()->dispatchCommand($player, "xtp ".$data[1]." ".$data[2]." ".$data[3]." ".$this->world[$data[4]]);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$playerdata=$this->playerEvent;
		$maxX=$playerdata->getPlayer()->getFloorX()+$playerdata->getSpaceShipSize()*100;
		$minX=$playerdata->getPlayer()->getFloorX()-$playerdata->getSpaceShipSize()*100;
		$maxZ=$playerdata->getPlayer()->getFloorZ()+$playerdata->getSpaceShipSize()*100;
		$minZ=$playerdata->getPlayer()->getFloorZ()-$playerdata->getSpaceShipSize()*100;
		return [
			"type" => "custom_form",
			"title" => "§a秘書 コスモ",
			"content" => [
				["type" => "label",
				"text" => "x,y,zを指定したテレポートをします。\n現在の座標\n".$playerdata->getPlayer()->getFloorX().",".$playerdata->getPlayer()->getFloorY().",".$playerdata->getPlayer()->getFloorZ()."\n現在行ける距離\nX:".$minX."～".$maxX."\nZ:".$minZ."～".$maxZ,],
				["type" => "input",
				"text" => "X",],
				["type" => "input",
				"text" => "Y",],
				["type" => "input",
				"text" => "Z",],
				["type" => "dropdown",
					"text" => "ワールド",
					"options"=>$this->world],
			],
		];

	}
}

class tppForm implements Form{
	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch($data) {
			case 0:
				$player->sendForm(new tppSendForm($player));
				break;
			case 1:
				Server::getInstance()->dispatchCommand($player, "tpagree");
				break;
			case 2:
				Server::getInstance()->dispatchCommand($player, "tpdis");
				break;
			case 3:
				Server::getInstance()->dispatchCommand($player, "tpcancel");
				break;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§a秘書 コスモ",
			"content" => "秘書です。私にできることなら何でもします！",
			"buttons" => [
				[
					'text' => "テレポートリクエストを送る",//0
				],
				[
					'text' => "テレポートリクエストを承認する",//1
				],
				[
					'text' => "テレポートリクエストを拒否する",//2
				],
				[
					'text' => "テレポートリクエストをキャンセルする",//3
				],
			]
		];
	}
}

class tppSendForm implements Form{

	/**
	 * @var string
	 */
	private $players;

	/**
	 * @var Player
	 */
	private $player;

	public function __construct(Player $player) {
		$this->player=$player;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if($data[1]===null) return;
		if($data[1]===0){
			$player->sendForm(new tppForm());
			return;
		}
		Server::getInstance()->dispatchCommand($player, "tpp ".$this->players[$data[1]]);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$this->players[]="戻る";
		foreach (Server::getInstance()->getOnlinePlayers() as $names) {
			if($this->player->getName()!==$names->getName()) {
				$this->players[] = $names->getName();
			}
		}
		return [
			"type" => "custom_form",
			"title" => "§a秘書 コスモ",
			"content" => [
				["type" => "label",
				"text" => "プレイヤーにテレポート申請を送信します！",],
				["type" => "dropdown",
					"text" => "プレイヤー名",
					"options"=>$this->players],
			],
		];

	}
}