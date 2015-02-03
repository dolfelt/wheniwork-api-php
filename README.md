When I Work API
=================

Very simple and easy When I Work API wrapper, for PHP.

Complex wrappers are for noobs. This lets you access the When I Work API using the docs as directly as possible.

Requires PHP 5.3 and a pulse.

Installation
------------

You can install the wheniwork-api using [Composer](https://getcomposer.org/). Just add the following to your `composer.json`:

    {
        "require": {
            "wheniwork/wheniwork-api": "dev-master"
        }
    }

You will then need to:
* run `composer install` to get these dependencies added to your vendor directory
* add the Composer autoloader to your application with this line: `require("vendor/autoload.php")`

Examples
--------

Login (requires Developer Key)

```php
$response = Wheniwork::login('developer_key', 'daniel@example.com', '******');
// TODO: Store the API token somewhere
$wiw = new Wheniwork($response->login->token);
```

List users (/users/ method)

```php
$wiw = new Wheniwork('api-token-here');
print_r($wiw->get('users'));
```

Create a new shift

```php
$wiw = new Wheniwork('api-token-here');
$result = $wiw->create('users', array(
                'email'             => 'nicole.jones@example.com',
                'first_name'        => 'Nicole',
                'last_name'         => 'Jones',
                'phone_number'      => '+16515559009'
            ));
print_r($result);
```
