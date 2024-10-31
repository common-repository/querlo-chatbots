<?php

class Querlo_PublicSite
{


  private $slug;

  public function __construct($slug)
  {
    $this->slug = strtolower($slug);
  }

  private function is_rest_request() {
    return defined( 'REST_REQUEST' ) && REST_REQUEST;
  }

  private function getCurrentUrl()
  {
    global $wp;
    return add_query_arg( $wp->query_vars, home_url( $wp->request ) );
  }

  public function init()
  {
    /** in widget previews, in admin, wp_footer is called when rendering the preview, but since such previews are requested through REST requests,
     * we can leverage this to skip rendering the chatbot in such cases
     */
    if ($this->is_rest_request()) {
      return;
    }
    $this->initHooks();
  }

  private function initHooks()
  {
    add_action ('wp_footer', array($this, 'AddEmbedJavaScriptToPage'));
  }

  private function calculateMatchPriority(
    $weightableLocationLowerCase,
    $weightableLocationRawLength,
    $weightableLocationHasEndWildcard,
    $weightableLocationHasDomain
  ) {
      if ($weightableLocationHasDomain) {
        $currentLocationParsed = parse_url($weightableLocationLowerCase);
        if ($currentLocationParsed === false) {
          return -1;
        }
        $weightablePart = $currentLocationParsed['path'];
        $weightableLocationRawLength = mb_strlen($weightablePart);
      }


      if ($weightableLocationHasEndWildcard) {
        $priority = $weightableLocationRawLength - 1;
      } else {
        $priority = $weightableLocationRawLength + 0.1;
      }

      return $priority;
  }


  /**
   * @param array $locationsDefinition
   * @param string $currentUrl
   * @param boolean $debugMatches
   * @return int index of matched location relative to the passed $locationsDefinition array, or -1 if there are no matches for given URL
   * @noinspection PhpSameParameterValueInspection
   */
  private function matchURL($locationsDefinition, $currentUrl, $debugMatches = false)
  {
    $currentPageUrlLowerCase = mb_strtolower($currentUrl);
    $currentPageUrlParsed = parse_url($currentPageUrlLowerCase);
    if ($currentPageUrlParsed === false) {
      return -1;
    }
    $currentPageUrlNoDomain = ($currentPageUrlParsed['path'] ?? '/') .
                                (!empty($currentPageUrlParsed['query']) ? ("?" . $currentPageUrlParsed['query']) : '');

    $matches = [];
    $selectedLocationIndex = -1;
    $maxMatchedLocationPriority = -1;

    foreach ($locationsDefinition as $i => $location) {
      $configuredLocationLowerCase = mb_strtolower(trim($location));
      $configuredLocationRawLength = mb_strlen($configuredLocationLowerCase);
      $configuredLocationHasEndWildcard =
        mb_substr($configuredLocationLowerCase, $configuredLocationRawLength -1, 1) === "*";
      $configuredLocationHasDomain = mb_substr($configuredLocationLowerCase, 0, 4) === "http";

      $currentPageURLCheckableVersion = $configuredLocationHasDomain ? $currentPageUrlLowerCase : $currentPageUrlNoDomain;

      if ($configuredLocationHasEndWildcard) {
        $matched = $configuredLocationLowerCase === "*" || mb_strpos($currentPageURLCheckableVersion, mb_substr($configuredLocationLowerCase, 0, $configuredLocationRawLength -1)) === 0;
      } else {
        $matched = $currentPageURLCheckableVersion === $configuredLocationLowerCase;
      }

      if ($matched) {
        $matchPriority = $this->calculateMatchPriority($configuredLocationLowerCase, $configuredLocationRawLength, $configuredLocationHasEndWildcard, $configuredLocationHasDomain);
        $matches[] = [$i, $configuredLocationLowerCase, $matchPriority];
        if ($matchPriority > $maxMatchedLocationPriority) {
          $selectedLocationIndex = $i;
          $maxMatchedLocationPriority = $matchPriority;
        }
      }
    }

    if ($debugMatches) {
      print_r($matches);
    }
    return $selectedLocationIndex;
  }

  public function AddEmbedJavaScriptToPage ()
  {
//    print "URL test: " . $this->getCurrentUrl();
    // Ignore the backend (admin pages)
/*    if (is_admin())
      return;*/

    // Read plugin settings from DB (falling back to defaults)
    $settings = get_option($this->slug . "-settings");

    if (empty($settings['locations'])) {
      return;
    }

    $matchedIndex = $this->matchURL($settings['locations'], $this->getCurrentUrl());

    if ($matchedIndex === -1) {
      return;
    }

    $output = $settings['embeds'][$matchedIndex];

    $allowed_html = [
      'script' => [
        'src'     => true,
        'async'   => true,
      ],
      'div'    => [
        'id'   => true,
        'class'   => true,
        'data-id'   => true,
        'data-pos-x'   => true,
        'data-pos-y'   => true,
        'data-new'   => true,
        'data-intro-txt' => true,
        'data-speaker-img'   => true,
        'data-speaker-name'   => true,
        'data-referrer'   => true,
        'data-main-color'   => true,
        'data-delay'   => true,
        'data-height'   => true,
        'data-width'   => true,
        'data-template'   => true,
      ]
    ];
    echo wp_kses($output, $allowed_html);
  }

}