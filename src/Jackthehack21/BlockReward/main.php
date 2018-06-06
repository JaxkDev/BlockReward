<?php

namespace Jackthehack21/BlockReward;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class main extends PluginBase implements Listener
{
    public function onEnable()
    {
        
    }

    public function onDisable()
    {
        
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        if ($cmd->getName() == 'blockreward') {
            
        }
        return true;
    }
   
}
