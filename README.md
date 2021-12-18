# GoApptiv TextLocal Package Laravel

This package provides functionality to Send Single and Bulk Sms using TextLocal API and it stores the result in MySQL Database. It also supports multiple TextLocal Accounts.

## Installation

Add the following code in the composer to install this package into your Laravel Project

Add the package name in the composer require

```json
"goapptiv/textlocal": "1.0.0"
```

```json
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/GoApptiv/textlocal-package-laravel"
    }
]
```

Add the following keys in your environment variables.

```env
TEXTLOCAL_API=https://api.textlocal.in
```

Add your own Encryption Key and IV to encrypt the username, token and working key in the database

```env
TEXTLOCAL_CRYPTO_KEY=KEY
TEXTLOCAL_CRYPTO_IV=RANDOM_IV
```

Use the following commands to run the migrations in the SQL Database

```bash
php artisan vendor:publish --tag=textlocal-migrations
php aritsan migrate
```

## Configuration

Before you start using the package or placing orders, you need to configure your account in the database.

### Register Account

Use the following Php artisan command to register the account token in the textlocal_accounts table

```cmd
php artisan textlocal:add-account
```

### Change API Key of Registered account

Use the following Php artisan command to replace the API Key for the account.

```cmd
php artisan textlocal:replace-apikey
```

## Usage

### Send Single SMS

The Send Sms method will Send SMS to the requested mobile number. This method supports multiple mobile numbers for same message.

```php
use GoApptiv\TextLocal\Facades\TextLocal;


$message = 'SMS MESSAGE';
// Message should be registered in TextLocal and DLT

$mobileNumbers = collect([]);
$mobileNumbers->push(new TextLocalMessage("9999955555", 'UNIQUE_REFERENCE_ID');
$mobileNumbers->push(new TextLocalMessage("9999944444", 'UNIQUE_REFERENCE_ID_2');

$sms = new TextLocalSms($mobileNumbers, 'SENDER_NAME', $message);

TextLocal::sendSMS($sms, 1));
```

```php
// For Scheduling the message
TextLocal::sendSMS($sms, 1, now()->addMinutes(30));
```

#### TextLocalMessage Parameters

| Parameter     | Type   | Description                                     |
| ------------- | ------ | ----------------------------------------------- |
| $mobileNumber | string | Mobile Number                                   |
| $referenceId  | string | Reference Id for the Message to track in future |

#### TextLocalSms Parameters

| Parameter     | Type             | Description                                    |
| ------------- | ---------------- | ---------------------------------------------- |
| $mobileNumber | TextLocalMessage | List of mobile numbers along with reference id |
| $sender       | string           | Sender Id                                      |
| $message      | string           | Message registered in TextLocal and DLT        |

#### sendSMS Parameters

| Parameter          | Type         | Description                                           |
| ------------------ | ------------ | ----------------------------------------------------- |
| $sms               | TextLocalSms | SMS Object                                            |
| $accountId         | int          | Account Id registered in the textlocal_accounts table |
| $scheduledDateTime | Carbon       | Schedule date/time for your message                   |

### Send Bulk SMS

The Send Bulk Sms method will Send SMS to the requested mobile number. This method supports multiple mobile numbers and multiple messages.

```php
use GoApptiv\TextLocal\Facades\TextLocal;

$message1 = 'FIRST MESSAGE';
$message2 = 'SECOND MESSAGE';

$messages = collect([]);
$messages->push(new TextLocalMessage("9999955555", 'UNIQUE_REFERENCE_ID', $message));
$messages->push(new TextLocalMessage("9999944444", 'UNIQUE_REFERENCE_ID', $message2));

$bulk = new TextLocalBulkSms($messages, 'SENDER');

TextLocal::sendBulkSms($bulk, 1);
```

```php
// For Scheduling the message
TextLocal::sendBulkSms($bulk, 1, now()->addMinutes(30));
```

#### TextLocalMessage Parameters

| Parameter     | Type   | Description                                     |
| ------------- | ------ | ----------------------------------------------- |
| $mobileNumber | string | Mobile Number                                   |
| $referenceId  | string | Reference Id for the Message to track in future |
| $message      | string | Message registered in TextLocal and DLT         |

#### TextLocalBulkSms Parameters

| Parameter | Type             | Description                                             |
| --------- | ---------------- | ------------------------------------------------------- |
| $message  | TextLocalMessage | List of mobile numbers along with reference and message |
| $sender   | string           | Sender Id                                               |

#### sendBulkSms Parameters

| Parameter          | Type         | Description                                           |
| ------------------ | ------------ | ----------------------------------------------------- |
| $sms               | TextLocalSms | SMS Object                                            |
| $accountId         | int          | Account Id registered in the textlocal_accounts table |
| $scheduledDateTime | Carbon       | Schedule date/time for your message                   |
