<?php
/**
 * Plugin Name: Querlo chatbots
 * Plugin URI: https://www.querlo.com/products/chatbot-studio
 * Tags: support chat, virtual assistant, customer support chat, customer chat plugin, chat plugin, messenger, chat, Querlo chatbot, chatbot, live chat, chatbot, chat plugin, customer chat plugin, live chat plugin
 * Description: Embed and manage Querlo chatbots on wordpress.
 * Version: 1.2.8
 * Author: Querlo
 * Author URI: http://www.querlo.com
 */

// Prevent direct access (outside of WordPress)
if (! defined('ABSPATH')) return;

class Querlo
{
  private $slug        = 'Querlo_chatbots';

  private function initPlugin() {
        require_once(plugin_dir_path(__FILE__).'admin/Querlo_Admin.php');
        require_once(plugin_dir_path(__FILE__).'public-site/Querlo_PublicSite.php');
        $admin = new Querlo_Admin($this->slug);
        $publicSite = new Querlo_PublicSite($this->slug);
        $admin->init();
        $publicSite->init();
    }

   public function __construct()
   {
       $this->initPlugin();
   }
}

$Querlo = new Querlo();
