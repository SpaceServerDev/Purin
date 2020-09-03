<?php


namespace SSC\Form;


use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use SSC\main;
use SSC\PlayerEvent;

class FishForm implements Form {

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	public function __construct(PlayerEvent $pe) {
		$this->pe = $pe;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if (!is_numeric($data)) {
			return;
		}
		switch ($data) {
			case 0:
				$amount=0;
				foreach ($player->getInventory()->getContents() as $item){
					if($item->getNamedTag()->offsetExists("Fish_name")){
						$amount++;
					}
				}
				if($amount==0){
						$player->sendMessage("[ぱちもんふくろう] 魚コレクションになりそうなのがないぽっぽ！");
						return;
				}
				$player->sendForm(new giveFish($this->pe));
				return;
			case 1:
				$player->sendForm(new SpaceFish($this->pe));
				return;
			case 2:
				$player->sendForm(new EarthFish($this->pe));
				return;
			case 3:
				$player->sendForm(new NeptuneFish($this->pe));
				return;
			case 4:
				$player->sendForm(new MarsFish($this->pe));
				return;
			case 5:
				$cls = $this->pe->getFish();
				$amount = $cls->getAllFish();
				if($amount===106){
					if($this->pe->canChangeRod()) {
						$item = Item::get(346, 0, 1);
						$item->setCustomName("§8王の釣り竿");
						$enchantment = Enchantment::getEnchantment(17);
						$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
						$item->getNamedTag()->setInt("RareFishingHook", 4);
						if ($player->getInventory()->canAddItem($item)) {
							$player->getInventory()->addItem($item);
							$player->sendMessage("[ぱちもんふくろう] §aコンプリートおめでとうぽっぽ！ 王の釣り竿を贈呈するぽっぽ！");
							$this->pe->changeRod();
							return;
						}
					}
				}
				$player->sendForm($this);
			break;
		}
		return;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$cls = $this->pe->getFish();
		$amount = $cls->getAllFish();
		return [
			"type" => "form",
			"title" => "魚のフクロウ",
			"content" => "コンプリートまで {$amount}/106\n\n",
			"buttons" => [["text" => "魚の納品"], ["text" => "宇宙の魚"], ["text" => "地球の魚"], ["text" => "海王星の魚"], ["text" => "火星の魚"], ["text" => "コンプリート報酬を受け取る"]],
		];
	}
}
class giveFish implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	/**
	 * @var array
	 */
	private $content;


	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		switch ($data){
			case 0:
				$player->sendForm(new FishForm($this->pe));
			return;
			default:
				$player->sendForm(new giveamountFish($this->pe,$this->content[$data-1]));
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
		$buttons[]=["text"=>"戻る"];
		foreach ($this->pe->getPlayer()->getInventory()->getContents() as $item) {
			if ($item->getNamedTag()->offsetExists("Fish_name")) {
				$buttons[]=["text"=>$item->getName()];
				$this->content[]=$item;
			}
		}
		return[
			"type"=>"form",
			"title"=>"魚のフクロウ",
			"content"=>"魚を納品できます。\n",
			"buttons"=>$buttons,
		];
	}
}

class giveamountFish implements Form{

	/**
	 * @var PlayerEvent
	 *
	 */
	private $pe;
	/**
	 * @var Item
	 */
	private $item;

