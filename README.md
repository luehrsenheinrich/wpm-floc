# Luehrsen // Heinrich Plugin Boilerplate for WordPress

[![🏗 Build & Deploy](../../actions/workflows/main.yml/badge.svg)](../../actions/workflows/main.yml)

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

### Theme Slug & Names

- [ ] Search & Replace (case sensitive) `lhpbp` with your new WordPress plugin slug
- [ ] Search & Replace (case sensitive) `_LHPBP` with your new WordPress plugin slug in uppercase
- [ ] Check success in `package.json`
- [ ] Adjust `name` in `package.json`

### Running the enviroment

- [ ] Type `npm start` into the terminal to spin up the docker enviroment
- [ ] Open `http://localhost/wp-admin` and log in with `admin:password`

### Test Release

- [ ] Create a patch release with the github action
- [ ] Check if the release has been created and uploaded in the GitHub release section

### Finishing touches

- [ ] Edit the `plugin-README.md` with the appropriate text about your plugin
- [ ] Delete (or rename) the `README.md` (this file)
- [ ] Rename the `plugin-README.md` to `README.md`
- [ ] 🎉  Celebrate!
