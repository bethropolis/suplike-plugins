### How to make a plugin

 1. create a new folder, inside the folder make a `plugin.php` file and `plugin.json` file.
 
 2. use the following schema for the `plugin.json` 
 ```js
 {
"id":  "", // plugin identification string (unique)
"name":  "",
"version":  "1.0",
"description":  "",
"author":  {
"name":  "Bethropolis",
"url":  ""
},
"github":  "", // repo url
"install_url": "" // link to public archive file
}
 ```

3. the `plugin.php` structure should be
```php

<?php
namespace  Bethropolis\PluginSystem\{folder-name}Plugin;

use Bethropolis\PluginSystem\Plugin;

class  Load  extends  Plugin
{

 public function initialize()
 {
  $this->linkHook('my_hook', array($this, 'myCallback'));
  // Link plugin callback to app hooks and events
 }
    
 public function myCallback($name = [])
 {
  $name = array_shift($name);
  return "hello {$name}";
 }
}

// this is just a template, you can modify it to do so much more


```

4. create an archive of your plugin (mostly .zip is preferred ) and upload it to a github/gitlab release or any other service. don't forget to include it to your `install_url` in your `plugin.json` file.

5. submit an issue or pull request to add your plugin to the list for public use.