	public function __construct(PlayerEvent $pe, Item $item) {
		$this->pe=$pe;
		$this->item=$item;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1])){
			return;
		}
		if($data[1]<0){
			return;
		}
		$this->pe->getFish()->addFish($this->item->getNamedTag()->getString("Fish_name"),$data[1]);
		if($this->item->getNamedTag()->offsetExists("Fish_size")) {
			$config = main::getMain()->fishsize->getAll();
			$config[$this->item->getNamedTag()->getString("Fish_name")]+=[$player->getName() => $this->item->getNamedTag()->getInt("Fish_size")];
			main::getMain()->fishsize->setAll($config);
			main::getMain()->fishsize->save();
		}
		switch ($this->item->getNamedTag()->getInt("Fish_rare")){
			case 1:
				$amount=5;
			break;
			case 2:
				$amount=20;
			break;
			case 3:
				$amount=50;
			break;
			case 4:
				$amount=200;
			break;
		}
		$prize=$amount*$data[1];
		EconomyAPI::getInstance()->addMoney($player->getName(),$prize);
		$this->item->setCount($data[1]);
		$player->getInventory()->removeItem($this->item);
		$player->sendMessage($this->item->getNamedTag()->getString("Fish_name")."を".$data[1]."匹".$prize."円で納品しました！");
		return;
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
			"type"=>"custom_form",
			"title"=>"魚のフクロウ",
			"content"=>[["type"=>"label","text"=>"{$this->item->getName()}を納品します"],['type' => 'slider','text' => '納品する個数を選択してください','min' => 1, 'max' => $this->item->getCount()]]
		];
	}
}

class SpaceFish implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		if($data==0){
			$player->sendForm(new FishForm($this->pe));
			return;
		}
		$player->sendForm($this);
		return;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons[]=["text"=>"§l§a戻る"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("シーラカンス") ? "シーラカンス" : "???")." ".$this->pe->getFish()->get("シーラカンス")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("メガロドン") ? "メガロドン" : "???")." ".$this->pe->getFish()->get("メガロドン")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("リュウグウノツカイ") ? "リュウグウノツカイ" : "???")." ".$this->pe->getFish()->get("リュウグウノツカイ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("オキナ") ? "オキナ" : "???")." ".$this->pe->getFish()->get("オキナ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("クラーケン") ? "クラーケン" : "???")." ".$this->pe->getFish()->get("クラーケン")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("ダイオウイカ") ? "ダイオウイカ" : "???")." ".$this->pe->getFish()->get("ダイオウイカ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("赤えい") ? "赤えい" : "???")." ".$this->pe->getFish()->get("赤えい")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("カメロケラス") ? "カメロケラス" : "???")." ".$this->pe->getFish()->get("カメロケラス")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("アスピドケロン") ? "アスピドケロン" : "???")." ".$this->pe->getFish()->get("アスピドケロン")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("マンボウ") ? "マンボウ" : "???")." ".$this->pe->getFish()->get("マンボウ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ミツクリザメ") ? "ミツクリザメ" : "???")." ".$this->pe->getFish()->get("ミツクリザメ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ラブカ") ? "ラブカ" : "???")." ".$this->pe->getFish()->get("ラブカ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("リフィーシードラゴン") ? "リフィーシードラゴン" : "???")." ".$this->pe->getFish()->get("リフィーシードラゴン")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ガラスイカ") ? "ガラスイカ" : "???")." ".$this->pe->getFish()->get("ガラスイカ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("スターゲイザーフィッシュ") ? "スターゲイザーフィッシュ" : "???")." ".$this->pe->getFish()->get("スターゲイザーフィッシュ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ブロブフィッシュ") ? "ブロブフィッシュ" : "???")." ".$this->pe->getFish()->get("ブロブフィッシュ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ホウライエソ") ? "ホウライエソ" : "???")." ".$this->pe->getFish()->get("ホウライエソ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ミズウオ") ? "ミズウオ" : "???")." ".$this->pe->getFish()->get("ミズウオ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("オニハダカ") ? "オニハダカ" : "???")." ".$this->pe->getFish()->get("オニハダカ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ミドリフサアンコウ") ? "ミドリフサアンコウ" : "???")." ".$this->pe->getFish()->get("ミドリフサアンコウ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ツボダイ") ? "ツボダイ" : "???")." ".$this->pe->getFish()->get("ツボダイ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("デメニギス") ? "デメニギス" : "???")." ".$this->pe->getFish()->get("デメニギス")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ハプロフリューネ・モリス") ? "ハプロフリューネ・モリス" : "???")." ".$this->pe->getFish()->get("ハプロフリューネ・モリス")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("フサアンコウ") ? "フサアンコウ" : "???")." ".$this->pe->getFish()->get("フサアンコウ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("コンニャクウオ") ? "コンニャクウオ" : "???")." ".$this->pe->getFish()->get("コンニャクウオ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ダンゴウオ") ? "ダンゴウオ" : "???")." ".$this->pe->getFish()->get("ダンゴウオ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ヨミノアシロ") ? "ヨミノアシロ" : "???")." ".$this->pe->getFish()->get("ヨミノアシロ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("シンカイクサウオ") ? "シンカイクサウオ" : "???")." ".$this->pe->getFish()->get("シンカイクサウオ")."匹"];
		return[
			"type"=>"form",
			"title"=>"魚のフクロウ",
			"content"=>"宇宙の魚です。\n",
			"buttons"=>$buttons,
		];

	}
}

