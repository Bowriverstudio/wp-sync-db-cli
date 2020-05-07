<?php

/**
 * Migrate your DB using WP Sync DB.
 */
class WPSDBCLI extends WP_CLI_Command
{

    /**
     * Run a migration.
     *
     * ## OPTIONS
     *
     * <profile>
     * : ID of the profile to use for the migration.
     *
     * ## EXAMPLES
     *
     *     wp wpsdb migrate 1
     *
     * @synopsis <profile>
     *
     * @since 1.0
     */
    public function migrate($args, $assoc_args)
    {
        $profile = $args[0];

        $result = wpsdb_migrate($profile);

        if (true === $result) {
            WP_CLI::success(__('Migration successful.', 'wp-sync-db-cli'));
            return;
        }

        WP_CLI::warning($result->get_error_message());
        return;
    }

    /**
     * List all profiles.
     *
     * ## EXAMPLES
     *
     *     wp wpsdb profiles
     *
     * @since 1.1 - Changed in git pull
     *
     */
    public function profiles()
    {
        $result = wpsdb_profiles();
        if ($result) {
            WP_CLI::log(__($result, 'wp-sync-db-cli'));
            return;
        }
        WP_CLI::warning(__('You have no profiles.', 'wp-sync-db-cli'));
        return;
    }

    /**
     * Sets the "Allow Pull" option to true
     *
     * ## EXAMPLES
     *
     *     wp wpsdb allow
     *
     * @since 1.2
     *
     */
    public function display_options()
    {
        $option = get_option('wpsdb_settings');
        if (function_exists('d')) {
            d($option);
        } else {
            var_dump($option);
        }
    }
    /**
     * Sets the "Allow Pull" option to true
     *
     * ## EXAMPLES
     *
     *     wp wpsdb allow_pull
     *
     * @since 1.2
     *
     */
    public function allow_pull()
    {
        $this->update_option('allow_pull', true);
        $this->display_options();
        return;
    }

    /**
     * Sets the "Allow Pull" option to false
     *
     * ## EXAMPLES
     *
     *     wp wpsdb disallow_pull
     *
     * @since 1.2
     *
     */

    public function disallow_pull()
    {
        $this->update_option('allow_pull', false);
        $this->display_options();
        return;
    }

    /**
     * Sets the "Allow Push" option to true
     *
     * ## EXAMPLES
     *
     *     wp wpsdb allow_push
     *
     * @since 1.2
     *
     */
    public function allow_push()
    {
        $this->update_option('allow_push', true);
        $this->display_options();
        return;
    }

    /**
     * Sets the "Allow Push" option to false
     *
     * ## EXAMPLES
     *
     *     wp wpsdb disallow_push
     *
     * @since 1.2
     *
     */

    public function disallow_push()
    {
        $this->update_option('allow_push', false);
        $this->display_options();
        return;
    }

    /**
     * Sets the "Allow Push" option to false
     *
     * ## EXAMPLES
     *
     *     wp wpsdb print_api_key
     *
     * @since 1.2
     *
     */

    public function print_api_key()
    {
        $option = get_option('wpsdb_settings');
        WP_CLI::success(__('API Key is:'));
        WP_CLI::success(__(site_url() . '/' . $option['key']));

        return;
    }

    private function update_option($field, $value)
    {
        $option = get_option('wpsdb_settings');
        if (!$option) {
            WP_CLI::warning(__('wpsdb_settings does not exist'));
            return;
        }
        $option[$field] = $value;
        update_option('wpsdb_settings', $option);
    }

}

WP_CLI::add_command('wpsdb', 'WPSDBCLI');
