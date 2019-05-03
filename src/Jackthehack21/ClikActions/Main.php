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

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

    /** @var self */
    private static $instance;

    private function initResources() : void{

    }

    private function init() : void{

    }

    public function onEnable() : void{
        self::$instance = $this;
        $this->initResources(); //load all actions and config.
        $this->init();
    }
}