class EarthFish implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
		public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		if($data==0){
			$player->sendForm(new FishForm($this->pe));
			return;
		}
		$player->sendForm($this);
		return;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons[]=["text"=>"§l§a戻る"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("マグロ") ? "マグロ" : "???")." ".$this->pe->getFish()->get("マグロ")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("ホオジロザメ") ? "ホオジロザメ" : "???")." ".$this->pe->getFish()->get("ホオジロザメ")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("カジキ") ? "カジキ" : "???")." ".$this->pe->getFish()->get("カジキ")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("シャチ") ? "シャチ" : "???")." ".$this->pe->getFish()->get("シャチ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("ドリアスピス") ? "ドリアスピス" : "???")." ".$this->pe->getFish()->get("ドリアスピス")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("エデスタス") ? "エデスタス" : "???")." ".$this->pe->getFish()->get("エデスタス")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("ヘリコプリオン") ? "ヘリコプリオン" : "???")." ".$this->pe->getFish()->get("ヘリコプリオン")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("リオプレウロドン") ? "リオプレウロドン" : "???")." ".$this->pe->getFish()->get("リオプレウロドン")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("ニューネッシー") ? "ニューネッシー" : "???")." ".$this->pe->getFish()->get("ニューネッシー")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("キャディ") ? "キャディ" : "???")." ".$this->pe->getFish()->get("キャディ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("マダイ") ? "マダイ" : "???")." ".$this->pe->getFish()->get("マダイ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ノコギリザメ") ? "ノコギリザメ" : "???")." ".$this->pe->getFish()->get("ノコギリザメ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ジンベエザメ") ? "ジンベエザメ" : "???")." ".$this->pe->getFish()->get("ジンベエザメ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("シュモクザメ") ? "シュモクザメ" : "???")." ".$this->pe->getFish()->get("シュモクザメ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("コバンザメ") ? "コバンザメ" : "???")." ".$this->pe->getFish()->get("コバンザメ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("サバ") ? "サバ" : "???")." ".$this->pe->getFish()->get("サバ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("サケ") ? "サケ" : "???")." ".$this->pe->getFish()->get("サケ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ブリ") ? "ブリ" : "???")." ".$this->pe->getFish()->get("ブリ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ウツボ") ? "ウツボ" : "???")." ".$this->pe->getFish()->get("ウツボ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("カツオ") ? "カツオ" : "???")." ".$this->pe->getFish()->get("カツオ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("スズキ") ? "スズキ" : "???")." ".$this->pe->getFish()->get("スズキ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("オニカサゴ") ? "オニカサゴ" : "???")." ".$this->pe->getFish()->get("オニカサゴ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("アカエイ") ? "アカエイ" : "???")." ".$this->pe->getFish()->get("アカエイ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("クロサバフグ") ? "クロサバフグ" : "???")." ".$this->pe->getFish()->get("クロサバフグ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("サワラ") ? "サワラ" : "???")." ".$this->pe->getFish()->get("サワラ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("タチウオ") ? "タチウオ" : "???")." ".$this->pe->getFish()->get("タチウオ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ボラ") ? "ボラ" : "???")." ".$this->pe->getFish()->get("ボラ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("マルアジ") ? "マルアジ" : "???")." ".$this->pe->getFish()->get("マルアジ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ヤリイカ") ? "ヤリイカ" : "???")." ".$this->pe->getFish()->get("ヤリイカ")."匹"];
		return[
			"type"=>"form",
			"title"=>"魚のフクロウ",
			"content"=>"地球の魚です。\n",
			"buttons"=>$buttons,
		];

	}
}

