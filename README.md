# Aero Commerce CLI Tool

> Install and manage your Aero Commerce projects from the command line.

## Installing the package

```
composer global require aerocommerce/cli
```

Make sure to place the `$HOME/.composer/vendor/bin` directory (or the equivalent directory for your OS) in your `$PATH` so that the `aero` executable can be located by your system.

Once installed, you should be able to run `aero {command name}` from within any directory.


## Installing Aero Commerce

You may create a new Aero Commerce site with the `new` command:

```
aero new my-site
```

This will download the latest version and install it into the `my-site` directory.
