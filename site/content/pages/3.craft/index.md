discipline:
  - 60dce3d4-8c49-49ec-a00a-2393e7e6aa24
contributors:
  - fcf94a4c-04ad-4243-b455-59dda1f2d016
punch_line: Sometimes, you do need a database
content_builder:
  - 
    type: markdown
    md_content: |
      ## Setup
      * http://www.dragonbe.com/2015/12/installing-php-7-with-xdebug-apache-and.html
      * https://getgrav.org/blog/mac-os-x-apache-setup-multiple-php-versions
      
      ## Twig Primer
      https://craftcms.com/docs/twig-primer
      
      ## Gotchas
      
      ### Mcrypt
      Make sure that you’ve installed both php and mcrypt from homebrew
      `brew install php55` `brew install php55-mcrypt`
      then type `brew info php55` and it will give you the following line to add to apache’s httpd.conf file:
      `LoadModule php5_module    /usr/local/opt/php55/libexec/apache2/libphp5.so`
      
      ### MySql > 5.7 (fixed in craft 3)
      * To find your config location, run `mysql --help`
      * http://craftcms.stackexchange.com/questions/12084/getting-this-sql-error-group-by-incompatible-with-sql-mode-only-full-group-by/12106
      * http://stackoverflow.com/questions/23921117/disable-only-full-group-by
      
      ### API/JSON
      * https://github.com/pixelandtonic/ElementAPI
      
      ## Assets
      https://craftcms.com/docs/assets
      When setting up a new asset source, the file system path input should point to a folder you create in `public`  (not in the craft folder), using a relative path. This folder also needs to be writable.
      
      ## Deploy
      * https://github.com/mattstauffer/syncCraft
      * https://gumroad.com/l/craft-setup
      
      ## Using mailcatcher with php
      
      https://serversforhackers.com/setting-up-mailcatcher
      Or what actually worked for me: Adding `sendmail_path = "/Users/seanwash/.rvm/gems/ruby-2.3.1/wrappers/catchmail"`  to the current php version’s php.ini file. If you used homebrew to install php, run `brew info php55` or `brew info php70` to see where the ini file is located.
      
      ## Random
      * Download a directory of images with wget `$ wget -r -nH -nd -np -R index.html* http://site.com/images/`  This could be useful if you have to pull down image assets for a site in progress
      * If you see `$_SERVER['SERVER_NAME']` , that’s coming from the `ServerName` setting in vhosts. Typically used for a multi env setup/config.
      
      ## Differences from Statamic
      * Instead of making separate fieldsets for each collection, taxnonomy, etc, in Craft you make all the different FIELDS by themselves and then you can drag and drop those fields to and field set on any channel, structure, or single.
      * Channel = Collection sorted by date
      * Structure = Collection sorted by number
      * Single = Page
      
      Collections → Sections
      
      Sections are an umbrella that holds different content types, mainly Channels (like blog or new, sorted by date), Structures (like projects, portfolio, products, sorted by numbers/drag drop), and Singles (like pages).
      
      ## Plugins
      
      If you haven’t already seen this: https://straightupcraft.com/craft-plugins
      
      *  Craft Kint (helpful debugger): https://github.com/mildlygeeky/craft_kint
      *  SEOmatic: https://github.com/nystudio107/seomatic
      *  SuperTable (craft doesn't super nested Matrix fields, this is essentially that): https://github.com/engram-design/SuperTable
      * Link It (way more helpful than I initially thought): https://github.com/fruitstudios/LinkIt
      * CP Field Links: https://github.com/mmikkel/CpFieldLinks-Craft
      * Pimp my Matrix (super helpful if you have huge matrix fields with a lot of block types): http://plugins.supercooldesign.co.uk/plugin/pimp-my-matrix
      * Code Block: https://github.com/lindseydiloreto/craft-codeblock
      * Field Manager: https://github.com/engram-design/FieldManager
      * Sprout Forms (paid, but awesome): http://sprout.barrelstrengthdesign.com/craft-plugins/forms
      * FeedMe: https://github.com/engram-design/FeedMe
      * Google Analytics https://dukt.net/craft/analytics
      * Sitemap Generator https://github.com/xodigital/SimpleSitemap
      
      Most of them by Pixel & Tonic, the company that develops Craft, are great. https://github.com/pixelandtonic
title: Craft
id: 214bef57-beb5-41d5-a098-19e7d1afd918
