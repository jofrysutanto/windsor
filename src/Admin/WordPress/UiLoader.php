<?php
namespace Windsor\Admin\WordPress;

use Windsor\Admin\WordPress\AjaxHandler;

class UiLoader
{
    /**
     * Current asset version
     *
     * @var string
     */
    protected $version = '0.9.7';

    /**
     * Admin page suffix hook coming from menu registration
     *
     * @var string
     */
    protected $adminHook;

    /**
     * All hooks and filters are registered through this method
     *
     * @return void
     */
    public function boot()
    {
        add_action('admin_menu', [$this, 'registerMenu']);
        AjaxHandler::instance()->registerHandlers();
    }

    /**
     * Register administration menu
     *
     * @return void
     */
    public function registerMenu()
    {
        $this->adminHook = add_submenu_page('edit.php?post_type=acf-field-group', 'Windsor - Export Tool', 'Export to YAML', 'manage_options', 'windsor', array($this, 'renderAdminPage'), 'dashicons-upload', 70);
        add_action('load-' . $this->adminHook, [$this, 'loadAssets']);
    }

    /**
     * Opportunity to add asset-related hooks, specific to Windsor
     *
     * @return void
     */
    public function loadAssets()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
        add_filter('script_loader_tag', [$this, 'addAssetAttribute'], 10, 3);
    }

    /**
     * Render HTML view of admin page
     *
     * @return void
     */
    public function renderAdminPage()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        // $key = 'group_5edb6bb061aef';
        // $result = \Windsor\Admin\WordPress\AjaxHandler::instance()
        //     ->loadFieldGroupForExport($key);
        // $yaml = new \Windsor\Admin\Exporter\YamlComposer($result, 'compact');
        // $yaml->generate();

        // Our Vite app register itself to this element
        // and let it take care of the rest
        echo '<div id="windsor"></div>';
    }

    /**
     * Enqueue additional assets
     *
     * @return void
     */
    public function enqueueAdminAssets()
    {
        wp_enqueue_style('windsor-prism-css', 'https://unpkg.com/prismjs@v1.x/themes/prism-tomorrow.css', []);
        wp_enqueue_style('windsor-css', get_stylesheet_directory_uri() . '/vendor/jofrysutanto/windsor/frontend/assets/style.css', [], $this->version, 'all');

        $jsDependencies = [
            'windsor-prismjs-core'       => 'https://unpkg.com/prismjs@v1.x/components/prism-core.min.js',
            'windsor-prismjs-autoloader' => 'https://unpkg.com/prismjs@v1.x/plugins/autoloader/prism-autoloader.min.js',
            'windsor-jszip'              => 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js',
        ];
        foreach ($jsDependencies as $key => $cdn) {
            wp_enqueue_script($key, $cdn, [], null, true);
        }
        wp_enqueue_script(
            'windsor-js',
            get_stylesheet_directory_uri() . '/vendor/jofrysutanto/windsor/frontend/assets/index.js',
            array_keys($jsDependencies),
            $this->version,
            true
        );
    }

    /**
     * Extend default asset enqueueing HTML attributes
     *
     * @param string $tag
     * @param string $handle
     * @param string $src
     * @return string
     */
    public function addAssetAttribute($tag, $handle, $src)
    {
        if ('windsor-js' !== $handle) {
            return $tag;
        }
        // Update script tag with 'module' type for Vite compatibility
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }
}
