# pokedex-api-laravel

This is a little demo of an API allowing create, update, delete as well as a listing of pokemons.
The database is populated via a csv. 

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

# launch a development server
php artisan serve
```
## Conventions 
- Commits follow [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/)
- Code Style follows [PSR-12](https://www.php-fig.org/psr/psr-12/)

## Testing
You can run tests via `php artisan test`. It is recommended to have a test driven approach to your development.

## TODO 
### Must have
- [ x ] make a csv class to read the csv and make it into a collection.
- [ x ] create a Pokemon model, and the appropriate database migration
- [ ] create an import command from the csv to the pokemons table
- [ ] create an autentication method for the API (use passport)
    - note : add a updated_by to the Pokemon class for more security
- [ ] create the PokemonController, with postman documentation
### Should have
- [ ] remove register endpoint for even more security
- [ ] tidy code : put models in a Models/ directory
### Nice to have
- [ ] make App\Services\CsvService take a path instead of writing and reading only in /storage/app/csv
- [ ] change pokemon model to secure the content of the types