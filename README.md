Gitlab CI SDK
=======

[![Latest Stable Version](https://poser.pugx.org/neilime/php-gitlab-ci-sdk/v/stable.png)](https://packagist.org/packages/neilime/php-gitlab-ci-sdk)
[![Total Downloads](https://poser.pugx.org/neilime/php-gitlab-ci-sdk/downloads.png)](https://packagist.org/packages/neilime/php-gitlab-ci-sdk)

NOTE : If you want to contribute don't hesitate, I'll review any PR.

Introduction
------------

Gitlab CI SDK is a PHP Wrapper for use with the [Gitlab CI API](https://github.com/gitlabhq/gitlab-ci/blob/master/doc/api/api.md).
==============

Based on [php-gitlab-api](https://github.com/m4tthumphrey/php-gitlab-api).

Requirements
------------

* [Buzz](https://github.com/kriswallsmith/Buzz) (>=0.7).

Installation
------------

### Main Setup

#### By cloning project

1. Install the [Buzz](https://github.com/kriswallsmith/Buzz) (>=0.7) by cloning them into `./vendor/`.
2. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "neilime/php-gitlab-ci-sdk": "1.0.0"
    }
    ```

2. Now tell composer to download AssetsBundle by running the command:

    ```bash
    $ php composer.phar update
    ```

General API Usage
-----------------

```php
$oClient = new \GitlabCI\Client('http://ci.example.com/api/v1/'); // change here
$oClient->authenticate('your_gitlab_ci_token_here','http://demo.gitlab.com', \GitlabCI\Client::AUTH_URL_TOKEN); // change here

$oProject = $oClient->api('projects')->create('My Project', array(
  'gitlab_id' => 2,
));

```

Model Usage
-----------

You can also use the library in an object oriented manner.

```php
$oClient = new \GitlabCI\Client('http://ci.example.com/api/v1/'); // change here
$oClient->authenticate('your_gitlab_ci_token_here','http://demo.gitlab.com', \GitlabCI\Client::AUTH_URL_TOKEN); // change here
```

Creating a new project

```php
$oProject = \GitlabCI\Model\Project::create($oClient, 'My Project', array(
  'gitlab_id' => 2,
));