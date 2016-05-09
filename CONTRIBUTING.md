# Contributing to allfacebook Instant Articles WordPress Plugin

Hi! Thank you for your interest in contributing to the plugin, we really appreciate it.

There are many ways to contribute – reporting bugs, feature suggestions, fixing bugs, submitting pull requests for enhancements.

## Reporting Bugs, Asking Questions, Sending Suggestions

Just [file a GitHub issue](https://github.com/luehrsenheinrich/afb_instant_articles/issues/new), that’s all. If you want to prefix the title with a “Question:”, “Bug:”, or the general area of the application, that would be helpful, but by no means mandatory. If you have write access, add the appropriate labels.

If you’re filing a bug, specific steps to reproduce are helpful. Please include the URL of the page that has the bug, along with what you expected to see and what happened instead.

Here is a [handy link for submitting a new bug](https://github.com/luehrsenheinrich/afb_instant_articles/issues/new?body=URL%3A%0A%0AWhat+I+expected%3A%0A%0ASteps+to+reproduce%3A%0A%0AWhat+happened+instead%3A&title=Description%20of%20the%20problem).

## Helping with the documentation

Every plugin is just as good as the documentation. In this repository we offer [collaboration with a wiki](https://github.com/luehrsenheinrich/afb_instant_articles/wiki) to create a documentation for this plugin. 

## Setting up the dev enviroment

If you want to contribute code to the plugin you have to set up the environment locally. Make sure that you have `npm` and `grunt` installed.

The working directory is the `build` directory. If you change shomething in another location of the git the pull request or commit will be ignored.

To test the plugin make a [symbolic link](https://en.wikipedia.org/wiki/Symbolic_link) between the `trunk` folder and the `wp-content/plugins/afb-instant-articles` folder on your local WordPress instance.

Start the grunt watcher with the terminal command `grunt watch`. Grunt will make sure that the code will be compiled and copied to the trunk folder.

Before committing execute the command `grunt deploy` to perform a clean deploy from the build to the trunk folder.
