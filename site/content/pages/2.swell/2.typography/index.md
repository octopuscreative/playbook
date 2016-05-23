discipline:
  - 60dce3d4-8c49-49ec-a00a-2393e7e6aa24
content_builder:
  - 
    type: markdown
    md_content: |
      |
            Font sizes are based off of the modular scale. The scale is made up of two main variables: `$rem-base` and `$ratio`. Your `$rem-base` should be a pixel value equivalent to your main body font size. The default is 16px. Your `$ratio` determines how your font sizes will scale. A higher ratio will create larger differences between levels, while a smaller ratio will create smaller differences between levels.
      
      Using a scale helps to establish a hierarchy within the design and prevents us – the developers – from writing arbitrary values for font sizes of elements.
      
      Typography classes are prepended with `t` for type or typography followed by either `a` for alignment or `w` for weight.
      
      ### Modifiers
      
       `.t-` `0 - 12`
       
       `.tn-` `1 - 3`
      
      `.ta-` `left` `center` `right`
      
      `.tw-` `light` `book` `medium` `semibold` `bold`
      
      Additional text styles include
      `.display` (uses display font family and style), `.caps`, `.strikethrough`, `.underline`, `.italic`
title: Typography
fieldset: page
id: e745ad0b-106e-4136-959c-723e123a7b6c
