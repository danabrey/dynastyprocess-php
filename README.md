# dynastyprocess-php

Provides a PHP API for accessing player valuations from [DynastyProcess.com](https://dynastyprocess.com/).

![Build](https://github.com/danabrey/dynastyprocess-php/workflows/PHP%20Composer/badge.svg)

## Installation

Via Composer:

`composer require danabrey/dynastyprocess-php`

## Usage

```
use DanAbrey\DynastyProcess\Client;

$client = new Client();
$values = $client->getValues();
```

`$values` is an array of simple `DynastyProcessPlayerValue` objects.