class NeptuneFish implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
		public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		if($data==0){
			$player->sendForm(new FishForm($this->pe));
			return;
		}
		$player->sendForm($this);
		return;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons[]=["text"=>"§l§a戻る"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("マグロ") ? "マグロ" : "???")." ".$this->pe->getFish()->get("マグロ")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("ウナギ") ? "ウナギ" : "???")." ".$this->pe->getFish()->get("ウナギ")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("ホッケ") ? "ホッケ" : "???")." ".$this->pe->getFish()->get("ホッケ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("ネッシー") ? "ネッシー" : "???")." ".$this->pe->getFish()->get("ネッシー")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("イッシー") ? "イッシー" : "???")." ".$this->pe->getFish()->get("イッシー")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("クッシー") ? "クッシー" : "???")." ".$this->pe->getFish()->get("クッシー")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("モッシー") ? "モッシー" : "???")." ".$this->pe->getFish()->get("モッシー")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("チュッシー") ? "チュッシー" : "???")." ".$this->pe->getFish()->get("チュッシー")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("アッシー") ? "アッシー" : "???")." ".$this->pe->getFish()->get("アッシー")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("キス") ? "キス" : "???")." ".$this->pe->getFish()->get("キス")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ニシン") ? "ニシン" : "???")." ".$this->pe->getFish()->get("ニシン")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("エビ") ? "エビ" : "???")." ".$this->pe->getFish()->get("エビ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ヒラメ") ? "ヒラメ" : "???")." ".$this->pe->getFish()->get("ヒラメ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ナマズ") ? "ナマズ" : "???")." ".$this->pe->getFish()->get("ナマズ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("サバ") ? "サバ" : "???")." ".$this->pe->getFish()->get("サバ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ヒラマサ") ? "ヒラマサ" : "???")." ".$this->pe->getFish()->get("ヒラマサ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("エイ") ? "エイ" : "???")." ".$this->pe->getFish()->get("エイ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("コチ") ? "コチ" : "???")." ".$this->pe->getFish()->get("コチ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ライギョ") ? "ライギョ" : "???")." ".$this->pe->getFish()->get("ライギョ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("サヨリ") ? "サヨリ" : "???")." ".$this->pe->getFish()->get("サヨリ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("コイ") ? "コイ" : "???")." ".$this->pe->getFish()->get("コイ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("デメキン") ? "デメキン" : "???")." ".$this->pe->getFish()->get("デメキン")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ワカサギ") ? "ワカサギ" : "???")." ".$this->pe->getFish()->get("ワカサギ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("タイ") ? "タイ" : "???")." ".$this->pe->getFish()->get("タイ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("フグ") ? "フグ" : "???")." ".$this->pe->getFish()->get("フグ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ブルーギル") ? "ブルーギル" : "???")." ".$this->pe->getFish()->get("ブルーギル")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("シーバス") ? "シーバス" : "???")." ".$this->pe->getFish()->get("シーバス")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ハゼ") ? "ハゼ" : "???")." ".$this->pe->getFish()->get("ハゼ")."匹"];
		return[
			"type"=>"form",
			"title"=>"魚のフクロウ",
			"content"=>"海王星の魚です。\n",
			"buttons"=>$buttons,
		];

	}
}

