discipline:
  - 60dce3d4-8c49-49ec-a00a-2393e7e6aa24
  - d9f5183f-b12a-4e3f-8928-c5fedabb03d4
contributors:
  - d4bc4529-83ce-43a4-861c-d5d60247445e
punch_line: Clients want it, we (kinda) hate it
content_builder:
  - 
    type: markdown
    md_content: |
      ## Set Up
      
      Scrollmagic needs the basic gsap files (animation, TweenMax, TimelineMax, etc) to work.
      
      ### Required Dependencies
      
      `jquery.min.js`
      
      `TweenMax.min.js`
      
      `Scrollmagic.min.js`
      
      `animation.gsap.js`
      
      
      ### Not Required but Useful Plugins
      
      `debug.addIndicators.js`
      
      `TimelineMax.min.js`
      
      `ScrollToPlugin.js`
      
      ## The Controller
      
      ```
      # Instantiate the scrollmagic controller.
      controller = new ScrollMagic.Controller()
      ```
      
      ## Tweens and Timelines
      
      ```
      fadeOut = TweenMax.to('#element', 1, {opacity: 0})
      fadeOut = TweenMax.to('#element', 1, {opacity: 0, delay: 0.5})
      fadeOut = TweenMax.staggerTo('#element', 1, {opacity: 0}, 0.1)
      fadeIn = TweenMax.fromTo('#element', 1, {opacity: 0}, {opacity: 1, delay: 0.5})
      fadeIn = TweenMax.staggerFromTo('#element', 1, {opacity: 0}, {opacity: 1}, 0.1)
      ```
      
      ```
      sceneTimeline = new TimelineMax()
      .add([
        TweenMax.to('#element', 1, {y: -50})
          TweenMax.to('#another', 1, {backgroundColor: '#000'})
      ])
      ```
      
      ## Scenes
      
      ```
      introScene = new ScrollMagic.Scene({
        triggerElement: '#scene',
        triggerHook: 0.75,
        duration: $('#scene').outerHeight()*2
      })
      .setTween(sceneTimeline)
      .addTo(controller)
      ```
title: Scrollmagic
id: 6d2581d4-c034-4fed-b8a7-c1204abce5c2