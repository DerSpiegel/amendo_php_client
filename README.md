# A PHP client for OneVision Amendo

[OneVision Amendo](https://www.onevision.com/solutions/image-editing/amendo/)
is automated image enhancement software.
This PHP client library uses its SOAP API.

This is not an official library supplied by the OneVision vendor.
It has been developed for the WoodWing Assets project at the German
[SPIEGEL Gruppe](https://www.spiegelgruppe.de), 2020.

## Installation

Use [Composer](https://getcomposer.org/) to add this library your project’s
composer.json file:

```
$ composer require der-spiegel/amendo-client
```

## Developing this library

If you want to help developing this library, Here’s how to get started
(Docker required):

### Install dependencies using Composer

```
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
  composer install
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
  composer require monolog/monolog
```

### Using the library

The `UsageExample.php` example script can be used without modification by
setting the environment variable `AMENDO_SERVER` to the Amendo server
base URL, `AMENDO_ASSEMBLYLINE` the name of the Amendo assembly line and
`AMENDO_FILEPATH` to a file path, that should be processed by the
assembly line.

The script will create a new job for the specified file using the specified
assembly line. Some custom job and file properties are submitted with the
job ticket to the Amendo server, that can be used to configure modules
used in the assembly line.

For more advanced usage create a copy of the
`UsageExample.php` example file

`$ cp UsageExample.php MyExample.php`

and modify the copied file accordingly.

#### Example source code explained

**Create an AmendoConfig object refering to the Amendo server:**

```php
$amendoConfig = new AmendoConfig($amendoServerUrl);
```

**Optional: Set desired SoapClient options. See
[PHP SoapClient](https://www.php.net/manual/en/class.soapclient.php)
documentation for details:**

```php
$amendoConfig->setSoapClientOption('connection_timeout', 5);
```

**Create an AmendoClient object using the AmendoConfig object:**

```php
$amendoClient = new AmendoClient($amendoConfig);
```

**Create a SimpleJobTicket object:**

```php
$jobTicket = new SimpleJobTicket();
```

**Optional: Set job name:**

If no job name is set, a generated job name is used.

```php
$jobTicket->setJobName('Test-' . time());
```

**Set assembly line to use:**

```php
$jobTicket->setAssemblyLineReference($assemblyLine);
```

**Optional: Set a job priority:**

If no priority is set, no priority is submitted to the Amendo server.

```php
$jobTicket->setJobPriority(60);
```

**Optional: Add job properties to the job:**

```php
$jobTicket->setStringProperty('Custom', 'AString', 'Text');
$jobTicket->setBooleanProperty('Custom', 'ABool', true);
$jobTicket->setIntegerProperty('Custom', 'AnInteger', 42);
$jobTicket->setFloatProperty('Custom', 'AFloat', 0.815);
```

The first argument of each method call specifies the list name to add the
property to. The second argument secifies the property name and the third
argument its value.

The first line adds a property of type string, the second line a property of
type boolean, the third line a property of type integer and the fourth lie
a property of type floating point to the job properties.

**Add one or more files to the job's run list:**

Use

```php
$file = $jobTicket->addFile($filePath);
```

for regular local files or

```php
$file = $jobTicket->addUri($filePath);
```

for file URI's or

```php
$file = $jobTicket->addDownloadUri($filePath);
```

for download URI's.

**Optional: Add file properties to the added file:**

```php
$file->setStringProperty('Custom', 'AString', 'FileText');
$file->setBooleanProperty('Custom', 'ABool', false);
$file->setIntegerProperty('Custom', 'AnInteger', 15);
$file->setFloatProperty('Custom', 'AFloat', 3.1415);
```

See job properties above for details.

**Submit the SimpleJobTicket to the Amendo server.**

```php
$jobId = $amendoClient->startJobTicket($jobTicket);
```

On success a positive `$jobId` is returned by the Amendo server.  
On error, either an exception is thrown or the Amendo server returns `0`.

**Optional: Querying the job status:**

```php
$status = $amendoClient->getStatus($jobId);
```

Returns a string containig the current job status. See Amendo documentation for
details.

**Optional: Querying the job result:**
```php
$result = $amendoClient->getResult($jobId);
```

Returns an object whose contents depends on the current status of the job.

### Then run your copy

```
$ docker run -it --rm --name amendo-client-example \
  --volume "$PWD":/usr/src/myapp --workdir /usr/src/myapp \
  php:7.4-cli php MyExample.php
```

## Authors

* [Tim Strehle](https://github.com/tistre) - https://twitter.com/tistre

## License

This library is licensed under the MIT License - see the `LICENSE` file for
details.
