<?php

class Querlo_Admin
{

  const VERSION = '1.2';

  private $slug;

  public function __construct($slug)
  {
    $this->slug = strtolower($slug);
  }


  public function init()
  {
    $this->initHooks();
  }

  private function initHooks()
  {
    add_action('admin_menu', [$this, 'registerSettingsPage']);
    add_action('admin_init', [$this, 'registerSettingsSectionsAndFields']);
  }

  public function registerSettingsPage()
  {
    add_menu_page(
      "Settings",
      "Querlo Chatbot",
      "manage_options",
      $this->slug . "-admin-main-menu",
      [$this, 'renderMainSettingsPage'],
      plugins_url( 'querlo-chatbots/assets/menu-icon.png' ),
      81
    );
  }

  public function registerSettingsSectionsAndFields()
  {
    add_settings_section(
      $this->slug . '-main-page-main-settings-section',
      "Main Settings",
      [$this, 'renderMainSettingsSection'],
      $this->slug . '-admin-main-page'
    );

    register_setting(
      $this->slug . "-settings",
      $this->slug . "-settings",
      ['sanitize_callback' => [$this, 'embedCodesByLocationSanitizeCallback']]
    );

    add_settings_field(
      $this->slug . '-embeds_by_location_regex',
      'Embed Codes by location',
      [$this, 'embedCodesByLocationRenderCallback'],
      $this->slug . '-admin-main-page',
      $this->slug . '-main-page-main-settings-section'
    );
  }

  public function renderMainSettingsSection() {
    return;
  }

  public function renderMainSettingsPage()
  {
//    print plugin_dir_path(  __FILE__  ) . 'views/admin-main-settings_v.php';
    $_['slug'] = $this->slug;
    require_once plugin_dir_path(  __FILE__ ) . 'views/admin-main-settings_v.php';
  }

  public function embedCodesByLocationSanitizeCallback($value)
  {
/*      var_dump($value);
      exit;*/
    if (!is_array($value)) {
        $value = [];
    }

    $value['version'] = self::VERSION;
    return $value;
  }

  public function embedCodesByLocationRenderCallback($value)
  {
    $_['slug'] = $this->slug;
    $option = get_option($this->slug . "-settings", ["locations" => ["*"], "embeds" => [""]]);
//    var_dump($option);

    require_once plugin_dir_path(  __FILE__ ) . 'views/admin-embeds-by-location_v.php';
  }
}
