<?php
namespace Windsor\Admin\WordPress;

use Tightenco\Collect\Support\Arr;
use Windsor\Admin\WordPress\AjaxHandler;

class UiLoader
{
    /**
     * Current asset version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Admin page suffix hook coming from menu registration
     *
     * @var string
     */
    protected $adminHook;

    /**
     * Determine if Windsor assets are enqueued as inline
     *
     * @var boolean
     */
    protected $shouldInlineAssets = false;

    public function __construct($config = [])
    {
        $this->shouldInlineAssets = Arr::get($config, 'inline_assets') === true;
    }

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
        $this->adminHook = add_submenu_page(
            'edit.php?post_type=acf-field-group',
            'Windsor - Export Tool',
            'Export to YAML',
            'manage_options',
            'windsor',
            array($this, 'renderAdminPage'),
            70
        );
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

        if ($this->shouldInlineAssets) {
            wp_add_inline_style(
                'windsor-prism-css',
                file_get_contents(__DIR__ . '/../../../frontend/assets/style.css')
            );
        } else {
            wp_enqueue_style(
                'windsor-css',
                get_stylesheet_directory_uri() . '/vendor/jofrysutanto/windsor/frontend/assets/style.css',
                [],
                $this->version,
                'all'
            );
        }
        // Prevent layout shift, intentionally hiding meta links on current page.
        wp_add_inline_style('windsor-prism-css', '#screen-meta-links { display: none }');

        $jsDependencies = [
            'windsor-prismjs-core'       => 'https://unpkg.com/prismjs@v1.x/components/prism-core.min.js',
            'windsor-prismjs-yaml'       => 'https://unpkg.com/prismjs@v1.x/components/prism-yaml.min.js',
            'windsor-jszip'              => 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js',
        ];
        foreach ($jsDependencies as $key => $cdn) {
            wp_enqueue_script($key, $cdn, [], null, true);
        }
        $lastHandle = array_keys($jsDependencies)[count($jsDependencies) - 1];
        if ($this->shouldInlineAssets) {
            wp_add_inline_script($lastHandle, file_get_contents(__DIR__ . '/../../../frontend/assets/index.js'));
        } else {
            wp_enqueue_script(
                'windsor-js',
                get_stylesheet_directory_uri() . '/vendor/jofrysutanto/windsor/frontend/assets/index.js',
                array_keys($jsDependencies),
                $this->version,
                true
            );
        }
        wp_localize_script($lastHandle, '_acf_windsor', [
            'acf' => [
                'version' => ACF_VERSION,
            ],
        ]);
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
