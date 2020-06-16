# ACF Windsor

This package extends [Advanced Custom Fields](https://advancedcustomfields.com) plugin for Wordpress and enable developers to write their ACF fields blazingly fast in configuration file.

![ACF Windsor](https://raw.githubusercontent.com/jofrysutanto/windsor/master/screenshot.png)

### Features
- Permanently lock your custom fields in your version-controlled code, preventing accidental edits that quickly leads to out-of-sync configurations.
- Create your fields much faster, especially when complimented with the IDE integration.
- Composition is at the heart of it. Write your own rules to further supercharge your development productivity.

### Getting Started

- The easiest way to install Windsor is to use composer:
```
composer require jofrysutanto/windsor "^0.9"
```
- If you are using VSCode, be sure to add the Schema file to your configuration.
- Ensure you have included composer auto-loader file. If you're not sure, add the following line into your `functions.php` file:
```php
require_once __DIR__ . '/vendor/autoload.php';
```
- Register Windsor on ACF initialization. You may also do this in `functions.php` file:
```php
function register_acf_windsor()
{
    \Windsor\Capsule\Manager::make()->register();
}
add_action('acf/init', 'register_acf_windsor');
```
- Create YAML entry file at `[your-active-theme]/acf-fields/index.yaml`, where `[your-active-theme]` refers to your currently active Wordpress theme directory. At minimum, your entry file should contain:
```yaml
fields: []
pages: []
```
- Test your installation:
  - Create your first custom field YAML, for example create a file `your-theme/acf-fields/page-default.acf.yaml`:
  ```yaml
  title: 'Page Default'
  key: 'page_default'
  position: 'acf_after_title'
  hide_on_screen: []
  location:
    -
      -
        param: 'page_template'
        operator: '=='
        value: 'default'
  fields:
    heading:
      type: text
      label: Heading
  ```
  - Register this new ACF file in your index:
  ```yaml
  fields: []
  pages:
    - page-default.acf.yaml
  ```
  - You have successfully registered a new field group which will be made available when creating a new default page.
- Check out our full documentation below. Now go and create beautiful ACF fields!

## Learn More
Check out full documentations at [https://windsor-docs.netlify.app/](https://windsor-docs.netlify.app/)

## IDE Integration

Only VSCode integration is available at the moment. To enable autocompletion and useful snippets, follow the installation steps below:
- If not already installed, download and enable [YAML language server](https://marketplace.visualstudio.com/items?itemName=redhat.vscode-yaml) extension.
- Update your VSCode [settings](https://code.visualstudio.com/docs/getstarted/settings#_settings-file-locations) (i.e. `settings.json`):
```json
"yaml.schemas": {
    "https://windsor-docs.netlify.app/schema.json": "*.acf.yaml"
}
```

## Credits

This package is written to be used with [Advanced Custom Fields](https://www.advancedcustomfields.com/) plugin by [Elliot Condon](https://www.elliotcondon.com/), who deserves most of the credits for delivering and maintaining such an incredible plugin for Wordpress developers.

If you have not already started using Advanced Custom Fields, be sure to check it out; it will definitely be worth your while.
