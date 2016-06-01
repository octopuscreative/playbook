discipline:
  - 60dce3d4-8c49-49ec-a00a-2393e7e6aa24
contributors:
  - bbc7581c-fb25-441e-bbd9-c6cc2ece6fcf
  - fcf94a4c-04ad-4243-b455-59dda1f2d016
  - d4bc4529-83ce-43a4-861c-d5d60247445e
punch_line: Convention over confusion
content_builder:
  - 
    type: markdown
    md_content: |
      ## Why Rails?
      
      ## Common Problems
      ### XSRF Token Missing
      Requests to an API generally will not include the rails XSRF token in the header and so they will fail with a `401`.
      
      A posible solution would be to tell `ActionController` not to throw an exception when the header is missing:
      
      ```ruby
      class ApplicationController < ActionController::Base
      	protect_from_forgery with: :null_session
      end
      ```
      
      This will fix the problem but it makes the application vulerable to request forgery. Instead of using a chainsaw here is a scalpel:
      ```ruby
      class ApiController < ActionController::Base
      	skip_before_action :verify_authenticity_token
      end
      ```
      This is better because it will still require the token on all of the non-api controller actions.
      
      
      ### Allow-Access-Control-Origin
      
      <script src="https://gist.github.com/chrsgrffth/34ddbff061e134aec7e0b0393bddc0cc.js"></script>
      
      
      ## Recomended Tooling
      ### Authentication
      *Devise* - Platformatic
      
      Provides a battle tested auth DSL that covers all of the most common use cases and includes helpers, views and controllers to get things up and running fast.
      
      ### Authorization
      *Pundit* - ELABS
      
      By far the best authorization gem out there.
      
      ## Helpful Snippets
      
      ### Use VirtualHostX with Rails for Local .dev Domains and Subdomains
      
      <script src="https://gist.github.com/crtvhd/85e2673a77c1836aa9c5.js"></script>
title: Rails
id: a5120204-551d-42fe-a869-dba1dd8798e1
