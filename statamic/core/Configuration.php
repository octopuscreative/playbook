<?php

namespace Statamic;

use Carbon\Carbon;
use Statamic\API\Arr;
use Statamic\API\File;
use Statamic\API\Path;
use Statamic\API\YAML;
use Statamic\API\Parse;
use Statamic\API\Config;
use Statamic\API\Folder;
use Statamic\API\Str;

/**
 * Site configuration
 */
class Configuration
{
    /**
     * @var array
     */
    private $env;

    /**
     * @var \Statamic\DataStore
     */
    private $store;

    /**
     * Create a new Configuration instance
     *
     * @param \Statamic\DataStore $store
     */
    public function __construct(DataStore $store)
    {
        $this->store = $store;
    }

    /**
     * Loads all config variables into the DataStore
     */
    public function loadAllConfigs()
    {
        $this->loadEnvironment();
        $this->loadDefaults();
        $this->loadSiteConfig();
        $this->loadAddonConfig();

        $this->mergeIntoLaravel();
    }

    /**
     * Load the environment file
     */
    public function loadEnvironment()
    {
        $env = app()->environment();

        $path = settings_path("environments/{$env}.yaml");

        $this->env = YAML::parse(File::get($path));

        datastore()->mergeIntoEnv($this->env);
    }

    /**
     * Loads default config variables
     */
    private function loadDefaults()
    {
        $files = Folder::getFilesByType(statamic_path('settings/defaults'), 'yaml');

        $settings = [];
        foreach ($files as $file) {
            $scope = pathinfo($file)['filename'];
            $settings[$scope] = YAML::parse(File::get($file));
        }

        $this->store->mergeInto('settings', $settings);
    }

    /**
     * Loads site config variables
     */
    private function loadSiteConfig()
    {
        $files = Folder::getFilesByType(settings_path(), 'yaml');

        $settings = [];
        foreach ($files as $file) {
            $scope = pathinfo($file)['filename'];
            $settings[$scope] = YAML::parse(File::get($file));
        }

        $settings_env = array_get($this->env, 'settings', []);

        $settings = Arr::combineRecursive($settings, $settings_env);

        $this->store->mergeInto('settings', $settings);
    }

    /**
     * Loads plugin config variables
     */
    private function loadAddonConfig()
    {
        foreach ([bundles_path(), addons_path()] as $addon_folder) {
            foreach (Folder::getFolders($addon_folder) as $addon) {
                $default = $config = [];

                // Get the default, if there is one
                if (File::exists($default_path = $addon . '/default.yaml')) {
                    $default = YAML::parse(File::get($default_path));
                }

                // Get the user addon config files
                $addon_name = Str::snake(basename($addon));
                if (File::exists($main_file = settings_path('addons/'.$addon_name.'.yaml'))) {
                    $config = YAML::parse(File::get($main_file));
                }

                // Merge with the environment
                $env = array_get($this->env, "addons.{$addon_name}", []);
                $config = Arr::combineRecursive($config, $env);

                // Add them to the addons scope
                if (! empty($default) || ! empty($config)) {
                    $this->store->mergeInto('addons', [
                        $addon_name => $config + $default
                    ]);
                }
            }
        }
    }

    /**
     * Merge appropriate config values into Laravel
     *
     * There are settings that are set in our Statamic YAML files
     * that won't automatically affect the Laravel settings.
     */
    private function mergeIntoLaravel()
    {
        config([
            'app.debug' => env('APP_DEBUG', Config::get('debug.debug')),

            'mail.driver' => Config::get('email.driver'),
            'mail.host' => Config::get('email.host'),
            'mail.port' => Config::get('email.port'),
            'mail.host' => Config::get('email.host'),
            'mail.encryption' => Config::get('email.encryption'),
            'mail.username' => Config::get('email.username'),
            'mail.password' => Config::get('email.password'),
            'mail.from' => ['address' => Config::get('email.from_email'), 'name' => Config::get('email.from_name')],
            'services.mandrill.secret' => Config::get('email.mandrill_secret'),
            'services.mailgun.secret' => Config::get('email.mailgun_secret'),
            'services.mailgun.domain' => Config::get('email.mailgun_domain'),

            'search.connections.algolia.config.application_id' => Config::get('search.algolia_app_id'),
            'search.connections.algolia.config.admin_api_key' => Config::get('search.algolia_api_key'),
        ]);
    }

    /**
     * Load user roles
     */
    public function loadRoles()
    {
        $path = settings_path('users/roles.yaml');

        $roles = YAML::parse(File::get($path));

        foreach ($roles as $uuid => $data) {
            $roles[$uuid] = app('Statamic\Contracts\Permissions\RoleFactory')->create($data, $uuid);
        }

        $this->store->createScope('roles', $roles);
    }
}
