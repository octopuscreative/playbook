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
      
      Tweens define a single group of animations for one or more elements. Meaning you can tell one or a group of elements to animate a number of properties, but you can't tell different elements to do their own different animations with Tweens.
      
      ```
      fadeOut = TweenMax.to('#element', 1, {opacity: 0})
      fadeOut = TweenMax.to('#element', 1, {opacity: 0, delay: 0.5})
      fadeOut = TweenMax.staggerTo('#element', 1, {opacity: 0}, 0.1)
      fadeIn = TweenMax.fromTo('#element', 1, {opacity: 0}, {opacity: 1, delay: 0.5})
      fadeIn = TweenMax.staggerFromTo('#element', 1, {opacity: 0}, {opacity: 1}, 0.1)
      ```
      
      This is where timelines come in. With timelines you specify the tweens and then tie them together for use with a scene.
      
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
title: ScrollMagic
id: 6d2581d4-c034-4fed-b8a7-c1204abce5c2
