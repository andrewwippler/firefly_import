# GnuCash XML

I wrote this script to create a CSV from a .gnucash (XML, gzipped) file. The GNUCash `File > Export` csv was not to my liking.

## How to Use

1. Clone this repo
2. `composer install`
3. Save your compressed, XML format GnuCash file as `firefly_gz.gnucash` in this cloned repo's root
4. Change your php.ini `memory_limit` to be 2 GB or more.
5. Open a terminal in this projects folder and execute `php ./src/start.php`
6. Get some coffee
7. Import the CSV file into firefly-iii (column 0 is a unique ID)
8. Manually import splits from `txt.json`. (I used `Prettier` in VS Code to make the output look nice.)

## License

MIT

## Special Thanks

[Carsten Brandt](https://github.com/cebe/gnucash-php) - for his unfinished, WIP GnuCash PHP XML parser. [I edited his work](https://github.com/andrewwippler/gnucash-php/tree/dev) to be compatible with my GnuCash 4.4 document.
