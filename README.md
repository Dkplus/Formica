# Formica

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b052c240-e02d-4413-94be-535e09de0e3c/mini.png)](https://insight.sensiolabs.com/projects/b052c240-e02d-4413-94be-535e09de0e3c)
[![Build Status](https://travis-ci.org/Dkplus/Formica.svg?branch=master)](https://travis-ci.org/Dkplus/Formica)

Formica allows to easily use the builder pattern in your projects e.g. to setup your test data.

## Installation

Formica can be easily installed by running `composer require dkplus/formica`.

## Quick start

Lets assume that you have a class `Acme\Issue` for which you want to have a builder for.
It has some properties and can be constructed through a named constructor.

```php
namespace Acme;

class Issue
{
    // …
    
    public static function bug(string $id, string $title, string $text): self
    {
        // …    
    }
    
    // …
}
```

For a builder you need another class `Acme\IssueBuilder` which extends the base builder:

```php
namespace Acme;

use Dkplus\Formica\Builder;

/**
 * @method IssueBuilder withTitle(string $title)
 * @method IssueBuilder withText(string $text)
 */
class IssueBuilder extends Builder
{
    public static function aBug()
    {
        return new self([
            '74738ff5-5367-5958-9aee-98fffdcd1876',
            'title' => 'It does not work',
            'text' => 'A long error text',
        ], 'bug');
    }
}
```

You can use the builder like this:

```php
$issue = IssueBuilder::aBug()->withTitle('Another title')->build();
```

## Explanations

The `Dkplus\Formica\Builder` expects three arguments:

 - The default arguments that should be passed to the constructing method.
 - The name of the static method that should be used to construct the object.
   If `null` is given it will use the constructor of the object.
 - The class of the object. If `null` is given it will try to guess the class
   by using the class of the builder and stripping the last 7 characters.
   So if you name your builders `<NameOfYourClass>Builder` you don't need to
   pass the class.

While the constructor of `Dkplus\Formica\Builder` is public, I advise to create
a static method that calls it and sets the default values.
 
All arguments that have been given with a key can be overriden by one of
`withX()` or `andX()` methods (both providing a fluent interface).

If you want some autocompletion I suggest to put some annotations on top of
your builder (see the example above).
