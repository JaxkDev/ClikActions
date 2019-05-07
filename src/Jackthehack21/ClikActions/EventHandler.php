<?php

/**
 *
 *     /$$$$$$  /$$ /$$ /$$        /$$$$$$              /$$     /$$
 *    /$$__  $$| $$|__/| $$       /$$__  $$            | $$    |__/
 *   | $$  \__/| $$ /$$| $$   /$$| $$  \ $$  /$$$$$$$ /$$$$$$   /$$  /$$$$$$  /$$$$$$$   /$$$$$$$
 *   | $$      | $$| $$| $$  /$$/| $$$$$$$$ /$$_____/|_  $$_/  | $$ /$$__  $$| $$__  $$ /$$_____/
 *   | $$      | $$| $$| $$$$$$/ | $$__  $$| $$        | $$    | $$| $$  \ $$| $$  \ $$|  $$$$$$
 *   | $$    $$| $$| $$| $$_  $$ | $$  | $$| $$        | $$ /$$| $$| $$  | $$| $$  | $$ \____  $$
 *   |  $$$$$$/| $$| $$| $$ \  $$| $$  | $$|  $$$$$$$  |  $$$$/| $$|  $$$$$$/| $$  | $$ /$$$$$$$/
 *    \______/ |__/|__/|__/  \__/|__/  |__/ \_______/   \___/  |__/ \______/ |__/  |__/|_______/
 *
 *   Copyright (C) 2019 Jackthehack21 (Jack Honour/Jackthehaxk21/JaxkDev)
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *   Twitter :: @JaxkDev
 *   Discord :: Jackthehaxk21#8860
 *   Email   :: gangnam253@gmail.com
 */

declare(strict_types=1);
namespace Jackthehack21\ClikActions;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat as C;

class EventHandler implements Listener {
    /** @var Main */
    private $plugin;

