# Playbook by Octopus

This is our internal resource for all things development.



## Built with Craft and Brunch

To get started:

### Contact [Chris](mailto:chris@octop.us) for the Craft config files.

This includes the database configuration. The database is configured on a remote server. When the repository is pulled and virtualhost is set up, the site will _just work&trade;_. To work on the source files (coffee, scss, etc.),


### Use Brunch for Task Automation

* Install (if you don't have them):
    * [Node.js](http://nodejs.org): `brew install node` on OS X
    * [Brunch](http://brunch.io): `npm install -g brunch`
    * Brunch plugins and app dependencies: `npm install`
* Run:
    * `brunch watch --server` — watches the project with continuous rebuild. This will also launch HTTP server with [pushState](https://developer.mozilla.org/en-US/docs/Web/Guide/API/DOM/Manipulating_the_browser_history).
    * `brunch build --production` — builds minified project for production
