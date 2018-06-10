<?php

namespace Jackthehack21\BlockReward;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\lang\TranslationContainer;
//use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent, PlayerDeathEvent, PlayerChatEvent};;
use pocketmine\event\block\{BlockBreakEvent};;
use pocketmine\event\entity\EntityDamageEvent;

class BlockEvents implements Listener
{
    private $main;

    public function __construct(Main $plugin)
    {
        $this->main = $plugin;
    }
    
    public function blockBreak(BlockBreakEvent $event)
    {
        if($this->main->cfg->get('enabled') != true){
            return;
        }
        $block = $event->getBlock();
        if (isset($this->main->cfg->get('blocks')[str_replace(' ','_',strtolower($block->getName()))])) {
            $player = $event->getPlayer();
            $event->setCancelled();
            $player->getLevel()->setBlock($block, new Block(Block::AIR), true, true);
            foreach($this->main->cfg->get('blocks')[str_replace(' ','_',strtolower($block->getName()))] as &$next){
                if($next[0] == "£" || $next[0] == "$"){
                    if(isset($this->main->economy) == true){
                        //there is money plugin so lets add some dough
                        $new = str_replace('$','',$next);
                        settype($new, "integer");
                        $this->main->economy::getInstance()->addMoney($player, $new);
                        $player->sendMessage(C::GOLD.$next.C::GREEN.' Has been put in your account !');
                    }
                }
                if($next == 'kill'){
                    $this->main->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_SUICIDE, 1000));
				    if($ev->isCancelled()){
                        return true;
                    }
                    $player->setLastDamageCause($ev);
                    $player->setHealth(0);
                    Command::broadcastCommandMessage($player, new TranslationContainer("commands.kill.successful", [$player->getName()]));
                }
                if($next == 'survival'){
                    $gameMode = Server::getGamemodeFromString($next);
		            if($gameMode != -1){
                        $player->setGamemode($gameMode);
                    } else {
                        if($this->main->cfg->get('debug') == true){
                            $this->main->getLogger()->warning('Invalid game mode ('.$next.') set in config.yml');
                        }
                    }
                }
                if($next == 'creative'){
                    $gameMode = Server::getGamemodeFromString($next);
		            if($gameMode != -1){
                        $player->setGamemode($gameMode);
                    } else {
                        if($this->main->cfg->get('debug') == true){
                            $this->main->getLogger()->warning('Invalid game mode ('.$next.') set in config.yml');
                        }
                    }
                }
                if($next == 'fly'){
                    $player->setAllowFlight(true);
                }
            }
        }
    }

}