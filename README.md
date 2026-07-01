# WorldGuardian

A powerful yet lightweight command restrict and server protection plugin for PocketMine-MP. Lock specific commands on specific worlds so players can only run commands you explicitly allow. keeping minigame, lobby, or event worlds completely under control.

![Version](https://img.shields.io/badge/version-0.14.x__0.15.10-blue)
![API](https://img.shields.io/badge/PocketMine--MP-API-green)
![License](https://img.shields.io/badge/license-MIT-lightgrey)

## Features

- **Per-world command locking** - lock any world and block all commands inside it by default.
- **Allowlist system** - whitelist specific commands per world. everything else is blocked.
- **Bypass permission** - give trusted players `worldguardian.bypass` to ignore all restrictions.
- **Hot-reload** - reload the configuration from ingame without restarting the server.
- **No manual config editing** - add, remove, allow, and deny everything in-game.
- **Persistent storage** - each locked world gets its own YAML file under `plugins/WorldGuardian/worlds/`.
- **Alias support** - use `/wg` as a short alias for `/worldguardian`.

## Requirements

- PocketMine-MP (compatible with builds 0.14.x – 0.15.10)
- No external dependencies required.

## Download

Get the latest release from this repository directly.

## Installation

1. Download `WorldGuardian.phar` from here: [Click To Download](https://www.mediafire.com/file/ok5ohubrlvjyu6y/WorldGuardian_v0.14.x_0.15.10.phar/file)
2. Place it in your server's `plugins/` folder.
3. Restart your server.
4. Use `/wg add <worldname>` to start locking worlds.

## Commands

All commands require the `worldguardian.admin` permission (default: **op**).
Alias: `/wg`

| Command | Description |
|---|---|
| `/wg add <world>` | Locks a world - all commands blocked by default |
| `/wg remove <world>` | Unlocks a world and removes its config |
| `/wg list` | Lists all locked worlds and their allowed command count |
| `/wg <world>` | Shows the current allowed commands for a world |
| `/wg <world> allow <command>` | Whitelists a command in the specified world |
| `/wg <world> deny <command>` | Removes a command from the whitelist |
| `/wg reload` | Reloads all world configs from disk |
| `/wg help` | Shows the in-game help menu |

## Permissions

| Permission | Default | Description |
|---|---|---|
| `worldguardian.admin` | op | Full access to all WorldGuardian management commands |
| `worldguardian.bypass` | op | Bypasses all command restrictions in locked worlds |

## Tutorial - Locking Your Lobby World

1. **Lock the world** (replace `lobby` with your actual world name):
   ```
   /wg add lobby
   ```
2. **Check what's currently allowed** (will be empty if you didnt allowed any commands yet):
   ```
   /wg lobby
   ```
3. **Allow specific commands** players should still be able to use:
   ```
   /wg lobby allow spawn
   /wg lobby allow hub
   /wg lobby allow help
   ```
4. **Remove a command from the whitelist** if you change your mind:
   ```
   /wg lobby deny help
   ```
5. **List all locked worlds** at any time:
   ```
   /wg list
   ```
6. **Reload config** after any manual file edits:
   ```
   /wg reload
   ```
7. **Unlock the world** entirely when you no longer need it protected:
   ```
   /wg remove lobby
   ```

> Players with `worldguardian.bypass` (default: op) are never blocked and can run any command in any world.

## Config Structure

Each locked world automatically gets a file at:
```
plugins/WorldGuardian/worlds/<worldname>.yml
```
Example `lobby.yml`:
```yaml
allowed:
  - spawn
  - hub
  - help
```
You can edit these files manually - just run `/wg reload` afterward.

## Support

Found a bug or have a suggestion? Contact us on Discord: https://discord.gg/dCzgPYam2J

## Author

**VeoZax**
GitHub: [github.com/VeoZaxOfficial](https://github.com/VeoZaxOfficial)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Copyright © 2026 VeoZax. All rights reserved.
