discipline:
  - 60dce3d4-8c49-49ec-a00a-2393e7e6aa24
contributors:
  - fcf94a4c-04ad-4243-b455-59dda1f2d016
punch_line: "You've got then and we'll answer them"
content_builder:
  - 
    type: markdown
    md_content: |
      > Do we actually need any dynamic content features?
      
      Most likely, yes. If you plan on editing the content of your site in any way you will need dynamic content features. It saves you money because you don't have to pay us to do it and it saves us the project setup/teardown time
      
      > How will we host this site? How will we deploy it? How will we roll it back?
      
      This is another great thing about Statamic. It will run pretty much anywhere you have PHP and Apache or Nginx. This is a very common hosting environment. We recommed services like [A Small Orange](https://asmallorange.com/) if you just need something small and managed, or [DigitalOcean](https://www.digitalocean.com/) if you want more control of your environment while keeping the bill down
      
      > What are the scalability limits?
      
      There aren't any additional concerns that you wouldn't have with any other site! Better yet, since Statamic is a flat file system and doesn't need a database, I'd say it will scale better than other options since a whole step in reteiving data is eliminated. On top of that, Statamic has built in caching to make the site blazing fast and performant at the same time. If you'd like to take it one step farther, they even allow you to use a feature called HTML Page Caching. Basically, when you update content in the control panel, Satamic will actually create an html page out of it and server that. That way when a user hits your site the PHP processes aren't even hit. Basically, plain HTML is as performant as you can get.
      
      > Where are these files we upload using the asset manager hosted (I am assuming on the Statamic web server)?
      
      Yes, most of the time they will be stored on the same server that's hosting your site, however Statamic is able to upload those assets to Amazon S3 instead if you'd prefer to have your assets backed by a [CDN](https://en.wikipedia.org/wiki/Content_delivery_network) of sorts. This would probably be a good idea if you're planning to upload larger files (multiple hundreds of megabytes). Even better though, is that we can mix and match theses. Host things like images, team photos, etc on the same server and larger files on S3.
title: FAQ
id: f6534952-b0d5-4307-a337-6f1fd434f8f9
