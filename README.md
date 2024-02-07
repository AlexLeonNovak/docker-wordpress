# Wordpress boilerplate
This is repository build based on [Bedrock](https://roots.io/bedrock/) and [markshust/docker-magento](https://github.com/markshust/docker-magento)

**Windows users**, before using this tool please make sure that you run any command on WSL.

## Cli command
You can view the description of most commands [here](https://github.com/markshust/docker-magento/blob/master/README.md#custom-cli-commands)

Below are just a few commands added to work with wordpress

| Command     | Description                                                                        |
|-------------|------------------------------------------------------------------------------------|
| `bin/setup` | Start all containers, install composer packages and install wordpress              |
| `bin/wp`    | Run [WP-CLI](https://developer.wordpress.org/cli/commands/). Ex. `bin/wp db check` |
| `bin/npm`   | Run npm scripts                                                                    |

### Setup
#### Create new project
1. Clone this repo
2. Create new `.env` file or run `cp .env.example .env`
3. Run command `bin/setup`

#### Exiting project
1. Create new project
2. Import your exiting db
3. Change siteurl and homeurl using wp-cli
    ```Bash
    wp option update home 'http://example.com'
    wp option update siteurl 'http://example.com'
    ```
4. Go to admin panel and regenerate permalinks


#### Export/Import db
```Bash
# Dump db
bin/wp db export - > temp/wordpress.sql
# Or using gzip
bin/wp db export - | gzip > temp/wordpress.sql.gz

# Import db
bin/wp db import - < temp/wordpress.sql
# Or using gzip
gunzip < temp/wordpress.sql.gz | bin/wp db import -
```

## Build tool for SCSS and JS
[<img src="https://cdn.roots.io/app/uploads/logo-bud.svg" width=100 />](https://bud.js.org/)

### Quick start
1. Run `bin/npm install` for beginning use
2. Set `THEME_NAME` in `.env` file
3. Call function named `load_resources();` in your `functions.php` file
4. Run `bin/npm run dev`
5. Enjoy!

To generate files for production run `bin/npm run build`

