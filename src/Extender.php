<?php

namespace AcfYaml;

class Extender
{
    /**
     * Array of extend classes
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Flag to determine if extensions have been registered
     *
     * @var boolean
     */
    protected $isRegistered = false;

    /**
     * Register new field class to acf
     *
     * @return $this
     */
    public function extendField($class)
    {
        $this->extensions[] = $class;
        return $this;
    }

    /**
     * Register all extensions
     *
     * @return $this
     */
    public function register()
    {
        foreach ($this->extensions as $extendClass) {
            // Based on how ACF works, just creating new instance
            // of extension is enough, all extensions inherits from acf_field
            new $extendClass;
        }
        $this->isRegistered = true;
        return $this;
    }

    /**
     * Register global options specific to post types
     * Useful for registering ACF fields for archive-based templates
     *
     * @return void
     */
    public function addArchiveOptionsPage()
    {
        // Check if ACF is installed
        if (!function_exists('acf_add_options_page')) {
            return;
        }

        $ctpacf_post_types = get_post_types([
            '_builtin'    => false,
            'has_archive' => true
        ]); //get post types

        foreach ($ctpacf_post_types as $cpt) {
            if (!post_type_exists($cpt)) {
                continue;
            }

            $postType = app('post-type')->get($cpt);
            if (!(
                    $postType
                    && property_exists($postType, 'hasAcfArchive')
                    && $postType->hasAcfArchive
                )) {
                continue;
            }

            $cptname = get_post_type_object($cpt)->labels->name;
            $cpt_post_id = 'cpt_' . $cpt;

            if (defined('ICL_LANGUAGE_CODE')) {
                $cpt_post_id = $cpt_post_id . '_' . ICL_LANGUAGE_CODE;
            }

            $cpt_acf_page = [
                'page_title'  => ucfirst($cptname) . ' Archive',
                'menu_title'  => ucfirst($cptname) . ' Archive',
                'parent_slug' => 'edit.php?post_type=' . $cpt,
                'menu_slug'   => $cpt . '-archive',
                'capability'  => 'edit_posts',
                'post_id'     => $cpt_post_id,
                'position'    => false,
                'icon_url'    => false,
                'redirect'    => false
            ];

            acf_add_options_page($cpt_acf_page);
        }
    }

    /**
     * Merge content fields into custom
     *
     * @return  void
     */
    public function seamlessContentFields()
    {
        ?>
            <script type="text/javascript">
            (function($) {
                
                $(document).ready(function() {
                    if ($('#postdivrich').length && $('#seamless').length) {
                        $('#postdivrich').appendTo($('#seamless .acf-input'))
                    }
                });
                
            })(jQuery);    
            </script>
            <style type="text/css">
                .acf-field #wp-content-editor-tools {
                    background: transparent;
                    padding-top: 0;
                }
            </style>
            <?php
    }
}
