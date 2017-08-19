<?php
namespace YeuMCPE;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
    public function onLoad(){
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "YeuMCPE");
    }
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->nolove = new Config($this->getDataFolder()."yeu.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[♥] Ok đã sẵn sàng trên máy chủ ".$this->getDescription()->getVersion());
    }
    
    public function onDisable(){
        $this->nolove->save();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[♥] Bạn đã chia tay với máy chủ.");
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            
            case "yeu":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[♥] Hãy sử dụng lệnh nầy trong game");
                    return true;
                }
                
                $loved = array_shift($args);
                if($this->nolove->exists(strtolower($loved))){
                    $sender->sendMessage("§5[♥]Xin lỗi, " . $loved . "§5 Không muốn yêu ai ngay bây giờ.");
                    return true;
                }else{
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        if($lovedPlayer == $sender){
                           
                            $sender->sendMessage("§5[♥]Bạn không thể yêu chính mình :P");
                            $this->getServer()->broadcastMessage("§5[♥]§a" . $sender->getName() . "§5 §eCố gắng yêu mình :P. §6#Mãi cô đơn");
                            
                            
                        }else{
                            $lovedPlayer->sendMessage("§5[♥]§a" . $sender->getName() . "§5Đang yêu bạn!");
                            if(isset($args[0])){
                                $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                            }
                            $sender->sendMessage("§5[♥] Bạn yêu §a" . $loved . "?§5 yahh đó là tốt ");
                            $this->getServer()->broadcastMessage("§a" . $sender->getName() . " §dĐang yêu §a" . $loved . "§d.");
                            $this->getServer()->broadcastMessage("§d♥" . $loved . "§d ♥ " . $sender->getName() . "§d♥");
                           
                            $sender->setDisplayName(TextFormat::LIGHT_PURPLE . "[♥]".$sender->getDisplayName());
                            $lovedPlayer->setDisplayName(TextFormat::LIGHT_PURPLE . "[♥]".$lovedPlayer->getDisplayName());
                            
                            return true;
                        }
                    }else{
                        $sender->sendMessage("§5[♥] §a" . $loved . "§5 Không có sẵn cho tình yêu. # Đáng xấu hổ. §5 Về cơ bản, §a" . $loved . "§5 Không tồn tại, hoặc không trực tuyến. ");
                        return true;
                    }
                }

                break;
            case "chiatay":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[♥] Bạn phải sử dụng lệnh nầy trong game.");
                    return true;
                }
                $loved = array_shift($args);
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        $lovedPlayer->sendMessage("§5[♥]§a" . $sender->getName() ."§5Đã chia tay với bạn!");
                        if(isset($args[0])){
                            $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                        }
                        $sender->sendMessage("§5[♥] Bạn đã chia tay với §a" . $loved . "§5.");
                        $this->getServer()->broadcastMessage("§d[♥]§a" . $sender->getName() . " §dĐã chia tay với §a" . $loved . "§d.");
                        
                        $sender->setDisplayName(str_replace(TextFormat::LIGHT_PURPLE . "[♥]", "", $sender->getDisplayName()));
                        $lovedPlayer->setDisplayName(str_replace(TextFormat::LIGHT_PURPLE . "[♥]", "", $lovedPlayer->getDisplayName()));
                        
                        return true;
                    }else{
                        $sender->sendMessage($loved . "§5[♥] Không có sẵn để chia tay. Về cơ bản, §a" . $loved . "§5 Không tồn tại, hoặc không trực tuyến.");
                        return true;
                    }

            break;
            case "khongyeu":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[♥] Bạn phải sử dụng lệnh nầy trong game.");
                    return true;
                }
                if($args[0] == "nolove"){
                    $this->nolove->set(strtolower($sender->getName()));
                    $sender->sendMessage("§5[♥] Bạn sẽ không còn được yêu thương. §e#ForEverAlone");
                    return true;
                }elseif($args[0] == "love"){
                    $this->nolove->remove(strtolower($sender->getName()));
                    $sender->sendMessage("§5[♥] Bây giờ bạn sẽ được yêu một lần nữa! §e#GetInThere");
                    return true;
                }else{
                    return false;
                }
                
            break;
            case "tuvanyeu":
                $sender->sendMessage("§5[♥] Plugin YÊU Create By Duy2Phong ");
                $sender->sendMessage("§d[♥][YÊU] Sử dụng: /yeu <tên người chơi>");
                $sender->sendMessage("§d[♥][YÊU] Sử dụng: /chiatay <tên người chơi>");
                $sender->sendMessage("§d[♥][YÊU] Sử dụng: /khongyeu <khongyeu / yeu> ");
                $sender->sendMessage("§5[♥][YÊU] Vui vẻ với tình yêu ><");
                return true;
            break;
        default:
            return false;
        }
    return false;
    }
}
