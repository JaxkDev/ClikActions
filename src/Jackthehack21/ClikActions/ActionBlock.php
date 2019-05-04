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

use pocketmine\level\Position;
use pocketmine\Player;

class ActionBlock
{
    /** @var Main */
    public $plugin;
    /** @var Position */
    public $position;

    public $name = "null";
    public $actions = [];
    public $id;

    /**
     * @param Main $plugin
     * @param string $name
     * @param array $position
     * @param string $world
     * @param array $actions
     * @param int $id
     */
    public function __construct(Main $plugin, string $name, array $position, string $world, array $actions, int $id)
    {
        $this->plugin = $plugin;
        $this->name = $name;
        $this->actions = $actions;
        $this->id = $id;

        if($this->plugin->getServer()->isLevelGenerated($world) === false){
            //delete action block, as block no longer exists.
            $this->plugin->deleteActionblock($this);
            return;
        }

        $this->position = new Position($position[0],$position[1],$position[2], $plugin->getServer()->getLevelByName($world));
    }

    public function execute(Player $player) : void{
        foreach($this->actions as $action){
            $player->sendMessage($action);
        }
    }

    /**
     * @return array
     */
    public function toArray() : array{
        return [
            "name"=>$this->name,
            "position"=>[$this->position->x,$this->position->y,$this->position->z],
            "world"=>$this->position->getLevel()->getName(),
            "actions"=>$this->actions
        ];
    }
}