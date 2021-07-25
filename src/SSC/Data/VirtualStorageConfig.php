<?php


namespace SSC\Data;

use pocketmine\item\Item;
use SSC\main;


class VirtualStorageConfig{

	private $name;

	public function __construct(string $name) {
		$this->name=$name;
	}

	private function getName(){
		return $this->name;
	}


	public function addItem(Item $item):bool{
		$name=$this->getName();
		$count=$item->getCount();
		$item->setCount(1);
		$nbt=$item->nbtSerialize();
		$result = main::getMain()->getVirtualStorage()->query("SELECT * FROM ".$name." WHERE NBT = \"$nbt\"");
		$results = $result->fetchArray(SQLITE3_ASSOC);
		if($result==null){
			main::getMain()->getVirtualStorage()->query("INSERT INTO ".$name."(NBT,COUNTS) VALUES(\"$nbt\",   \"$count\")");
			return false;
		}
		$id = $results["ID"];
		$nbt= $results["NBT"];
		$count2=$result["COUNTS"];
		$count=$count+$count2;
		main::getMain()->getVirtualStorage()->query("REPLACE INTO ".$name." VALUES(\"$id\",\"$nbt\",  \"$count\")");
		return true;

	}

	public function ItemCount(Item $item):int {
		$nbt=$item->nbtSerialize();
		$name=$this->getName();
		$query=main::getMain()->getVirtualStorage()->querySingle("SELECT COUNT(\"$nbt\") FROM ".$name);
		return $query;
	}

	public function getAllCount(){
		$name=$this->getName();
		$query=main::getMain()->getVirtualStorage()->querySingle("SELECT COUNT(*) FROM ".$name);
		return $query;
	}

	public function isSet(){
		return main::getPlayerData($this->getName())->getInventoryObject()>$this->getAllCount();
	}




}