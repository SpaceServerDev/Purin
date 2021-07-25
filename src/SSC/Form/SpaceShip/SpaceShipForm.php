<?php
namespace SSC\Form\SpaceShip;

use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\Item;
use pocketmine\Player;
use SSC\PlayerEvent;

class SpaceShipForm implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $player;
	/**
	 * @var string
	 */
	private $str;

	public function __construct(PlayerEvent $player,string $str="§a宇宙船 コクピットです！") {
		$this->player=$player;
		$this->str=$str;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;
		$name=["石炭","鉄","金","ラピスラズリ","レッドストーン","ダイヤ","エメラルド"];
		$name_e=["COAL","IRON","GOLD","LAPIS","REDSTONE","DIAMOND","EMERALD"];
		$id=[263,265,266,351,331,264,388];
		$damage=[0,0,0,4,0,0,0];
		$exp=[1,3,10,5,5,20,25];
		$count=0;
		foreach ($player->getInventory()->getContents() as $itm) {
			$id_t = $itm->getId();
			$damage_t=$itm->getDamage();
			if ($id_t === $id[$data]) {
				if ($damage_t === $damage[$data]) {
					$count += 1;
				}
			}
		}
		if($count===0){
			$player->sendForm(new self($this->player,"鉱石がインベントリに存在しません"));
			return;
		}
		$player->sendForm(new SpaceShipCountForm($this->player,$id[$data],$damage[$data],$name[$data],$name_e[$data],$exp[$data]));
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$maxexp=$this->player->getSpaceShipSize()*100;
		$coal=$this->player->getSpaceShipOreCount("COAL");
		$iron=$this->player->getSpaceShipOreCount("IRON");
		$gold=$this->player->getSpaceShipOreCount("GOLD");
		$lapis=$this->player->getSpaceShipOreCount("LAPIS");
		$redstone=$this->player->getSpaceShipOreCount("REDSTONE");
		$diamond=$this->player->getSpaceShipOreCount("DIAMOND");
		$emerald=$this->player->getSpaceShipOreCount("EMERALD");
		$buttons[] = [
			'text' => "石炭 1EXP",
		];//0
		$buttons[] = [
			'text' => "鉄 3EXP",
		];//1
		$buttons[] = [
			'text' => "金 10EXP",
		];//2
		$buttons[] = [
			'text' => "ラピス 5EXP",
		];//3
		$buttons[] = [
			'text' => "レッドストーン5EXP",
		];//4
		$buttons[] = [
			'text' => "ダイヤ 20EXP",
		];//5
		$buttons[] = [
			'text' => "エメラルド 25EXP",
		];//6
		return [
			"type" => "form",
			"title" => "§d§l宇宙船コクピット",
			"content" => "{$this->str}\n宇宙船を強化するとxtpのテレポート距離を増やせたり、\n新しい惑星に行けるようになります!\n§b現在の宇宙船サイズ : {$this->player->getSpaceShipSize()}\n次の宇宙船サイズまで : {$this->player->getSpaceShipLevel()}EXP/{$maxexp}EXP\n§c===現在使用済み資材===\n石炭:{$coal}個\n鉄:{$iron}個\n金:{$gold}個\nラピス:{$lapis}個\n赤石:{$redstone}個\nダイヤ:{$diamond}個\nエメラルド:{$emerald}個",
			"buttons" => $buttons
		];
	}

}

class SpaceShipCountForm implements Form{
/**
	 * @var PlayerEvent
	 */
	private $player;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $damage;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $name_e;

	/**
	 * @var int
	 */
	private $exp;

	public function __construct(PlayerEvent $player,int $id,int $damage, string $name,string $name_e,int $exp) {
		$this->player=$player;
		$this->id=$id;
		$this->damage=$damage;
		$this->name=$name;
		$this->name_e=$name_e;
		$this->exp=$exp;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param Player $player
	 * @param mixed $data
	 *
	 */
	public function handleResponse(Player $player, $data): void {
		if($data===null) return;
		$item=Item::get($this->id,$this->damage,$data[1]);
		$player->getInventory()->removeItem($item);
		$exp=$this->exp*$data[1];
		$this->addExp($exp);
		$this->player->addSpaceShipOreCount($this->name_e,$data[1]);
		$player->sendForm(new SpaceShipForm($this->player,"交換が完了しました"));
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$player=$this->player->getPlayer();
		$amount = 0;
		foreach ($player->getInventory()->getContents() as $itm) {
			$id_t = $itm->getId();
			$dmg_t = $itm->getDamage();
			if ($id_t == $this->id) {
				if ($dmg_t == $this->damage) {
					$amt = $itm->getCount();
					$amount = (int)$amount + (int)$amt;
				}
			}
		}
		return [
			"type" => "custom_form",
			"title" => "§d§l宇宙船コクピット",
			"content" => [
				["type"=>"label",
				"text"=>"宇宙船の強化に使用する{$this->name}の個数を選択してください。"],
				["type"=>"slider",
				"text"=>"個数",
				"min"=>1,
				"max"=>$amount]
			],
		];

	}

	private function addExp(int $count){
		$re=0;
		$level=$this->player->getSpaceShipLevel();
		for($n=1;$n<=$count;$n++) {
			$level++;
			if($level%100===0){
				$re=1;
				$this->player->addSpaceShipSize();
			}
		}
		$this->player->setSpaceShipLevel($level);
		return $re;
	}
}
