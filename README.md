[![Latest Release](https://img.shields.io/github/v/release/Jefferson49/Joomla-OAuth2-server?display_name=tag)](https://github.com/Jefferson49/Joomla-OAuth2-server/releases/latest)
[![Joomla major version](https://img.shields.io/badge/joomla-v4.x-green)](https://downloads.joomla.org/cms/joomla4)
[![Joomla major version](https://img.shields.io/badge/joomla-v5.x-green)](https://downloads.joomla.org/cms/joomla5)
## Joomla OAuth2 Server 
A [Joomla](https://www.joomla.org/) extension, which provides an [OAuth 2.0](https://en.wikipedia.org/wiki/OAuth) server within the Joomla content management system, which enables [Single Sign On](https://en.wikipedia.org/wiki/Single_sign-on) (SSO) of other applications with the Joomla user account.

##  Installation
+ Install the extension in the Joomla backend
+ Open the backend menu: Components / OAuth2 Server / Configure OAuth
+ Client on the button to add an OAuth client
+ Enter the "Client Name" and the "Authorized Redirect URL"
+ Check the "Client Name", "Client ID", and "Client Secret" in the list of OAuth clients
+ Configure the corresponding OAuth 2.0 clients accordingly

## Development and Contributions
+ Joomla OAuth2 Server is a fork of the [OAuth Server for Joomla](https://extensions.joomla.org/extension/oauth-server-for-joomla/) extension (version 3.0), which was developed by [miniorange](https://www.miniorange.com/).
+ The fork is intended and tested to be used in a combination of [Joomla](https://www.joomla.org/) with [webtrees](https://www.webtrees.net/); especially with the [webtrees-OAuth2-client](https://github.com/Jefferson49/webtrees-OAuth2-client).
+ Some limitations of the forked extension were removed.

##  Versions 
+ The latest extension version was developed and tested with: 
    + [Joomla 4.4.8](https://downloads.joomla.org/cms/joomla4)
    + [Joomla 5.1.4](https://downloads.joomla.org/cms/joomla5)
    + However, the extension should also run with other Joomla 4.x or 5.x versions
+ PHP 8.0.23 as well as PHP 8.1.13; but should also run with other PHP 8 versions. 7.x versions have not been tested, but might also be feasible.

## Translation
You can help to translate this module:
+ Administrator backtend translations: [/com_oauth2server/admin/language](https://github.com/Jefferson49/Joomla-OAuth2-server/tree/main/com_oauth2server/admin/language)

You can use a text editor like notepad++ to work on translations.

You can contribute translations via a pull request (if you know how to do), or by opening a new Github issue and attaching the file. Updated translations will be included in the next release of this module.

Currently, the following frontend languages are available:
+ English

## Issue reporting
If you experience any bugs [create a new issue](https://github.com/Jefferson49/Joomla-OAuth2-server/issues) in the Github repository

##  Github repository  
https://github.com/Jefferson49/Joomla-OAuth2-server
