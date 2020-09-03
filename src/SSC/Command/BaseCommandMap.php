<?php


namespace SSC\Command;


use Pocketmine\Server;
use SSC\Command\DefaultCommands\tradeCommand;
use SSC\Command\DefaultCommands\advancemodeCommand;
use SSC\Command\DefaultCommands\calcCommand;
use SSC\Command\DefaultCommands\cbanCommand;
use SSC\Command\DefaultCommands\cnameCommand;
use SSC\Command\DefaultCommands\cpardonCommand;
use SSC\Command\DefaultCommands\dcsCommand;
use SSC\Command\DefaultCommands\dropCommand;
use SSC\Command\DefaultCommands\dustCommand;
use SSC\Command\DefaultCommands\earthCommand;
use SSC\Command\DefaultCommands\floorCommand;
use SSC\Command\DefaultCommands\flyCommand;
use SSC\Command\DefaultCommands\gacCommand;
use SSC\Command\DefaultCommands\gachaCommand;
use SSC\Command\DefaultCommands\gachaticketCommand;
use SSC\Command\DefaultCommands\hidCommand;
use SSC\Command\DefaultCommands\hungryCommand;
use SSC\Command\DefaultCommands\infoCommand;
use SSC\Command\DefaultCommands\johoCommand;
use SSC\Command\DefaultCommands\kakinCommand;
use SSC\Command\DefaultCommands\kakinitemCommand;
use SSC\Command\DefaultCommands\kCommand;
use SSC\Command\DefaultCommands\keikokuCommand;
use SSC\Command\DefaultCommands\levelbonusCommand;
use SSC\Command\DefaultCommands\logCommand;
use SSC\Command\DefaultCommands\loginbonusCommand;
use SSC\Command\DefaultCommands\mwCommand;
use SSC\Command\DefaultCommands\opchatCommand;
use SSC\Command\DefaultCommands\ptpCommand;
use SSC\Command\DefaultCommands\pvpCommand;
use SSC\Command\DefaultCommands\randCommand;
use SSC\Command\DefaultCommands\rankCommand;
use SSC\Command\DefaultCommands\repairCommand;
use SSC\Command\DefaultCommands\respawnCommand;
use SSC\Command\DefaultCommands\rouyaCommand;
use SSC\Command\DefaultCommands\saddleCommand;
use SSC\Command\DefaultCommands\SendFormCommand;
use SSC\Command\DefaultCommands\shopCommand;
use SSC\Command\DefaultCommands\showsignCommand;
use SSC\Command\DefaultCommands\slotCommand;
use SSC\Command\DefaultCommands\spaceCommand;
use SSC\Command\DefaultCommands\spCommand;
use SSC\Command\DefaultCommands\stickCommand;
use SSC\Command\DefaultCommands\suCommand;
use SSC\Command\DefaultCommands\sunCommand;
use SSC\Command\DefaultCommands\testCommand;
use SSC\Command\DefaultCommands\townCommand;
use SSC\Command\DefaultCommands\tpagreeCommand;
use SSC\Command\DefaultCommands\tpdisCommand;
use SSC\Command\DefaultCommands\tppCommand;
use SSC\Command\DefaultCommands\unregisterCommand;
use SSC\Command\DefaultCommands\warpCommand;
use SSC\Command\DefaultCommands\WorldWarpCommand;
use SSC\Command\DefaultCommands\xtpCommand;
use SSC\Command\DefaultCommands\xyzCommand;

use SSC\Core\version;
use SSC\main;

class BaseCommandMap {

	const pm="pocketmine";

