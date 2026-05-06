# WBS SaaS WordPress 2.x

[![pipeline status](https://gitlab.integrity-asia.com/canary/wbs-saas-wp/badges/production/pipeline.svg)](https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/commits/production) [![coverage report](https://gitlab.integrity-asia.com/canary/wbs-saas-wp/badges/production/coverage.svg)](https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/commits/production) [![Latest Release](https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/badges/release.svg)](https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/releases)

[TOC]

## Bug Tracker

Service Desk: <bug+canary-wbs-saas-wp-53-issue-@gitlab.integrity-asia.com>

## Technical Notes

### MySQL table

The table's structure is defined in `~/includes/db.php`

| Name                 | Type         | Null | Default                      |
| :------------------  | :----------- | :--- | :--------------------------- |
| id                   | mediumint(9) | No   | Auto increment               |
| customer_id          | mediumint(9) | No   | Foreign Key from WooCustomer |
| tenant_uuid          | varchar(36)  | Yes  | ''                           |
| tenant_name          | text         | No   | NULL                         |
| tenant_url           | varchar(255) | No   | ''                           |
| tenant_settings      | longtext     | Yes  | NULL                         |
| subscription_wc_id   | mediumint(9) | Yes  | NULL                         |
| subscription_expired | datetime     | Yes  | 0000-00-00 00:00:00          |
| created              | datetime     | No   | 0000-00-00 00:00:00          |
| modified             | datetime     | No   | 0000-00-00 00:00:00          |

### JSON sent to the API Kodok

#### Create new client

* Method: `POST`
* Endpoint: `{{API_URL}}/v1/clients/new`
* Payload:

```json
{
    "company_name": "Acme Corporation",
    "company_url": "https:\/\/acme.speak-up.link",
    "admin_email": "ngeorget@mailo.com",
    "default_lang": "en",
    "package": {
        "webform": [
            "short",
            "medium",
            "long"
        ],
        "phone": 1,
        "email": 1,
        "im": 1,
        "postmail": 1,
        "chat": 0,
        "mobileapp": 0,
        "languages": 1,
        "users": {
            "manager": 1,
            "operator": 1,
            "agent": 1
        },
        "themes": [
            "phoenix_1"
        ]
    },
    "created": "2023-09-20 04:06:39",
    "modified": "2023-09-20 04:06:39",
    "expired": "2023-10-20 04:05:55"
}
```

#### Launch the Basic Setup Wizard

* Method: `POST`
* Endpoint: `{{COMPANY_URL}}/clients/new?u={{uuid}}&m={{emailEncrypted}}`
* Parameters:
    - `{{uuid}}` = string Tenant UUID
    - `{{emailEncrypted}}` = string Email encrypted using PHP [password_hash](https://www.php.net/manual/en/function.password-hash.php) and PASSWORD_DEFAULT algorithms

#### Redo Basic Setup Wizard

* Method: `POST`
* Endpoint: `{{COMPANY_URL}}/login?sw=1`
* Parameters:
    - `{{sw}}` = int 1|0 or true|false

#### Update Package

* Method: `PUT`
* Endpoint: `{{API_URL}}/v1/package-slots`
* Payload:

```json
{
  "client_uuid": "{{UUID}}",
  "package": "phone", //package list
  "slot": 3
}
```

* Package List (hardcoded in `~/includes/hhok.wc.php`):
    * ~~webform~~ (see [Update Webform](#update-webform))
    * phone
    * email
    * im
    * postmail
    * chat
    * mobileapp
    * languages
    * user_manager
    * user_operator
    * user_agent

#### Update Theme

* Method: `PUT`
* Endpoint: `{{API_URL}}/v1/settings/template`
* Payload:

```json
{
  "client_uuid": "{{UUID}}",
  "data": [
      "premium_1", 
      "phoenix_9"
  ]
}
```

#### Update Webform

* Method: `PUT`
* Endpoint: `{{API_URL}}/v1/settings/package`
* Payload:

```json
{
  "client_uuid": "{{UUID}}",
  "data": [
      "short", 
      "medium",
      "long"
  ]
}
```

* Data: `short|medium|long|custom`

## Notes

