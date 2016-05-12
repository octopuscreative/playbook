contributors:
  - fcf94a4c-04ad-4243-b455-59dda1f2d016
  - c7782849-8a3f-4979-a8db-c1831b8962f2
punch_line: Because you get what you pay for
content_builder:
  - 
    type: markdown
    md_content: |
      ## Content Managment
      
      A lot of people point out that there are _tons_ of plugins out there for WordPress, so much in fact that you can do anything with it. The reality is though that out of the box, WordPress comes with a very limited set of capabilities and the vast majority of what a client needs from us wouldn't be possible without using plugins. This is an issue because when you start to use plugins, you then depened on whoever is maintaining said plugins for sercurity updates, bugfixes, or even just good documentation. There's also the classic problem of "Installing plugin B breaks plugin A that I installed yesterday". Good luck fixing that!
      
      Aside from the above out of the box you get two content types (blog and page), and between them there's one textarea for adding content. Typically in our projects clients expect to be able to edit most content on a page, for example hero text or client testimonials. This isn't possible without adding at least two plugins, and even after that you get a cluttered mess of a UI to work with, and you can't even check those customizations into source control.
      
      ## Performance
      
      So now that we've talked about how much work it takes to even give clients a (usable) UI to update their site, we should talk about performance. For every WordPress plugin that's added to the mix, there are all the more php processes that need to run and sql queries that need to be processed. If you find yourself saying something like "So what? Computers are fast!", then you need to look a little deeper. [This company](http://www.yottaa.com/company/blog/application-optimization/benchmarking-performance-of-8-cms-platforms-who-is-slowest/) benchmarked 15,000 sites and compared the results across the different CMS's that they used and WordPress was very consistently the slowest performer. What makes things even worse is that these tests were run on desktop machines - can you imagine what performance would be like for the growing number of mobile users out there? In an age where your users expect things to be fast and won't wait more than a few seconds for your site to load, it's a pretty big deal.
      
      ##  Security
      
      Every CMS and framework suffers the occasional security flaw. However, WordPress has a “richer” history of security bugs and choices in the system that lead to vulnerabilities, including various [exploits](http://readwrite.com/2011/01/13/the-hidden-dangers-of-free-wor/) and [vulnerabilities](http://markmaunder.com/2011/08/01/zero-day-vulnerability-in-many-wordpress-themes/) in downloadable themes and plugins. For a comprehensive list of WordPress exploits, [check this out](http://www.wordpressexploit.com/).
      
      ## The Bottom Line
      
      Really, the bottom line is that there are people out there that can do cool things with WordPress, but that's not where we excell. We're good at building things from scratch quickly and not repurposing/bending a setup to do something it wasn't meant to do from its conception. The reason why things like [Statamic 😍](https://statamic.com/) and [Craft  👀](https://craftcms.com/) work so much better is because from the start they dont make any assumptions about the content you'll be working with - they get out of the way and let you build what the client needs.
title: Why not WordPress?
id: 6669ca9f-c060-4f32-a29f-bf9e97d21ba6