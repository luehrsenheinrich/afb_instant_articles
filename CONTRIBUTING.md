# Contributing to the Agency Theme

Hi! Thank you for your interest in contributing to the theme, we really appreciate it.

There are many ways to contribute – reporting bugs, feature suggestions, fixing bugs, submitting pull requests for enhancements.

## Reporting Bugs, Asking Questions, Sending Suggestions

Just [file a GitHub issue](https://github.com/luehrsenheinrich/agncy/issues/new), that’s all. If you want to prefix the title with a “Question:”, “Bug:”, or the general area of the application, that would be helpful, but by no means mandatory. If you have write access, add the appropriate labels.

If you’re filing a bug, specific steps to reproduce are helpful. Please include the URL of the page that has the bug, along with what you expected to see and what happened instead.

Here is a [handy link for submitting a new bug](https://github.com/luehrsenheinrich/agncy/issues/new?body=URL%3A%0A%0AWhat+I+expected%3A%0A%0ASteps+to+reproduce%3A%0A%0AWhat+happened+instead%3A&title=Description%20of%20the%20problem).

## Helping with the documentation

Every theme is just as good as the documentation. In this repository we offer [collaboration with a wiki](https://github.com/luehrsenheinrich/agncy/wiki) to create a documentation for this theme.

## Setting up the dev enviroment

If you want to contribute code to the theme you have to set up the environment locally. Make sure that you have `npm` and `grunt` installed.

The working directory is the `build` directory. If you change something in another location of the git the pull request or commit will be ignored.

The development server and all dependencies are handled by docker and npm. Make sure you have [docker-compose installed](https://docs.docker.com/compose/install/) and run `npm run setup` in the directory. You spawned WordPress instance will be available under `http://localhost` with the account `wordpress:wordpress`.

Please be aware, that you should usually not write code directly on the master branch.

Start the grunt watcher with the terminal command `grunt watch`. Grunt will make sure that the code will be compiled and copied to the trunk folder.

Before committing execute the command `grunt deploy` to perform a clean deploy from the build to the trunk folder.

*IMPORTANT*: Edits outside the `build` directory will be overwritten by the grunt tasks. So make sure you don't work within the `trunk` folder.

## Development workflow

To keep the work in this repository structured and visible, we follow a certain way to add changes and code. A good workflow is structured like this: 

1. Write or take an issue about the problem you want to solve
2. Add your own branch to the repository
3. As soon as you have a presentable solution, add a pull request to the master branch
4. Get feedback about your implementation
5. Before merging the PR to the master branch, update your branch from master to resolve conflicts
6. Merge the PR into the master branch, test the solution and delete your branch

### Naming branches

Ideally name your branches with prefixes and descriptions, like this: [type]/[change]. A good prefix would be:

* add/ = add a new feature
* try/ = experimental feature, "tentatively add"
* update/ = update an existing feature

For example, add/gallery-block means you're working on adding a new gallery block.
