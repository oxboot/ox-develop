# Ox
**Ox currently supports:**
- Ubuntu 16.04
## Install
```bash
wget -qO ee oxboot.com/ox && sudo bash ox
```
## Commands
```bash
ox site:create domain.dev
ox site:create domain.dev --mysql
ox site:create domain.dev --mysql --package=wp
ox site:create domain.dev --mysql --package=oxboot
ox site:create domain.dev --mysql --package=bedrock
ox site:create domain.dev --package=grav
```
```bash
ox site:delete domain.dev
```
```bash
ox site:info domain.dev
```
