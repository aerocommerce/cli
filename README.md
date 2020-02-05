# Aero Commerce CLI Tool

> A command line installer for your Aero Commerce projects.

## Installing the package

```
composer global require aerocommerce/cli
```

Make sure to place the `$HOME/.composer/vendor/bin` directory (or the equivalent directory for your OS) in your `$PATH` so that the `aero` executable can be located by your system.

Once installed, you should be able to run `aero {command}` from within any directory.


## Installing Aero Commerce

### Generating a New Project

Use the `new` command to create a new Aero Commerce project:

```
aero new my-store
```

This will download the latest version and install it into the `my-store` directory.
