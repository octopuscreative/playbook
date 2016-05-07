---
punch_line: " Don't miss another <em>swell</em> or powder day because of CSS inheritance issues and start pushing out websites like a hen on Easter. It's so easy, a designer could do it."
content_builder:
  - 
    type: markdown
    content: |
      Swell is a barebones utility CSS framework that is agnostic of design, allowing you – the developer – to build websites and application interfaces efficiently from project to project.
      
      ## Why utility classes?
      
       "But that's how we've always done it." Even between us at Octopus, we were hesistant to adopt something so different from how websites and apps have been styles since the beginning of CSS; but, after one too many projects with CSS inhertiance issues and overwriting styles throughout a deep nesting of SASS files, we decided to give it a go.
      
       Like getting on a bike for the first time, it felt weird to see so much going on in the markup; but, we realized two  things:
      
      1. Views and functionality were being built incredibly quickly.
      2. We could do much more than we thought with simple styles.
      3. We could see exactly what styles were being applied to all the elements without having to dig through inspector.
      
      
      ## Getting Started
      
      There are two ways of getting started using Swell:
      
      
      ### SCSS
      
      Using Swell in it's SCSS form, you'll be able to:
      
      1. control which components are imported into your css stylesheet.
      2. change global settings within your project.
      3. extend utility classes to custom stylesheets within your project.
      
      You'll need to copy  the `scss` folder within `src` to a logical place in your app. You'll need `init.scss` (which you can rename to `{your-app-name}.scss` to import the components and settings and `settings.scss` to load the default settings.
      
      ```
      init.scss
      settings.scss
      util/
      ```
      
      ### Vanilla CSS
      
      To get started using the classes without dealing with any task automation, you can download and include a compiled version of the Swell stylesheet. Copy `styles.css` from the `dist` folder to a logical place in your project and include it in your HTML.
title: Swell
id: 43ce1d49-0a58-4437-a32c-7b6236d43639
---
Don't miss another swell because of CSS inheritance issues and start pushing out websites like a hen on Easter.