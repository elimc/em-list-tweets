**EM List Tweets**
==================

**EM List Tweets** is a WordPress widget that is designed to work with the latest version of Twitter's API 1.1.

About
-----

**EM List Tweets** is a simple way to display Tweets on your WordPress Web site, using Twitter API 1.1. Although the displayed Tweets are wrapped in classes, these classes have no predefined styles attached to them. This widget is an excellent way to work on a Twitter feed for client stes without having to register the clients for JetPack, and its Twitter Feed. Of course, with this widget, you might find that you do not need to download JetPack.

While you don't need to register for JetPack, you will need to create a Twitter Developer Account. This is how things work with Twitter API 1.1, and there is no way around this. Once you have a free Twitter Developer Account, you will be able to receive the oAuth tokens necessary to receive your Tweets. Check out the Installation section in this document to see how to get this working.

Most of the fields on the backend are self-explanatory, except for the the Refresh Tweets field. This field determines how often the widget will check the Twitter feed for new tweets. A lower number means the feed will refresh with new tweets faster. A higher number means that the feed will display on your site faster, becase it won't have to make as many requests to Twitter. The default value is `12`, which means the feed will update twice a day.

__NOTE__ Tweets that are sent to someone, i.e., tweets that have an @username in them, will not be displayed with this widget. Perhaps they will in the future, if there are requests for that feature.

Screenshots
-----------

![displayes tweets example](https://raw.github.com/elimc/em-list-tweets/master/images/front_end.PNG "Displayed Tweets")

FAQ
---

__Why did a lot of the Twitter widgets for WordPress recently break?__

Twitter is now using oAuth to validate users. You can no longer grab a simple JSON stream from any user and display its contents.

__Why did they do that?__

I can only assume that they have an evil corporate agenda . . . [real reason](https://dev.twitter.com/docs/application-permission-model)

__What's wrong with JetPack?__

It doesn't let me do things on my development servers without signing up for WordPress.com

__Can I fork this, improve it, and send it back to you?__

YES! The more eyeballs that are refactoring my code to make it more secure, terse, and speedy, the better.

__Should I use safety goggles with this widget?__

It's up to you. So far, there have been no industrial accidents involving more than 27 people with this widget.

__Can I request feture updates for this Twitter?__

Sure. I am attempting to keep this widget as simple as possible, but if the change will increase utility more than complexity, I would consider updating the code. Alternatively, you can fork it and submit a pull request.

Requirements
------------

WordPress 3.3+

Installation
------------

The first thing to do is to download the widget and get it installed in your WordPress theme.

1) Download this widget as a .zip.
2) Upload .zip file to your plugins directory. You can find the file uploader under `Plugins => Add New` in the WordPress sidebar menu.
3) Click on the `Plugin` link in the WordPress sidebar. Find `EM List Tweets` in the list of plugins and click the activate link. Now your widget is activated!

Now that your plugin in activated, you will have to set up a Twitter Developer Acount and generate some oAuth keys.

1) Sign up for a free ([developer account](https://dev.twitter.com/apps)) on Twitter.
2) Create a free app with your account. This is necessary to authenticate your to Twitter.

![create twitter app](https://raw.github.com/elimc/em-list-tweets/master/images/create_app.png "create twitter app")

3) Create oAuth access tokens. This will give you a consumer key, consumer secret, access token, and access token secret. Copy these, you will need them later!

![create oAuth access tokens](https://raw.github.com/elimc/em-list-tweets/master/images/create_access_token.png "create oAuth access tokens")

4) Change your requests from `read` to `read and write`. First you will select the `Settings` tab.

![settings tab](https://raw.github.com/elimc/em-list-tweets/master/images/change_access_levels.png "settings tab")

5) Change your application type to `Read and Write`.

![read and write seetings](https://raw.github.com/elimc/em-list-tweets/master/images/read_and_write.png "read and write seetings")

6) Put the oAuth keys into the widget in the widget admin section, along with Twitter username.

![widget options](https://raw.github.com/elimc/em-list-tweets/master/images/widget_screen.PNG "Widget options")

Changelog
---------

### 0.9.1 (August 29, 2013)
* Updated documentation.
* Code refactoring

### 0.9 (August 27, 2013)
* Plugin fully functional, but still in beta testing phase.

### 0.1 (August 23, 2013)
* Initial commit.

Author
------

Eli McMakin

Web site: www.elimcmakin.com

GitHub: https://github.com/elimc

**EM List Tweets** Repository: https://github.com/elimc/em-list-tweets