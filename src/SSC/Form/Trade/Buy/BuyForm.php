<?php


namespace SSC\Form\Trade\Buy;


use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use SSC\Data\tradeConfig;
use SSC\Form\Trade\MainForm;

class BuyForm implements Form {
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				$player->sendForm(new SearchIDForm());
				return;
			case 1:
				$player->sendForm(new SearchMarketIDForm());
				return;
			case 2:
				$cls=new tradeConfig();
				if($cls->getAllMarket()==null){
					$player->sendMessage("[§aTRADE§r] フリーマーケットがありません");
					return;
				}
				foreach ($cls->getAllMarket() as $id){
					$buttons[]=$cls->getMarketData($id);
				}
				$player->sendForm(new ResultSerachIDForm($buttons));
				return;
		}
		$player->sendForm(new MainForm());
	}


	public function jsonSerialize() {
		$buttons[]=['text'=>"アイテムIDから検索"];
		$buttons[]=['text'=>"フリマIDから検索"];
		$buttons[]=['text'=>"最新の出品リスト"];
		$buttons[]=['text'=>"戻る"];
		return [
			"type"=>'form',
			"title"=>'§l§aSSTRADESTATION.com',
			"content"=>"フリーマーケットメニューです！",
			"buttons"=>$buttons,
		];
	}
}

//アイテムIDから検索==========

class SearchIDForm implements Form{

	/**
	 * @var string
	 */
	private $label;

	public function __construct(string $label="") {
		$this->label=$label;
	}

	public function handleResponse(Player $player, $data): void {
		if($data==false) return;
		if(!is_numeric($data[1])){
			$player->sendForm(new self("§cIDは整数で入力してください"));
			return;
		}
		if(!is_numeric($data[2])){
			$player->sendForm(new self("§cダメージ値は整数で入力してください"));
			return;
		}
		$id=floor($data[1]);
		$damage=floor($data[2]);
		$cls=new tradeConfig();
		$ary=$cls->getMarketItem($id,$damage);
		if($ary==null){
			$player->sendForm(new self("見つかりませんでした。"));
			return;
		}
		foreach ($ary as $id){
			$buttons[]=$cls->getMarketData($id);
		}
		$player->sendForm(new ResultSerachIDForm($buttons));
	}

	public function jsonSerialize() {
		$content[]=[
			"type"=>"label",
			"text"=>$this->label
		];
		$content[]=[
			"type"=>"input",
			"text"=>"IDを入力してください"
		];
		$content[]=[
			"type"=>"input",
			"text"=>"ダメージ値",
		];
		return [
			'type'=>'custom_form',
			'title'=>'§l§aSSTRADESTATION.com/Buy',
			'content'=>$content
		];
	}
}


//フリマから検索==========

class SearchMarketIDForm implements Form{

	/**
	 * @var string
	 */
	private $label;

	public function __construct(string $label="") {
		$this->label=$label;
	}

	public function handleResponse(Player $player, $data): void {
		if($data==false) return;
		if(!is_numeric($data[1])){
			$player->sendForm(new self("§cフリマIDは整数で入力してください"));
			return;
		}
		$id=floor($data[1]);
		$cls=new tradeConfig();
		$ary[]=$cls->getMarketData($id);
		if($ary==null){
			$player->sendForm(new self("見つかりませんでした。"));
			return;
		}
		$player->sendForm(new ResultSerachIDForm($ary));
	}

	public function jsonSerialize() {
		$content[]=[
			"type"=>"label",
			"text"=>$this->label
		];
		$content[]=[
			"type"=>"input",
			"text"=>"フリマIDを入力してください"
		];
		return [
			'type'=>'custom_form',
			'title'=>'§l§aSSTRADESTATION.com/Buy/',
			'content'=>$content
		];
	}
}

class ResultSerachIDForm implements Form{

	/**
	 * @var array
	 */
	private $button;


	/**
	 * @var string
	 */
	private $content;

	public function __construct(array $button,string $content="") {
		$this->button=$button;
		$this->content=$content;
	}


	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;
		$market=$this->button[$data];
		if($market["price"]>EconomyAPI::getInstance()->myMoney($player->getName())){
			$player->sendForm(new self($this->button,$content="§aお金が足りません"));
			return;
		}
		$player->sendForm(new ConfirmSerachIDForm($this->button[$data]["id"],EconomyAPI::getInstance()->myMoney($player->getName())));

	}

	public function jsonSerialize() {
		foreach ($this->button as $data) {
			$item = Item::nbtDeserialize(unserialize($data["nbt"]));
			$name = $item->getName()."§r";
			$button[]=['text'=>"{$name} : {$item->getCount()}個\n{$data["price"]}￥ ItemID_Damage:{$item->getId()}_{$item->getDamage()} {$data["player"]}さん出品 ID:{$data["id"]}"];
		}
		return [
			"type"=>'form',
			"title"=>"§l§aSSTRADESTATION.com/buy/{$item->getId()}/{$item->getDamage()}",
			"content"=>$this->content,
			"buttons"=>$button,
		];
	}
}

class ConfirmSerachIDForm implements Form{

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var mixed
	 */
	private $money;

	/**
	 * @var array
	 */
	private $data;

	public function __construct(int $id,$money) {
		$this->id=$id;
		$this->money=$money;
	}

	public function handleResponse(Player $player, $data): void {
		if ($data) {
			$cls = new tradeConfig();
			if ($cls->exists($this->data["id"])) {
				if ($this->data["player"] == $player->getName()) {
					$player->sendMessage("[§aTRADE§r] 自分の出品したアイテムは買えません。");
					return;
				}
				$cls=new tradeConfig();
				$item = Item::nbtDeserialize(unserialize($this->data["nbt"]));
				if ($player->getInventory()->canAddItem($item)) {
					EconomyAPI::getInstance()->reduceMoney($player->getName(), $this->data["price"]);
					EconomyAPI::getInstance()->addMoney($this->data["player"], $this->data["price"]);
					$player->getInventory()->addItem($item);
					$cls = new tradeConfig();
					$cls->removeItem($this->data["id"]);
					$player->sendMessage("[§aTRADE§r] 購入が完了しました！");
					$target = Server::getInstance()->getPlayer($this->data["player"]);
					if ($target instanceof Player) {
						$target->sendMessage("[§aTRADE§r] ID:{$this->id} が購入されました。");
					}
					Server::getInstance()->getLogger()->notice("§r[§aTRADE§r] {$player->getName()}がID:{$this->id} {$this->data["player"]}の{$item->getId()}:{$item->getDamage()} {$item->getCount()}個を{$this->data["price"]}￥の取引が成立しました");
					return;
				}
				$player->sendMessage("[§aTRADE§r] これ以上アイテムを持ません");
				return;
			}
			$player->sendForm(new SearchIDForm());
			return;
		}else{
			$player->sendMessage("[§aTRADE§r] 残念ながら買われてしまったようです");
		}
	}
	public function jsonSerialize() {
		$cls=new tradeConfig();
		$this->data=$cls->getMarketData($this->id);
		$item = Item::nbtDeserialize(unserialize($this->data["nbt"]));
		$name = $item->getName()."§r";
		return [
			'type'=>'modal',
			'title'=>"§l§aSSTRADESTATION.com/buy/{$this->data["id"]}",
			'content'=>"Mymoney : {$this->money}\n\nID : {$item->getId()}\nItem : {$name}\nダメージ値 : {$item->getDamage()}\n個数 : {$item->getCount()}\n値段 : {$this->data["price"]}",
			'button1'=>"購入を確定する",
			'button2'=>"戻る"
		];
	}
}