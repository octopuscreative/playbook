discipline:
  - 60dce3d4-8c49-49ec-a00a-2393e7e6aa24
content_builder:
  - 
    type: markdown
    md_content: |
      |
      ## GZIP
      
      ```
      gzip on;
      gzip_disable "msie6";
      
      gzip_vary on;
      gzip_proxied any;
      gzip_comp_level 6;
      gzip_buffers 16 8k;
      gzip_http_version 1.1;
      gzip_types
          application/atom+xml
          application/javascript
          application/json
          application/rss+xml
          application/vnd.ms-fontobject
          application/x-font-ttf
          application/x-web-app-manifest+json
          application/xhtml+xml
          application/xml
          font/opentype
          image/svg+xml
          image/x-icon
          text/css
          text/plain
          text/x-component;
      ```
      
      [Source](https://mattstauffer.co/blog/enabling-gzip-on-nginx-servers-including-laravel-forge)
      
        ## Browser Caching
        ```
        # cache.appcache, your document html and data
        location ~* \.(?:manifest|appcache|html?|xml|json)$ {
          expires -1;
          # access_log logs/static.log; # I don't usually include a static log
        }
      
        # Feed
        location ~* \.(?:rss|atom)$ {
          expires 1h;
          add_header Cache-Control "public";
        }
      
        # Media: images, icons, video, audio, HTC
        location ~* \.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc)$ {
          expires 1M;
          access_log off;
          add_header Cache-Control "public";
        }
      
        # CSS and Javascript
        location ~* \.(?:css|js)$ {
          expires 1y;
          access_log off;
          add_header Cache-Control "public";
        }
        ```
      
        [Source](https://serversforhackers.com/nginx-caching)
      
      
      ## Imgix FTW
      
      ```
      <img src="http://domain.imgix.net{{ url }}?dpr=1" srcset="http://domain.imgix.net{{ url }}?dpr=1 1x, http://domain.imgix.net{{ url }}?dpr=2 2x" alt="{{ alt }}" />
      ```
title: Performance
id: 191ab9b6-24b6-48ec-a33d-75aee787b04c
