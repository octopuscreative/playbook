contributors:
  - d4bc4529-83ce-43a4-861c-d5d60247445e
content_builder:
  - 
    type: markdown
    md_content: |
      ## Spin Up Server
      
      Forge is our preferred method for deploying Statamic sites.
      
      ## Create the Site
      
      Ensure that the license key is added to the Statamic system settings and that the target domain is listed next to the domain in the [Statamic Lodge](lodge.statamic.com).
      
      Add the SSH key created for the server by Forge to the deploy keys in the project Gitlab or Github settings. Note: if you plan to use Spock to commit changes, you'll have to add the key to your SSH Keys in your user settings because deployment keys are not allowed to push code.
      
      Add the SSH link for the repo to forge and ensure that `Install Composer Dependencies` is *unchecked*.
      
      
      ## Configure Deploy Script
      
      Edit the *Deployment Script* to specify the following.
      
      ```
      cd /home/forge/default
      git pull origin master
      chmod -R 777 assets local site statamic
      php please cache:clear
      php please search:update
      ```
      
      The changemod will ensure that we don't get permission errors on the backend if changes don't contain or override the necessary permissions. Statamic also recommends we clear the application cache and update the search.
      
      Deploy the server again to apply the changemod to the newly installed site.'
      
      ## Server Configuration
      
      Edit the *Environment File* to specify the enviroment – either `APP_ENV=staging` or `APP_ENV=production`.
      
      Edit the *Nginx Configuration*. Below the line specifying `charset = utf-8;`, replace with the following:
      
      ```
      location / {
          try_files $uri $uri/ /static/$uri /static/$uri/index.html /static/ $uri /index.php?$query_string;
      }
      
      location = /favicon.ico { access_log off; log_not_found off; }
      location = /robots.txt  { access_log off; log_not_found off; }
      
      access_log off;
      error_log  /var/log/nginx/example.com-error.log error;
      
      error_page 404 /index.php;
      
      location ~ \.php$ {
          fastcgi_split_path_info ^(.+\.php)(/.+)$;
          fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
          fastcgi_index index.php;
          include fastcgi_params;
      }
      
      location ~ /\.ht {
          deny all;
      }
      ```
      
      This updates the `location` rule with Statamic's and tells PHP to use `php7.0-fpm`. Without this, you'll get the `php5-fpm: Unrecognized service` error.
title: Deploying Statamic
id: 4a2ccd96-d903-44ee-9ffc-0b70e83e1e28