    /**
     * EventHandler constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param array $args
     * @return bool
     */
    public function handleCommand(CommandSender $sender, array $args) : bool{
        if($sender instanceof ConsoleCommandSender){
            $sender->sendMessage(C::RED."Command must be run in-game.");
            return true;
        }
        if(count($args) === 0) return false;
        switch(strtolower($args[0])) {
            case 'help':
                if(!$sender->hasPermission("clikactions.command.help")){
                    $sender->sendMessage(C::RED."You do not have permission to do that.");
                    return true;
                }
                $sender->sendMessage(C::GREEN . "-- Help Docs --");
                $sender->sendMessage(C::GRAY . "/actions add <action>");
                $sender->sendMessage(C::GRAY . "/actions rem <action>");
                $sender->sendMessage(C::GRAY . "/actions delete");
                $sender->sendMessage(C::GRAY . "/actions list");
                $sender->sendMessage(C::GRAY . "/actions help");
                $sender->sendMessage(C::GRAY . "/actions credits");
                break;
            case 'credits':
                if(!$sender->hasPermission("clikactions.command.credits")){
                    $sender->sendMessage(C::RED."You do not have permission to do that.");
                    return true;
                }
                $sender->sendMessage(C::GREEN."--- ".C::GOLD."CREDITS".C::GREEN." ---");
                $sender->sendMessage(C::RED."Developer  :: Jackthehack21/JaxkDev");
                break;
            case 'list':
                if(!$sender->hasPermission("clikactions.command.list")){
                    $sender->sendMessage(C::RED."You do not have permission to do that.");
                    return true;
                }
                $sender->sendMessage(C::GOLD."Click a block to list their actions, or type 'cancel'.");
                $this->plugin->interactCommand[strtolower($sender->getName())] = ["list"];
                break;
            case 'delete':
                if(!$sender->hasPermission("clikactions.command.delete")){
                    $sender->sendMessage(C::RED."You do not have permission to do that.");
                    return true;
                }
                $sender->sendMessage(C::GOLD."Click a block to delete all actions applied to it, or type 'cancel'.");
                $this->plugin->interactCommand[strtolower($sender->getName())] = ["delete"];
                break;
            case 'new':
            case 'add':
            case 'create':
                if(!$sender->hasPermission("clikactions.command.add")){
                    $sender->sendMessage(C::RED."You do not have permission to do that.");
                    return true;
                }
                if(count($args) < 2){
                    $sender->sendMessage(C::RED."Usage: ".C::GRAY."/actions add <action>");
                    break;
                }
                array_shift($args);
                $action = join(" ",$args);
                $this->plugin->interactCommand[strtolower($sender->getName())] = ["add",$action];
                $sender->sendMessage(C::GOLD."Click a block to add the command, or type 'cancel'.");
                break;
            case 'rem':
                if(!$sender->hasPermission("clikactions.command.rem")){
                    $sender->sendMessage(C::RED."You do not have permission to do that.");
                    return true;
                }
                if(count($args) < 2){
                    $sender->sendMessage(C::RED."Usage: ".C::GRAY."/actions rem <action>");
                    break;
                }
                array_shift($args);
                $action = join(" ", $args);
                $this->plugin->interactCommand[strtolower($sender->getName())] = ["rem",$action];
                $sender->sendMessage(C::GOLD."Click a block to remove the command, or type 'cancel'.");
                break;
            default:
                return false;
        }
        return true;
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onChat(PlayerChatEvent $event) : void{
        $player = $event->getPlayer();
        $msg = $event->getMessage();
        if(isset($this->plugin->interactCommand[strtolower($player->getName())])){
            if(strtolower($msg) === "cancel" or strtolower($msg) === "cancel."){
                unset($this->plugin->interactCommand[strtolower($player->getName())]);
                $player->sendMessage(C::RED."Action cancelled.");
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event) : void{
        if($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) return;
        //todo config, specific side of block.
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if(isset($this->plugin->interactCommand[strtolower($player->getName())])){
            $event->setCancelled(true);
            $args = $this->plugin->interactCommand[strtolower($player->getName())];
            unset($this->plugin->interactCommand[strtolower($player->getName())]);
            switch($args[0]){
                case 'list':
                    $actionBlock = $this->plugin->getActionByPosition($block->asPosition());
                    if($actionBlock === null){
                        $player->sendMessage(C::RED."That block has no actions assigned.");
                        break;
                    }
					if(count($actionBlock>actions) === 0){
						//technically this should never happen, but just in case its user friendly.
						$player->sendMessage(C::RED."This action block has no actions.");
						break;
					}
                    $player->sendMessage(C::GOLD."Actions for '".$actionBlock->name."':"); 
					//name is there for future and no need to add the value to existing data when it finally gets used with remoteactions.
                    foreach($actionBlock->actions as $action){
                        $player->sendMessage(C::GRAY."> ".C::GREEN.$action);
                    }
                    break;
                case 'delete':
                    $actionBlock = $this->plugin->getActionByPosition($block->asPosition());
                    if($actionBlock === null){
                        $player->sendMessage(C::RED."That block has no actions to delete.");
                        break;
                    }
                    $this->plugin->deleteActionblock($actionBlock);
                    $player->sendMessage(C::GREEN."Actions for that block were successfully deleted.");
                    break;
                case 'add':
                    $actionBlock = $this->plugin->getActionByPosition($block->asPosition());
                    if($actionBlock === null){
                        $this->plugin->createActionblock($block->asPosition(), [$args[1]]);
                        $player->sendMessage(C::GREEN."Action '".$args[1]."' added.");
                        break;
                    } else {
                        $actionBlock->actions[] = $args[1];
                        $player->sendMessage(C::GREEN."Action '".$args[1]."' added.");
                        break;
                    }
                case 'rem':
                    $actionBlock = $this->plugin->getActionByPosition($block->asPosition());
                    if($actionBlock === null){
                        $player->sendMessage(C::RED."That block has no actions to delete.");
                        break;
                    }
                    $i = 0;
                    foreach($actionBlock->actions as $action){
                        if($action === $args[1]){
                            unset($actionBlock->actions[$i]);
                            $actionBlock->actions = array_values($actionBlock->actions);
                            $player->sendMessage(C::GREEN."Action '".$args[1]."' removed.");
                            $this->plugin->saveActions();
                            return;
                        }
                        $i++;
                    }
                    $player->sendMessage(C::RED."Action '".$args[1]."' could not be found on this block, make sure it was typed exactly the same as said in /actions list");
                    break;
                default:
                    //shouldn't reach here, failsafe.
                    $player->sendMessage(C::RED."Unknown command used on block.");
                    break;
            }
            return;
        }

        $actionBlock = $this->plugin->getActionByPosition($block->asPosition());
        if($actionBlock === null) return;
		if(!$player->hasPermission("clikactions.use")){
            $player->sendMessage(C::RED."You do not have permission to use that action Block.");
            return;
        }
        $event->setCancelled(true);

        $actionBlock->execute($player);
    }
}
