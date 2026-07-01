<?php
namespace worldguardian\signature;

final class VeoZax {

    const PLUGIN_NAME    = "WorldGuardian";
    const PLUGIN_VERSION = "1.0.0";
    const AUTHOR         = "VeoZax";
    const NETWORK        = "play.veozax.xyz:25590";
    const API            = "VeoZaxAPI";
    const BUILD_DATE     = "2026-07-01";

    public static function printBanner(\pocketmine\plugin\Plugin $plugin){
        $logger = $plugin->getLogger();
        $logger->info("");
        $logger->info("§b╔══════════════════════════════════════╗");
        $logger->info("§b║ §fWorldGuardian §bv" . self::PLUGIN_VERSION . " §7by §f" . self::AUTHOR . "       §b║");
        $logger->info("§b║ §7Owner Server: §f" . self::NETWORK . "  §b║");
        $logger->info("§b║ §7Created On: §a" . self::BUILD_DATE . "               §b║");
        $logger->info("§b╚══════════════════════════════════════╝");
        $logger->info(""); }

    public static function identity(){
        return self::PLUGIN_NAME . " v" . self::PLUGIN_VERSION . " by " . self::AUTHOR;
    }
    private function __construct(){}
}