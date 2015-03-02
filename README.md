[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/inspector-php/inspector/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/inspector-php/inspector/?branch=master)
# Inspector

Inspect your app's data, config and user input for issues.

## Use-cases

* Test customer databases for configuration issues
* Test customer databases for inconsistencies / invalid data
* Validate user input (find invalid email addresses, phonenrs, etc)
* ... etc etc

The framework lets you write 'Inspections' for any type of data, and raise 'Issues' when needed.

The issues contain (translatable) user-level error messages, proposed solutions, and hyperlinks to fix the issue.

## Installation
It is recommended to use composer to install Inspector:
```
composer require inspector/inspector "~1.0"
```
Alternatively you can add the following to the require section in your composer.json manually:
```
"inspector/inspector": "~1.0"
```
Run composer update afterwards.

## How to use Inspector

### Create an Inspection class

You can create a new Inspection class for each type of data that you want to inspect. For example a `UserInspector`

```php
namespace Acme\Inspector\Inspection;

use Inspector\Inspection\InspectionInterface
use Acme\Inspector\Issue\UserWithoutPasswordIssue;
use PDO;

// As a convention, end the classname with "Inspection"
class UserInspection
{
    private $pdo;
    
    // Request dependencies from the container
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    // The inspector will run all methods starting with `inspect`
    public function inspectPasswords(InspectionInterface $inspection)
    {
        // Query for users without a password
        $res = $pdo->queryForUsersWithoutPasswords();
        
        // Loop through the results
        foreach ($res as $row) {
            // Instantiate a new issue for each user without a password
            $issue = new UserWithoutPasswordIssue($inspection);
            
            // Attach arbitrary data to the issue for later usage in the templates
            $issue->setData('username', $row['username']);
            $issue->setData('userid', $row['id']);
            
            // Add this new issue to the inspection
            $inspection->addIssue($issue);
        }
    }
}
```

### Create an Issue class

Each issue is described in it's own class, in this case `UserWithoutPasswordIssue`:

```php

namespace Acme\Inspector\Issue;

use Inspector\Issue\IssueInterface;
use Inspector\Issue\BaseIssue

// An issue must implement the IssueInterface
// To simplify this, simply extend from the BaseIssue
class UserWithoutPasswordIssue extends BaseIssue implements IssueInterface
{
    // Optionally provide links to where to solve the issue
    public function getLinks()
    {   
        $links = array();
        $links[] = new Link('/users/' . $this->getData('userid') . '/resetpassword', 'Reset password');
        return $links;
    }
}
```

### Run inspections


```php
use Inspector\Inspector;
use Inspector\Loader\YamlLoader;
use Inspector\Formatter\ConsoleFormatter;

// Create a new container, or reuse an existing one (Symfony DI, Pimple, etc)
$container = array(); 
$container['pdo'] = $myPdoHandle;

// Instantiate a new Inspector
$inspector = new Inspector($container);

// Use a YamlLoader to quickly import a suite of Inspections
$loader = new YamlLoader();
$loader->load($inspector, '/my/inspector/suite.yml');

// Run all the inspections
$inspector->run();

// Create a new formatter to display the output
$formatter = new ConsoleFormatter();

// Echo out the formatted inspector results
echo $formatter->format($inspector);
```

### Creating a inspection suite file

You can define a set of inspections in a YAML file for quick loading and running.
The YAML file supports includes, and lets you specify classnames that contain `inspect` methods:

```yaml
include:
    - other.yml
    
classes:
    - Acme\Inspection\UserInspection
```

### Running inspections from the console

A console command is included, you can run it like this:

```bash
vendor/bin/inspector inspector:run path/to/my/suite.yml
```

This runs the inspection suite, and outputs the results to the console.

### Running inspections on SQL databases

If your inspections need an SQL database connection, you can use the `--pdoconfig=/path/to/config.ini` option.
The ini file should contain the `server`, `name`, `username` and `password` for your database.
You can define which `driver` to use, which will default to `mysql` if omitted.

For example:

```mydatabase.ini
name=mydatabase
server=localhost
username=joe
password=secret
driver=pgsql
```

## License

MIT (see [LICENSE.md](LICENSE.md))

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
