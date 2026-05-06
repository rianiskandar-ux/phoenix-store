# ChangeLog

[TOC]

Please read the [Changelog Guide](https://docs.gitlab.com/ee/development/changelog.html) to add any entry.

## Version 2.2.0 (2025-02/25)

### Added

* [Add Branch for PAOW / Phoenix All Over the World](!26)
* [Add Composer for package League/Config to handle multiple config #d997cfa6](d997cfa6e8311d8c9b2b9484713e1824d1355079)
* [Add USD currency in shortcode #3706fa25](3706fa259619b1ea642aaa73c3b8853631faa03d)
* [Add backend form to view/edit Settings of a tenant #62768773](62768773a600e5a0fa43e39170c58b696aa6130e)
* [Add configuration files #bf516ffa](bf516ffad4649e6ce362a9f6a6e1aa68b1594e8a)
* [Add composer with repo League/Config #d997cfa6](d997cfa6e8311d8c9b2b9484713e1824d1355079)

### Fixed

* [OP#2199 : Fix Gravity Error Handler for domain name #cf4fb675](cf4fb6750e306dbea2169ae5148533a018f6217d)
* [Fix issue OP#2149 - Buy extra Theme #569dc61a](569dc61a7996728070334be1c57486e5eb7f19e9)

### Changed

* [Change the IP server Indonesia and Singapore #6db98992](6db98992f36058f0e38a8f1145ee13b3e425fd3d)
* [Add FQDN and token in Config /App #71564e98](71564e98272f0d5bf197f9c42e74d09bdb0c6c1e)
* [Remove API calls for Infomaniak inboxes #28042371](28042371a7cde421c58a389834ac91a3624fc117)
* [Add tab for Devel and Logs #dd63dc2c](dd63dc2cef664ad2b3d1a4dcb3968964736abaea)

## Version 2.1.0 (2024-07-17)

### Added

* [Add WC Composite #0d439ece](!24)
* [Add shortcode for Composite translations [wbs_composite channel="xx" label="xx"] #5099a9b0](5099a9b00ddc116d158072eccf0eb9a6d4325838)
* [Add shortcode to get metadata from a WC_Object [wc_product meta=title|price|desc option=yearly-per-month|raw|debug] #a167a523](a167a523f223ea4d1def25ea79150cc2ce676149)

## Version 2.0.2 (2024-04-24)

### Added

* [Add IP Address in log Header #97596d01](97596d01e6ed660b19a0b00c38c2e0748364861d)
* [Add the Shortcode [product_price id="XX"] #c21a6445](c21a6445e86a50b1fc56adc164f381925c5a457e)
* [Add filter to execute shortcode in the plugin woocommerce_subscriptions_product_price_string #82846376](82846376567161317c84879e2848a0f5eb6ff968)
* Add more headers in Log
    - [Add HTTP referer #83e71ee0](83e71ee00d70726245b492f81334fd434026afa6)
    - [Add User Agent #0645e2cf](0645e2cff857886c5438fde6fcfb6749641796e2)
    - [Add Hostname #50006db9](50006db955105df3f7c3b3cd5d2f0e84667001a5)


### Fixed

* [Change the WP Session token value when user is Guest #abb8943f](abb8943f4fb0fd0ee928e64cde489a0dce725977)
* [Fix issue when the WBS options are reseted when plugin is deactivate -> reactivate #d2f51d10](d2f51d10b8acdd346d722abecc5c2625b34df8dd)
* [Fix issue with JS called with the wrong MIME type (#42 - closed)](#42)
* [Change the global constant WBSSAAS_WPML_DOMAIN to a var string in function __() (#43 - closed)](#43)
* [Add underscore as allowed characters for sub-domain #11acd26f](11acd26f3db9b794e46165d7e8679bd7888049fc)
* [Replace global constant WPML_DOMAIN by a var string. See #43 #57ad181f](57ad181f906c3c26385d86e6cbd96b10e41d40ff)

### Changed

* [Remove the JavaScript to handle the FQDN #2faf0d39](2faf0d3912b0ccb5434ec67d5c4d443b7c0e7d81)

## Version 2.0.1 (2023-12-01)

### Fixed

* [All Error displayed if GF is empty #40](#40)
* [Fix fatal error with __() function #a4a076d3](a4a076d3a146ae88dc67e743215029f30333534c)

### Added

* [Add WC_Subscription Renewal hook #41](#41)
* [Add Hook to populate dynamically list of clients for addons #27b26d61](27b26d61f6a1ede434a6b945572d285d5b8c5bd9)
* [Add hook when WC order is complete for Addon #954ebbff](954ebbff90db10e8715b76b35892f02018f12452) and [#8a1f4d35](8a1f4d3552dee468d088a15f47a71924d4164bfb)
* [Add hooks for themes and alert if tenant not migrated #23ad1511](23ad1511cdc80adf3a7a778d8278783607526644)

## Version 2.0.0 (2023-10-04)

### Changed

* New specs for branch 2.x

## Version 1.0.0 (2023-09-17)

### Added

* [Implement API Check Domain availability (#1 - closed)](#1)
* [Store and update clients (#2 - closed)](#2)
* [Plugins Form for CRUD client and settings (#3 - closed)](#3)
* [Edit Company info (#4 - closed)](#4)
* [Add settings for a client (#5 - closed)](#5)
* [Implement settings tenant to API #6 (closed)](#6)
* [Implement Settings for User to API #7 (closed)](#7)
* [Implement JSON Channels to API #8 (closed)](#8)
* [Split setting channels into GF entities #9 (closed)](#9)
* [Force 2FA in WP User settings #11 (closed)](#11)
* [Setting channels enable (boolean: true|false) #12 (closed)](#12)
* [Mailbox domain with WBS domain chosen by customer #17 (closed)](#17)
* [Forbidden subdomain #18 (closed)](#18)
* [Forbidden Email name #19 (closed)](#19)
* [Default Language on the bottom #22 (closed)](#22)

### Fixed

* [Error 400 Bad Request while editing Setting Channels #13 (closed)](#13)
* [Correct QR Code for Channel MobileApp #14 (closed)](#14)
* [Die if API is down or Success == false #15 (closed)](#15)
* [Format PostMail to international format #16 (closed)](#16)
* [Current Currency should be fixed #20 (closed)](#20)
* [Error while create new tenant without account #21 (closed)](#21)
* [When I create password for the account, please add an eye so I can see the password #25 (closed)](#25)

### Changed

* [Refactoring client settings in wp (!10)](!10)
* [Field Prefix and Suffix is not clear #23 (closed)](#23)
* [Remove “order notes” while checkout #24 (closed)](#24)

--- 

| Keyword     | Description             |
| :---------- |:----------------------- |
| added       | New feature             |
| fixed       | Bug fix                 |
| changed     | Feature change          |
| deprecated  | New deprecation         |
| removed     | Feature removal         |
| security    | Security fix            |
| performance | Performance improvement |
| other       | Other                   |
