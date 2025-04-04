
# Commission Calculator

Reads transactions from input file, calculates commission based on BIN country and exchange rate.

## How to run

```bash
composer install
php app.php input.txt
```

## How to test

```bash
composer test
```

Tests use Mockery and PHPUnit.

## Structure

- `src/` → core logic
- `tests/` → unit tests
- `app.php` → entry point

## Notes
Make sure to copy the `env.loc` file to `.env` before running the application:
```bash
cp env.loc .env
```

## ✅ Requirements Checklist

| Requirement                                           | ✅ Done |
|-------------------------------------------------------|:------:|
| Refactored, clean, production-ready code              |  ✅    |
| Unit tests with API mocking (Mockery + PHPUnit)       |  ✅    |
| Commission rounded up to nearest cent                 |  ✅    |
| Output matches original script (except rounding)      |  ✅    |
| Extendable structure (Interfaces + DI)                |  ✅    |
| Composer used for autoloading and testing             |  ✅    |
| Proper error handling (no die/exit)                   |  ✅    |
| Folder structure, .gitignore, README included         |  ✅    |
