# pokedex-api-laravel

This is a little demo of an API allowing create, update, delete as well as a listing of pokemons.

## Installation 
Requires : 
- PHP >= 7.2
- [composer](https://getcomposer.org/download/)
- MySQl or equivalent
```bash
# set up your .env
cp .env.example .env
# then modify the infos in your .env
composer install
# generate app key (laravel specific)
php artisan key:generate
# Migrate all the database
php artisan migrate
```
## Conventions 
- Commits follow [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/)
- Code Style follows [PSR-12](https://www.php-fig.org/psr/psr-12/)

