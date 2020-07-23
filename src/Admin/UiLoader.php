<?php
namespace Windsor\Admin;

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
        // // load field group
        // $field_group = acf_get_field_group($key);
        // // load fields
        // $field_group['fields'] = acf_get_fields($field_group);
        // // prepare for export
        // $field_group = acf_prepare_field_group_for_export($field_group);

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
        wp_enqueue_style('windsor-css', get_stylesheet_directory_uri() . '/vendor/jofrysutanto/windsor/frontend/dist/style.css', [], $this->version, 'all');
        wp_enqueue_script('windsor-js', get_stylesheet_directory_uri() . '/vendor/jofrysutanto/windsor/frontend/dist/index.js', [], $this->version, true);
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