	public static function init(main $main) {
		Server::getInstance()->getCommandMap()->register(self::pm, new xyzCommand("xyz"));
		Server::getInstance()->getCommandMap()->register(self::pm, new respawnCommand("respawn"));
		Server::getInstance()->getCommandMap()->register(self::pm, new spaceCommand("space"));
		Server::getInstance()->getCommandMap()->register(self::pm, new earthCommand("earth"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("blackhole", "自由に飛べるワールド(ブラックホール)へ移動します", "/blackhole"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("taucetuse", "バニラワールド(くじら座τ星e)へ移動します", "/taucetuse"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("taucetusf", "露天掘りワールド(くじら座τ星f)へ移動します", "/taucetusf"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("mars", "火星へ移動します", "/mars"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("neptune", "海王星へ移動します", "/neptune"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("trappist-1e", "整地ワールド(トラピスト1星e)へ移動します", "/trappist-1e"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("spawn", "ロビーへ移動します", "/spawn"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("lobby", "ロビーへ移動します", "/lobby"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("flat", "人工惑星(フラットワールド)へ移動します", "/flat"));
		Server::getInstance()->getCommandMap()->register(self::pm, new WorldWarpCommand("rule", "ルール説明に移動します", "/rule"));
		Server::getInstance()->getCommandMap()->register(self::pm, new sunCommand("sun"));
		Server::getInstance()->getCommandMap()->register(self::pm, new pvpCommand("pvp"));
		Server::getInstance()->getCommandMap()->register(self::pm, new kCommand("k", "こんにちはの顔文字を送信します", "/k"));
		Server::getInstance()->getCommandMap()->register(self::pm, new kCommand("oti", "おちるときのの顔文字を送信します", "/oti"));
		Server::getInstance()->getCommandMap()->register(self::pm, new kCommand("otu", "おつかれの顔文字を送信します", "/otu"));
		Server::getInstance()->getCommandMap()->register(self::pm, new tppCommand($main));
		Server::getInstance()->getCommandMap()->register(self::pm, new tpagreeCommand($main));
		Server::getInstance()->getCommandMap()->register(self::pm, new tpdisCommand($main));
		Server::getInstance()->getCommandMap()->register(self::pm, new suCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new dustCommand($main));
		Server::getInstance()->getCommandMap()->register(self::pm, new hidCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new gacCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new rouyaCommand($main));
		Server::getInstance()->getCommandMap()->register(self::pm, new repairCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new cnameCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new levelbonusCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new gachaCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new opchatCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new infoCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new johoCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new hungryCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new logCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new dcsCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new stickCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new mwCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new floorCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new slotCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new randCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new dropCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new advancemodeCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new calcCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new flyCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new kakinCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new SendFormCommand('zisseki', "実績を表示します", "/zisseki","ZissekiForm"));
		Server::getInstance()->getCommandMap()->register(self::pm, new SendFormCommand('daily', "デイリーミッションを確認", "/daily","DailyForm"));
		Server::getInstance()->getCommandMap()->register(self::pm, new SendFormCommand('repeat', "連続ログインボーナスを確認します", "/repeat","RepeatLoginbonusForm"));
		Server::getInstance()->getCommandMap()->register(self::pm, new SendFormCommand('clan', "クランに加入します(β)", "/clan","ClanForm"));
		Server::getInstance()->getCommandMap()->register(self::pm, new SendFormCommand('dailybonus', "デイリーチケット交換所をひらく", "/dairybonus","DailyChangeForm"));
		Server::getInstance()->getCommandMap()->register(self::pm, new SendFormCommand('feed', "釣り餌を変える", "/feed","FishFeedForm"));
		Server::getInstance()->getCommandMap()->register(self::pm, new warpCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new kakinitemCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new showsignCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new ptpCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new cbanCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new cpardonCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new unregisterCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new shopCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new townCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new rankCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new xtpCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new spCommand());
		Server::getInstance()->getCommandMap()->register(self::pm, new saddleCommand());
		Server::getInstance()->getCommandMap()->register(self::pm,new loginbonusCommand());
		Server::getInstance()->getCommandMap()->register(self::pm ,new keikokuCommand());
		Server::getInstance()->getCommandMap()->register(self::pm ,new gachaticketCommand());
		Server::getInstance()->getCommandMap()->register(self::pm ,new tradeCommand());
		if(version::DEV_VERSION) {
			Server::getInstance()->getCommandMap()->register(self::pm, new testCommand());
		}
	}
}