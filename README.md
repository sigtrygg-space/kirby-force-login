# Force Login

A plugin for [Kirby CMS](https://getkirby.com/docs/reference).

- forces all users to login on non-panel pages
- redirects back to blocked page via urlParam after login

Especially useful for staging websites.

Inspired by [WordPress plugin - Force Login](https://de.wordpress.org/plugins/wp-force-login/)

## Requirements

- Kirby 5.1+ (just tested with 5.1 probably works on previous versions too)

## Installation

### Download

Download and copy this repository to `/site/plugins/kirby-force-login`.

### Composer

```
composer require andrekelling/kirby-force-login
```

## Usage

`andrekelling.force-login.is-active` supports `bool` and `callable` values in your config (for example in `site/config/config.php`).

### Bool example

```php
return [
    'andrekelling.force-login' => [
        'is-active' => true,
    ],
];
```

### Callable example

The callable should return `true` or `false` depending on your runtime checks.

```php
return [
    'andrekelling.force-login' => [
        'is-active' => function (): bool {
            if (!defined('SOMETHING') || SOMETHING !== true) return false;
            $path = (string) kirby()->request()->path();
            if (in_array($path, ['somepath', 'other/path', 'whatever/path.html'], true)) return false;
            return true;
        },
    ],
];
```

## License

[MIT License](./LICENSE)
Copyright © 2025 André Kelling
