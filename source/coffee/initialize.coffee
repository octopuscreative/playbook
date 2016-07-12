(($) ->
    
  $(document).on 'click', '#newPost', (e) ->

    e.preventDefault()

    window.open(
      $(this).attr('href'),
      'Add an Entry',
      'height=1000,
       width=1000,
       left=400,
       top=100'
    )

) jQuery