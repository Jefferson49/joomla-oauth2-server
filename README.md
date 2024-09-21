[![Latest Release](https://img.shields.io/github/v/release/Jefferson49/joomla-oauth2-server?display_name=tag)](https://github.com/Jefferson49/joomla-oauth2-server/releases/latest)
[![Joomla major version](https://img.shields.io/badge/joomla-v5.x-green)](https://downloads.joomla.org/cms/joomla5)
[![Joomla major version](https://img.shields.io/badge/joomla-v4.x-green)](https://downloads.joomla.org/cms/joomla4)
[![Joomla major version](https://img.shields.io/badge/joomla-v3.x-green)](https://downloads.joomla.org/cms/joomla3)
## Joomla OAuth 2.0 Server 
A [Joomla](https://www.joomla.org/) extension, which provides an [OAuth 2.0](https://en.wikipedia.org/wiki/OAuth) server within the Joomla content management system, which enables [Single Sign On](https://en.wikipedia.org/wiki/Single_sign-on) (SSO) of other applications with the Joomla user account.

##  Installation
+ Install the extension in the Joomla administration backend
+ Open the backend menu: Components / OAuth 2.0 Server / Configure OAuth
+ Click on the button "Add client"
+ Enter the "Client Name" and the "Authorized Redirect URL" (from your OAuth 2.0 clients)
+ Check the "Client Name", "Client ID", and "Client Secret" in the list of OAuth clients
+ Configure the corresponding OAuth 2.0 clients accordingly

## Development and Contributions
+ Joomla OAuth 2.0 Server is a fork of the [OAuth Server for Joomla](https://extensions.joomla.org/extension/oauth-server-for-joomla/) extension (version 5.0.0), which was developed by [miniorange](https://www.miniorange.com/).
+ The fork is intended and tested to be used in a combination of a [Joomla](https://www.joomla.org/) content management system with the [webtrees] (https://www.webtrees.net/) genealogy application; especially for usage with the [webtrees-oauth2-client](https://github.com/Jefferson49/webtrees-oauth2-client) custom module.
+ Some limitations of the original extension were removed in the fork.

##  Versions 
+ Joomla: The latest version of the extension was tested with:
    + [Joomla 5.1.4](https://downloads.joomla.org/cms/joomla5)
    + [Joomla 4.4.8](https://downloads.joomla.org/cms/joomla4)
    + [Joomla 3.10.12](https://downloads.joomla.org/cms/joomla3)
    + The extension should also run with other Joomla 4.x or 5.x versions
+ PHP: The latest version of the extension was tested with:
    + PHP 8.3.6
    + PHP 8.2.21
    + The extension should also run with other PHP 8 versions.
    + PHP 7.x versions were not tested, but will probably work as well.

##  Updates
The extension supports the Joomla Update System. Available updates will be shown in the administration backend.

## Translation
You can help to translate this module. The translation files for the administrator backend can be found in the following path: [/com_oauth2server/admin/language](https://github.com/Jefferson49/joomla-oauth2-server/tree/main/com_oauth2server/admin/language).

You can use a text editor like notepad++ to work on translation *.ini files.

You can contribute translations via a pull request (if you know how to do), or by opening a new Github issue and attaching the file. Updated translations will be included in the next release of this module.

Currently, the following frontend languages are available:
+ English

## Issue reporting
If you experience any bugs [create a new issue](https://github.com/Jefferson49/joomla-oauth2-server/issues) in the Github repository

##  Github repository  
https://github.com/Jefferson49/joomla-oauth2-server
