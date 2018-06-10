<?php

namespace Jackthehack21\BlockReward;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent, PlayerDeathEvent, PlayerChatEvent};;
use pocketmine\event\block\{BlockBreakEvent};;

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
        $this->main->getLogger()->info($block->getName().'-'.$block->getId());
        if (isset($this->main->cfg->get('blocks')[str_replace(' ','_',strtolower($block->getName()))])) {
            $player = $event->getPlayer();
            $event->setCancelled();
            $player->getLevel()->setBlock($block, new Block(Block::AIR), true, true);
            $this->main->getLogger()->info(serialize($this->main->cfg->get('blocks')[str_replace(' ','_',strtolower($block->getName()))]));
            foreach($this->main->cfg->get('blocks')[str_replace(' ','_',strtolower($block->getName()))][0] as $next){
                if(substr($next, 0, 1 ) === "£" || substr($next, 0, 1 ) === "$"){
                    if(isset($this->main->economy)){
                        //there is money plugin so lets add some dough
                        settype($next, "integer");
                        $this->main->economy->addMoney($player, $next);
                        $player->sendMessage('£'.$next.' Has been put in your bank');
                    }
                } else {
                    //check if block but how
                }
            }
        }
    }

}