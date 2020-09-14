# pokedex-api-laravel

This is a little demo of an API allowing create, update, delete as well as a listing of pokemons.
The database is populated via a csv.

[Postman documentation](https://documenter.getpostman.com/view/12628792/TVK75Ke8)

[Staging url](https://pure-stream-21702.herokuapp.com)

## Installation 
Requires : 
- PHP >= 7.2
- [composer](https://getcomposer.org/download/)
- MySQL or equivalent
```bash
# set up your .env
cp .env.example .env
# then modify the infos in your .env
composer install
# generate app key (laravel specific)
php artisan key:generate
# Migrate all the database
php artisan migrate

# Install passport (for authentication)
# Note: this will generate key in your app/storage folder. You can also those keys in your .env like so:
# PASSPORT_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----\nMIIJJwIBAAKCAgEAw3KPag...\n-----END RSA PRIVATE KEY-----"
# PASSPORT_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOC...\n-----END PUBLIC KEY-----\n"
php artisan passport:install
# launch a development server
php artisan serve
```
## Conventions 
- Commits follow [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/)
- Code Style follows [PSR-12](https://www.php-fig.org/psr/psr-12/)

## Testing
You can run tests via `php artisan test`. It is recommended to have a test driven approach to your development.

## API 
[Postman documentation](https://documenter.getpostman.com/view/12628792/TVK75Ke8)
Some methods require an account. This can be created by the register method for now.

## Import

The pokemon data can be reset by running `php artisan pokemon:import`. See file `app/Console/Commands/ImportPokemon.php`.

## TODO 
### Must have
- [x] make a csv class to read the csv and make it into a collection.
- [x] create a Pokemon model, and the appropriate database migration
- [x] create an import command from the csv to the pokemons table
- [x] create the PokemonController, with postman documentation
### Should have
- [x] Heroku implementation
- [x] Do not allow create of the same name
- [x] create an autentication method for the API (use passport)
- [x] tidy code : put models in a Models/ directory
- [ ] remove register endpoint for even more security
- [ ] Commands, import : Bulk import instead of save in foreach
### Nice to have
- [ ] add a updated_by to the Pokemon class for more security
- [ ] soft delete
- [ ] Commands, import : make a separate command (or add option) that updates or create, and does not drop table pokemons
- [ ] Commands, import : allow to use another csv than /storage/app/csv/pokemon.csv
- [ ] Commands, import : make nice little user output
- [ ] Services, csv : make App\Services\CsvService take a path instead of writing and reading only in /storage/app/csv