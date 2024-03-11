<?php

use \Illuminate\Support\Collection;

function partial(string $partial): void
{
    $templateFile = realpath(get_theme_file_path("src/partials/{$partial}.php"));
    $templateFile && require_once $templateFile;
}

function getManifest(): Collection
{
    $path = realpath(get_theme_file_path('dist/entrypoints.json'));

    if (!$path) {
        wp_die('Run npm run build');
    }

    return Collection::make(
        json_decode(
            file_get_contents(
                get_theme_file_path('dist/entrypoints.json')
            )
        )
    );
};

function entrypoint(
    string $name,
    string $type,
    Object $entrypoint
) {
    $entrypoint->modules = Collection::make(
        $entrypoint->$type ?? (object) []
    );

    $hasDependencies = $type == 'js' &&
        property_exists($entrypoint, 'dependencies');

    $entrypoint->dependencies = Collection::make(
        $hasDependencies
            ? $entrypoint->dependencies
            : [],
    );

    return $entrypoint->modules->map(
        function ($module, $index) use ($type, $name, $entrypoint) {
            $name = "{$type}.{$name}.{$index}";

            $dependencies = $entrypoint->dependencies->all();

            $entrypoint->dependencies->push($name);

            return (object) [
                'name' => $name,
                'uri' => $module,
                'deps' => $dependencies,
            ];
        }
    );
}

function bundle(string $bundleName, $params = []): void
{
    $filterHot = fn ($entry) => !strpos($entry->uri, 'hot-update');
    $url = fn ($endpoint) => join("/", [get_template_directory_uri(), 'dist', $endpoint]);

    getManifest()
        ->filter(fn ($value, $key) => $key === $bundleName)
        ->map(fn ($item, $name) => (object) [
            'js' => entrypoint($name, 'js', $item),
            'css' => entrypoint($name, 'css', $item)
        ])
        ->each(function ($entrypoint) use ($filterHot, $url, $bundleName, $params) {
            $entrypoint->js->filter($filterHot)->each(
                function ($entry) use ($url, $bundleName, $params) {
                    wp_enqueue_script($entry->name, $url($entry->uri), $entry->deps, null, true);
                    if (str_contains($entry->uri, $bundleName) && $params) {
                        foreach ($params as $varName => $_params) {
                            wp_localize_script($entry->name, $varName, $_params);
                        }
                    }
                }
            );

            $entrypoint->css->filter($filterHot)->each(
                fn ($entry) =>
                wp_enqueue_style($entry->name, $url($entry->uri), $entry->deps, null)
            );
        });
};

function load_resources($params = []): void
{
    add_action('wp_enqueue_scripts', fn () => bundle('app', $params), 100);
    add_action('enqueue_block_editor_assets', fn () => bundle('editor'), 100);
}