class MarsFish implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
		public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		if($data==0){
			$player->sendForm(new FishForm($this->pe));
			return;
		}
		$player->sendForm($this);
		return;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons[]=["text"=>"§l§a戻る"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("ナイルパーチ") ? "ナイルパーチ" : "???")." ".$this->pe->getFish()->get("ナイルパーチ")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("ウナギ") ? "ウナギ" : "???")." ".$this->pe->getFish()->get("ウナギ")."匹"];
		$buttons[]=["text"=>"§l§eUR\n".($this->pe->getFish()->getPublic("ヘラチョウザメ") ? "ヘラチョウザメ" : "???")." ".$this->pe->getFish()->get("ヘラチョウザメ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("オオメジロサメ") ? "オオメジロサメ" : "???")." ".$this->pe->getFish()->get("オオメジロサメ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("メコンオオナマズ") ? "メコンオオナマズ" : "???")." ".$this->pe->getFish()->get("メコンオオナマズ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("デンキウナギ") ? "デンキウナギ" : "???")." ".$this->pe->getFish()->get("デンキウナギ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("アハイア・グランディ") ? "アハイア・グランディ" : "???")." ".$this->pe->getFish()->get("アハイア・グランディ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("アマゾンカワイルカ") ? "アマゾンカワイルカ" : "???")." ".$this->pe->getFish()->get("アマゾンカワイルカ")."匹"];
		$buttons[]=["text"=>"§l§eUR 特殊餌\n".($this->pe->getFish()->getPublic("アリゲーターガー") ? "アリゲーターガー" : "???")." ".$this->pe->getFish()->get("アリゲーターガー")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ベタ") ? "ベタ" : "???")." ".$this->pe->getFish()->get("ベタ")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("エンドリケリー") ? "エンドリケリー" : "???")." ".$this->pe->getFish()->get("エンドリケリー")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ピラルク") ? "ピラルク" : "???")." ".$this->pe->getFish()->get("ピラルク")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("ドラド") ? "ドラド" : "???")." ".$this->pe->getFish()->get("ドラド")."匹"];
		$buttons[]=["text"=>"§l§cSR\n".($this->pe->getFish()->getPublic("アロワナ") ? "アロワナ" : "???")." ".$this->pe->getFish()->get("アロワナ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ティラピア") ? "ティラピア" : "???")." ".$this->pe->getFish()->get("ティラピア")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("河ふぐ") ? "河ふぐ" : "???")." ".$this->pe->getFish()->get("河ふぐ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ニジマス") ? "ニジマス" : "???")." ".$this->pe->getFish()->get("ニジマス")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("ケツギョ") ? "ケツギョ" : "???")." ".$this->pe->getFish()->get("ケツギョ")."匹"];
		$buttons[]=["text"=>"§l§bR\n".($this->pe->getFish()->getPublic("カワアナゴ") ? "カワアナゴ" : "???")." ".$this->pe->getFish()->get("カワアナゴ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ハヤ") ? "ハヤ" : "???")." ".$this->pe->getFish()->get("ハヤ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("コイ") ? "コイ" : "???")." ".$this->pe->getFish()->get("コイ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("アユ") ? "アユ" : "???")." ".$this->pe->getFish()->get("アユ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("サクラマス") ? "サクラマス" : "???")." ".$this->pe->getFish()->get("サクラマス")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ワカサギ") ? "ワカサギ" : "???")." ".$this->pe->getFish()->get("ワカサギ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("イトウ") ? "イトウ" : "???")." ".$this->pe->getFish()->get("イトウ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("スズキ") ? "スズキ" : "???")." ".$this->pe->getFish()->get("スズキ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ドジョウ") ? "ドジョウ" : "???")." ".$this->pe->getFish()->get("ドジョウ")."匹"];
		$buttons[]=["text"=>"§l§aN\n".($this->pe->getFish()->getPublic("ナマズ") ? "ナマズ" : "???")." ".$this->pe->getFish()->get("ナマズ")."匹"];
		return[
			"type"=>"form",
			"title"=>"魚のフクロウ",
			"content"=>"火星の魚です。\n",
			"buttons"=>$buttons,
		];

	}
}