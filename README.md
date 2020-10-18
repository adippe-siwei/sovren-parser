# sovren-parser
A caller and response parser for Sovren API

## Installation

You can install the package via composer:


```bash
composer require siwei/sovren-parser
```

## Usage

After installing, the package will automatically register its service provider.

To publish the config file to config/sovren.php run:

```bash
php artisan vendor:publish --provider="Siwei\SovrenParser"
```

And change your API keys accordingly.

### Usage
```php
$resume = Sovren::parse(FULL_PATH_TO_RESUME);
```

### Security

If you discover any security related issues, please email adippe@siwei.fr.

## Credits

- [Aurélien Dippe](https://github.com/adippe-siwei)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
