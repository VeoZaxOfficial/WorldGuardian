<?php
namespace worldguardian;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use worldguardian\signature\VeoZax;

class WorldGuardian extends PluginBase implements Listener{
    private $worlds = [];
    private $prefix = "§l§8[§r§dWorld§7Guardian§l§8]§r ";
    private $bypassCommand = "kill";

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
                 VeoZax::printBanner($this);
        @mkdir($this->getDataFolder() . "worlds/");
        $this->loadWorlds();
        $this->getLogger()->info("WorldGuardian Activated");}
    public function onDisable(){
        $this->getLogger()->info("WorldGuardian Deactivated.");
    }

    private function loadWorlds(){
        $this->worlds = [];
        $folder = $this->getDataFolder() . "worlds/";
        foreach(glob($folder . "*.yml") as $file){
            $worldName = strtolower(basename($file, ".yml"));
            $allowed = (new Config($file, Config::YAML))->get("allowed", []);
            $this->worlds[$worldName] = array_map(function ($cmd) {
                return strtolower(ltrim(trim($cmd), "/"));
            }, $allowed); } }
    private function saveWorld(string $world) {
        $config = new Config($this->getDataFolder() . "worlds/$world.yml", Config::YAML);
        $config->set("allowed", $this->worlds[$world]);
        $config->save();
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args): bool {
        if(!$sender->hasPermission("worldguardian.admin")){
            $sender->sendMessage($this->prefix . "§cYou don't have permission to use this.");
            return true; }
        if(count($args) === 0){
            $this->sendHelp($sender);
            return true; }
        switch (strtolower($args[0])){
            case "help":
                $this->sendHelp($sender);
     break;
        case "reload":
        $this->loadWorlds();
        $sender->sendMessage($this->prefix . "§aConfiguration reloaded.");
  break;
       case "add":
       if(!isset($args[1])) {
          $sender->sendMessage($this->prefix . "§cUsage: /wg add <world>");
        return true; }
        $world = strtolower($args[1]);
        if(isset($this->worlds[$world])){
           $sender->sendMessage($this->prefix . "§cWorld §f$world §cis already added.");
            return true; }
                $this->worlds[$world] = [];
                $this->saveWorld($world);
                $sender->sendMessage($this->prefix . "§aWorld §f$world §awas added to WorldGuardian.");
                break;
        case "remove":
         if(!isset($args[1])) {
            $sender->sendMessage($this->prefix . "§cUsage: /wg remove <world>");
         return true; }
      $world = strtolower($args[1]);
            if (!isset($this->worlds[$world])) {
          $sender->sendMessage($this->prefix . "§cWorld §f$world §cis not in the list.");
        return true; }
         unset($this->worlds[$world]);
         @unlink($this->getDataFolder() . "worlds/$world.yml");
         $sender->sendMessage($this->prefix . "§aWorld §f$world §awas removed from WorldGuardian.");
         break;
      case "list":
         if (empty($this->worlds)){
             $sender->sendMessage($this->prefix . "§bNo worlds are currently locked.");
         return true; }
  $sender->sendMessage($this->prefix . "§bLocked worlds:");
         foreach ($this->worlds as $world => $allowed){
     $count = count($allowed);
           $sender->sendMessage("  §f$world §7- §a$count §7allowed commands"); }
      break;
            default:
       $world = strtolower($args[0]);
          if (!isset($this->worlds[$world])){
        $sender->sendMessage($this->prefix . "§cWorld §f$world §cis not in the list.");
    return true;  }
        if(!isset($args[1])){
     $allowedList = empty($this->worlds[$world]) ? "§7(none)" : "§f" . implode("§7, §f", $this->worlds[$world]);
        $sender->sendMessage($this->prefix . "§bAllowed commands in §f$world§b: $allowedList");
        return true; }
  $sub = strtolower($args[1]);
         if($sub === "allow"){
    if(!isset($args[2])){
        $sender->sendMessage($this->prefix . "§cUsage: /wg $world allow <command>");
             return true; }
 $cmd = strtolower(ltrim($args[2], "/"));
     if(in_array($cmd, $this->worlds[$world], true)){
        $sender->sendMessage($this->prefix . "§cCommand §f$cmd §cis already allowed in §f$world§c.");
       return true; }
       $this->worlds[$world][] = $cmd;
       $this->saveWorld($world);
       $sender->sendMessage($this->prefix . "§aCommand §f$cmd §awas allowed in §f$world §a.");
      } elseif ($sub === "deny"){
       if(!isset($args[2])) {
             $sender->sendMessage($this->prefix . "§cUsage: /wg $world deny <command>");
            return true; }
              $cmd = strtolower(ltrim($args[2], "/"));
              $key = array_search($cmd, $this->worlds[$world], true);
                    if ($key === false) {
                      $sender->sendMessage($this->prefix . "§cCommand §f$cmd §cisn't in the allowed list for §f$world§c.");
         return true; }
             unset($this->worlds[$world][$key]);
               $this->worlds[$world] = array_values($this->worlds[$world]);
               $this->saveWorld($world);
               $sender->sendMessage($this->prefix . "§aCommand §f$cmd §awas removed from §f$world §a's allowed list.");
                } else {
                    $sender->sendMessage($this->prefix . "§cUsage: /wg $world allow|deny <command>");
                }
                break; }
        return true; }
    private function sendHelp(CommandSender $sender){
        $sender->sendMessage($this->prefix . "§eWorldGuardian Commands");
        $sender->sendMessage("§8- §f/§dw§fg §aadd <world> §7- §6Lock a world");
        $sender->sendMessage("§8- §f/§dw§fg §aremove <world> §7- §6Unlock a world");
        $sender->sendMessage("§8- §§f/§dw§fg §alist §7- §6List all locked worlds");
        $sender->sendMessage("§8- §f/§dw§fg §areload §7- §6Reload configuration from disk");
        $sender->sendMessage("§8- §f/§dw§fg §a<world> §7- §6Show allowed commands for a world");
        $sender->sendMessage("§8- §§f/§dw§fg §a<world> allow <command> §7- §6Allow a command in a world");
        $sender->sendMessage("§8- §f/§dw§fg §a<world> deny <command> §7- §6Remove an allowed command");
    }
    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $msg = $event->getMessage();
        if(substr($msg, 0, 1) !== "/"){
            return; }
        if($player->hasPermission("worldguardian.bypass")){
            return; }
        $worldName = strtolower($player->getLevel()->getName());
        if(!isset($this->worlds[$worldName])){
            return; }
        $allowed = $this->worlds[$worldName];
        $withoutSlash = substr($msg, 1);
        $parts = explode(" ", $withoutSlash, 2);
        $command = strtolower($parts[0]);
        if($command === $this->bypassCommand){
            return; }
        if(in_array($command, $allowed, true)){
            return;
        }
        $event->setCancelled(true);
        $player->sendMessage($this->prefix . "§cYou can't use that command in this world. Try §b/" . $this->bypassCommand . "§c instead.");
    }
}