<?php
namespace uk\benward;

class MissingConfigException extends \Exception {}
class InvalidMappingsException extends \Exception {}

# Shrtn is a tiny class that handles redirection, via a mappings Yaml file
class Shrtn {

  protected array $config;
  protected array $mappings = array();

  public function __construct(string $config) {
    if (!file_exists($config)) {
      throw new MissingConfigException("Cannot find $config");
    }
    $content = file_get_contents($config);
    $this->config = json_decode($content, true);

    # Look for, load, store YML mappings
    if (!isset($this->config['mappings'])) {
      throw new MissingConfigException('No mappings configured');
    }

    $this->loadMappings();
  }

  public function loadMappings() : array {
    $filename = $this->config['mappings'];

    if (!file_exists($filename)) {
      throw new InvalidMappingsException("FATAL: Cannot load mappings YAML: $filename");
    }

    # TODO: Store in an object cache?

    $content = file_get_contents($filename);
    $mappingConfig = yaml_parse($content);

    if (!isset($mappingConfig['shorturls'])) {
      throw new InvalidMappingsException("FATAL: No mappings in mappings YAML: $filename");
    }

    $mappings = array();
    foreach ($mappingConfig['shorturls'] as $shortUrl) {
      $mappings[$shortUrl['id']] = $shortUrl['url'];
    }

    return $this->mappings = $mappings;
  }

  public function fallbackUrl() : ?string {
    if (isset($this->config['failure_url'])) {
      return $this->config['failure_url'];
    } else {
      return null;
    }
  }

  public function getMappedUrl(string $shortcode) : ?string {
    if (isset($this->mappings[$shortcode])) {
      return $this->mappings[$shortcode];
    }
    return null;
  }

  public function handleRequest() {
    $path = substr($_SERVER['REQUEST_URI'], 1);
    list($possiblyShortcode) = preg_split('/[\/\?]/', $path, 2);

    $url = $this->getMappedUrl($possiblyShortcode);
    if ($url) {
      header('Location: ' . $url, true, 301);
    } elseif ($this->fallbackUrl()) {
      header('Location: ' . $this->fallbackUrl(), true, 302);
    } else {
      header("HTTP/1.0 404 Not Found");
    }
    exit();
  }
}
