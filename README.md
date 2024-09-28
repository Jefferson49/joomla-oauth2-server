[![Latest Release](https://img.shields.io/github/v/release/Jefferson49/joomla-oauth2-server?display_name=tag)](https://github.com/Jefferson49/joomla-oauth2-server/releases/latest)
[![Joomla major version](https://img.shields.io/badge/joomla-v5.x-green)](https://downloads.joomla.org/cms/joomla5)
[![Joomla major version](https://img.shields.io/badge/joomla-v4.x-green)](https://downloads.joomla.org/cms/joomla4)
[![Joomla major version](https://img.shields.io/badge/joomla-v3.x-green)](https://downloads.joomla.org/cms/joomla3)
## Joomla OAuth 2.0 Server 
A [Joomla](https://www.joomla.org/) extension, which provides an [OAuth 2.0](https://en.wikipedia.org/wiki/OAuth) server within the Joomla content management system, with the intension to enable [Single Sign On](https://en.wikipedia.org/wiki/Single_sign-on) (SSO) of other applications with the Joomla user account.

##  Table of Contents
This README file contains the following main sections:
+   [IMPORTANT SECURITY NOTES](#important-security-notes)
+   [Installation](#installation)
+   [Versions](#versions)
+   [Updates](#updates)
+   [Translation](#translation)
+   [Issue reporting](#issue-reporting)
+   [Development and Contributions](#development-and-contributions)
+   [License](#license)
+   [Github Repository](#github-repository)

## IMPORTANT SECURITY NOTES
It is **highly recommended to use** the **HTTPS** protocol for your Joomla installation. The [HTTPS](https://en.wikipedia.org/wiki/HTTPS) protocol will ensure the encryption of the communication between OAuth clients and the Joomla authorization provider. This will allow secure exchange of secret IDs and secret access tokens.

##  Installation
+ Install the extension in the Joomla administration backend
+ Open the backend menu: Components / OAuth 2.0 Server / Configure OAuth
+ Click on the button "Add client"
+ Enter the "Client Name" and the "Authorized Redirect URL" (from your OAuth 2.0 client)
+ Check the "Client Name", "Client ID", and "Client Secret" in the list of OAuth clients
+ Configure the corresponding OAuth 2.0 clients accordingly

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
You can help to translate this module. The translation files for the administrator backend can be found in the following path: [/joomla-oauth2-server/com_oauth2server/admin/language](joomla-oauth2-server/com_oauth2server/admin/language/).

You can use a text editor like notepad++ to work on translation *.ini files.

You can contribute translations via a pull request (if you know how to do), or by opening a new Github issue and attaching the file. Updated translations will be included in the next release of this module.

Currently, the following frontend languages are available:
+ English

## Issue Reporting
If you experience any bugs [create a new issue](https://github.com/Jefferson49/joomla-oauth2-server/issues) in the Github repository

## Development and Contributions
+ Joomla OAuth 2.0 Server is a fork of the [OAuth Server for Joomla](https://extensions.joomla.org/extension/oauth-server-for-joomla/) extension (version 5.0.0), which was developed by [miniOrange](https://www.miniorange.com/).
+ The fork is intended and tested to be used in a combination of a [Joomla](https://www.joomla.org/) content management system with the [webtrees](https://www.webtrees.net/) genealogy application; especially for usage with the [webtrees-oauth2-client](https://github.com/Jefferson49/webtrees-oauth2-client) custom module.
+ Some limitations of the original extension were removed in the fork.

## License
+ [GNU General Public License, Version 3](LICENSE.md)
+ Joomla
    + Copyright (c) 2005-2024 [Open Source Matters, Inc.](https://www.opensourcematters.org)
+ OAuth Server for Joomla (Joomla extension)
    + Copyright (c) 2016 [miniOrange](https://www.miniorange.com). All Rights Reserved.
+ Joomla OAuth 2.0 Server (webtrees custom module)
    + Copyright (c) 2024 [Jefferson49](https://github.com/Jefferson49)

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see https://www.gnu.org/licenses/.

##  Github Repository  
https://github.com/Jefferson49/joomla-oauth2-server
