discipline:
  - 60dce3d4-8c49-49ec-a00a-2393e7e6aa24
  - d9f5183f-b12a-4e3f-8928-c5fedabb03d4
contributors:
  - d4bc4529-83ce-43a4-861c-d5d60247445e
punch_line: Full page refreshes are so 2009.
content_builder:
  - 
    type: markdown
    md_content: |
      SmoothState is a really nifty and simple way to bring our sites into the 21st century by elminating full page refreshes and loading only the content we specify within the SmoothState container.
      
      ```
      # The the defined area that we want to AJAX-ify.
      $main = $('#main')
      
      
      (($) ->
      
      
        # Instantiate the smooth state.
        smoothState = $main.smoothState
          debug: true
          loadingClass: 'is-loading' # Do things with CSS while the page is transitioning.
          # anchors: 'a'
          # blacklist: 'form'
      
      
          onBefore: ($currentTarget) ->
            # We can clean up things like event bindings here if needed.
            # $currentTarget is jQuery object of the link that was clicked.
      
          onStart:
            duration: 1000 # Duration of the out animation.
      
            render: ->
              # Transition out the old content.
      
          onReady:
            duration: 1000 # Duration of the in animation.
            render: ($main, $newContent) ->
            
                # Load the new content to the container.
                $('#main').html($newContent)
      
                # Do the animation.
      
      
          onAfter: ->
            # Reinitialize any plugins or event bindings if needed.
      
      ) jQuery
      ```
title: SmoothState.js
id: eb59167d-b9c8-4f18-adfd-a90f2a903868
