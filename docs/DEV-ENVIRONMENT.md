# Local Development Environment

Installed into the home directory, so nothing needed an administrator password.

| Tool | Version | Location |
|---|---|---|
| PHP | 8.3.12 | `~/.config/herd-lite/bin` |
| Composer | 2.8.12 | `~/.config/herd-lite/bin` |
| Node | 22.20.0 | `~/.local/node/bin` |
| MySQL | 8.4.6 | `~/.local/mysql` |
| graphify | latest | `~/.local/bin` |

Add to `PATH` (already appended to `~/.zshrc` for PHP):

```
export PATH="$HOME/.config/herd-lite/bin:$HOME/.local/node/bin:$HOME/.local/bin:$HOME/.local/mysql/bin:$PATH"
```

## MySQL

Runs on **port 3307** to avoid clashing with any other install. Data lives in `~/.local/mysql-data`, config in `~/.local/mysql/my.cnf`. User `root` with no password, local only.

Start:
```
mysqld --defaults-file=$HOME/.local/mysql/my.cnf &
```
Stop:
```
mysqladmin --socket=$HOME/.local/mysql-data/mysql.sock -uroot shutdown
```
Databases: `gconsultancy` (development) and `gconsultancy_test` (test suite, configured in `phpunit.xml`).

Production on Hostinger uses port 3306 with a real password. See `docs/HOSTING-HOSTINGER.md`.

## Daily commands

```
php artisan serve          # http://127.0.0.1:8000
php artisan test           # full suite against gconsultancy_test
vendor/bin/pint            # format
npm run dev                # asset watch
npm run build              # production assets (committed)
```
