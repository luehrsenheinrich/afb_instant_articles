# Luehrsen // Heinrich Plugin Boilerplate for WordPress

[![Build Status](https://travis-ci.com/luehrsenheinrich/wp-plugin-boilerplate.svg?branch=master)](https://travis-ci.com/luehrsenheinrich/wp-plugin-boilerplate)

There are probably more plugin boilerplates than actual plugins available for
bootstrapping your work on an amazing WordPress plugin. And that is very much
okay, because every developer, every agency has their own little flavors in how
they like to do things.

That is the reason we made this plugin boilerplate. We liked the work of so many
other developers before us, but we never found the perfect boilerplate that fit
our style of work. The result is this, a very opinionated plugin boilerplate
based on docker, grunt and less-css.

This boilerplate will give you all the tools you need to write, test and publish
your plugin, either for commercial clients or to publish the plugin in the
WordPress.org repository.


## Getting started

These steps will guide you through the setup process up until you can start
writing functions, markup and styles for your plugin.

For the sake of scope we will assume that you know the slug of your plugin.
Please make sure that the slug is unique to the system of the client, our
projects and the WordPress.org plugin repository.

We will also assume, that you have already configured your GitHub repository to
your liking, downloaded the source of the boilerplate and uploaded it to your
new repository. So let's get started:

### Plugin Slug & Names

- [ ] Rename the `build/allfacebook-instant-articles.php` file
- [ ] Search & Replace (case sensitive) `allfacebook-instant-articles` with your new WordPress plugin slug
- [ ] Search & Replace (case sensitive) `allfacebook-instant-articles` with your new WordPress plugin slug
- [ ] Search & Replace (case sensitive) `AFBIA` with your new WordPress plugin slug in uppercase
- [ ] Check success in `package.json`, `docker-compose.json` & `bin/install-wordpress.sh`

### Running the enviroment

- [ ] Type `npm run setup` into the terminal to spin up the docker enviroment
- [ ] Open `http://localhost/wp-admin` and log in with `wordpress:wordpress`
- [ ] Make sure the plugin unit demo content is installed and the plugin is active

### Test Release

- [ ] Add a 0.0.2 release by running `npm run release` in your terminal
- [ ] Check if the release has been created and uploaded in the GitHub release section

### Finishing touches

- [ ] Edit the `README.md` with the appropriate text about your plugin
- [ ] 🎉  Celebrate!
