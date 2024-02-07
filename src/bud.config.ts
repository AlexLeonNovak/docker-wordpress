import type {Bud} from '@roots/bud';

const { WP_DOMAIN, THEME_NAME = 'twentytwentyfour' } = process.env;

const THEME_PATH = `web/app/themes/${THEME_NAME}`;
/**
 * bud.js configuration
 */
export default async (bud: Bud) => {
  bud

    // Add theme path to project
    .setPath('@theme', bud.path(THEME_PATH))

    // Path of source *.scss and *.[tj]s files
    .setPath('@src', bud.path('resources'))

    // Path of output files
    .setPath('@dist', '@theme/dist')

    // Copy images from @src to @dist
    // .assets(['images'])

    /**
     * Add global `app` group
     *
     * @link https://bud.js.org/reference/bud.entry
     */
    .entry('app', ['main.ts', 'main.scss'])

    // Live reload page if files changed
    .watch(bud.path(THEME_PATH, '*.php'))

    .devtool('eval-source-map')
    .minimize(bud.isProduction)
    .setProxyUrl(`https://${WP_DOMAIN}`)
  ;
  // Enable sourcemaps
  bud.postcss.setSourceMap(true);
};
