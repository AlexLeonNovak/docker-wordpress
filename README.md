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
2. Reinitialize your repo 
    ```Bash 
    rm -rf .git
    git init
    git add .
    git commit -m "commit_message"
    ```
2. Create new `.env` file or run `cp .env.example .env`
3. Run command `bin/setup`

#### Exiting project
1. Create new project
2. Import your exiting db
3. Change siteurl and homeurl using wp-cli
    ```Bash
    bin/wp option update home 'http://example.com'
    bin/wp option update siteurl 'http://example.com'
    ```
4. Go to admin panel and regenerate permalinks

## Examples
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
**Note for WSL:** Move the project to your `/home/[user]` dir for HMR to work

1. Run `bin/npm install` for beginning use
2. Set `THEME_NAME` in `.env` file
3. Call function named `load_resources();` in your `functions.php` file
4. Run `bin/npm run dev`
5. Enjoy!

To generate files for production run `bin/npm run build`

`load_resources();` can take a variable parameter argument for js, ex.:
```php
load_resources([
    'str_var' => 'value',
    'obj_var' => [
        'key1' => 'val1',
        'key2' => 'val2',
    ],
]);
```
You can then use in your js files as these variables are added as global using WordPress function `wp_localize_script`
