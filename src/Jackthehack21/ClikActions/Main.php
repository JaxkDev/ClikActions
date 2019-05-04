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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

    /** @var self */
    private static $instance;

    public const ActionsVersion = 0;

    /** @var Config */
    private $actionsC;

    /** @var ActionBlock[] */
    public $actions = [];

    /** @var array */
    public $config = [];
    public $interactCommand = []; //[playerName => command/args] used when adding rem listing actions.

    /** @var EventHandler */
    public $eventHandler;

    private function initResources() : void{
        //todo sqlite.
        $this->actionsC = new Config($this->getDataFolder() . "actions.yml", Config::DETECT, ["version" => $this::ActionsVersion, "actions" => []]);

        $this->saveResource("help.txt");
    }

    private function init() : void{
        $this->eventHandler = new EventHandler($this);
        $this->getServer()->getPluginManager()->registerEvents($this->eventHandler, $this);

        $this->loadActions();
    }

    public function loadActions() : void{
        $id = 0;
        $this->actions = [];
        foreach($this->actionsC->get("actions") as $action){
            $this->actions[] = new ActionBlock($this, $action["name"], $action["position"], $action["world"], $action["actions"], $id);
            $id++;
        }
    }

    public function saveActions() : void{
        $actions = [];
        foreach($this->actions as $action){
            $actions[] = $action->toArray();
        }
        $this->actionsC->set("actions", $actions);
        $this->actionsC->save();
    }

    public function onEnable() : void{
        self::$instance = $this;
        $this->initResources();
        $this->init();
    }

    public function onDisable()
    {
        $this->saveActions();
    }

    /**
     * @param Position $pos
     * @param array $actions
     * @param string $name
     * @return ActionBlock
     */
    public function createActionblock(Position $pos, array $actions, string $name = "null") : ActionBlock{
        $id = count($this->actions);
        $actionBlock = new ActionBlock($this, $name, [$pos->x,$pos->y,$pos->z], $pos->getLevel()->getName(), $actions, $id);
        $this->actions[$id] = $actionBlock;
        $this->saveActions();
        return $actionBlock;
    }

    public function deleteActionblock(ActionBlock $block) : void{
        unset($this->actions[$block->id]);
        $this->saveActions();
        $this->loadActions();
    }

    /**
     * @param int $id
     * @return ActionBlock|null
     */
    public function getActionById(int $id){
        if(!isset($this->actions[$id])) return null;
        return $this->actions[$id];
    }

    /**
     * @param Position $pos
     * @return ActionBlock|null
     */
    public function getActionByPosition(Position $pos){
        foreach($this->actions as $action){
            if($action->position->equals($pos->asVector3()) && $action->position->getLevel()->getName() === $pos->getLevel()->getName()){
                return $action;
            }
        }
        return null;
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        return $this->eventHandler->handleCommand($sender, $args);
    }
}