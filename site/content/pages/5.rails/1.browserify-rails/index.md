discipline:
  - 60dce3d4-8c49-49ec-a00a-2393e7e6aa24
contributors:
  - fcf94a4c-04ad-4243-b455-59dda1f2d016
punch_line: >
  While some people complain that the asset pipeline is "the worst"™ we find that browserify + rails
  relieves much of the headache
content_builder:
  - 
    type: markdown
    md_content: |
      A quick solution to problems that the asset pipeline introduces, like JavaScript load order and excessive globals, is to use the [browserify-rails](https://github.com/browserify-rails/browserify-rails) gem. This gem doesn't replace the asset pipeline, but rather it builds upon it. With a couple of simple config udates you'll have the power of browserify and all of its transforms at your fingertips.
      
      ### Quick Links
      
      * [browserify-rails Getting Started](https://github.com/browserify-rails/browserify-rails#getting-started)
title: Browserify + Rails
id: d45ffcc6-01c0-4cad-8bfa-ed2ed76940c1
