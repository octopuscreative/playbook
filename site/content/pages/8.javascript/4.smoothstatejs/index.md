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
      ```
      
      # The the defined area that we want to AJAX-ify.
      $main = $('#main')
      
      
      (($) ->
        module.init()
      
      
        # Handle internal page transitions with smoothstate.
        smoothState = $main.smoothState
          debug: true
          loadingClass: 'is-loading' # Show loader when is loading.
          # anchors: 'a'
          # blacklist: 'form'
      
      
          onBefore: ($currentTarget) ->
            # $currentTarget is the link that was clicked.
            #
            # Clean up any event bindings here.
      
          onStart:
            duration: # Duration of the out animation.
      
            render: ->
              # Transition out the old content.
      
          onReady:
            duration: # Duration of the in animation.
            render: ($main, $newContent) ->
                # Load the new content to the container.
                $('#main').html($newContent)
      
                # Do the animation.
      
      
          onAfter: ->
            # Reinitialize plugins.
      
      ) jQuery
      ```
title: SmoothState.js
id: eb59167d-b9c8-4f18-adfd-a90f2a903868
