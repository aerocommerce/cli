# Aero Commerce CLI Tool

> A command line installer for your Aero Commerce projects.

## Installing the package

```
composer global require aerocommerce/cli
```

Make sure to place the `$HOME/.composer/vendor/bin` directory (or the equivalent directory for your OS) in your `$PATH` so that the `aero` executable can be located by your system.

Once installed, you should be able to run `aero {command}` from within any directory.


## Installing Aero Commerce

### Generating a New Site

Use the `new` command to create a new Aero Commerce site:

```
aero new my-site
```

This will download the latest version and install it into the `my-site` directory.

### Configuring Your Site

Run the configure command in your new site:

```bash
cd my-site
php artisan aero:configure
```

and answer the questions.

Alternatively you can manually set parameters in the generated `.env` file.

### Finalising installation

Finally run:

```bash
php artisan aero:install
```

to run database migrations etc.
