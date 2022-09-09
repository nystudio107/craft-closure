[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nystudio107/craft-closure/badges/quality-score.png?b=v1)](https://scrutinizer-ci.com/g/nystudio107/craft-closure/?branch=develop) [![Code Coverage](https://scrutinizer-ci.com/g/nystudio107/craft-closure/badges/coverage.png?b=v1)](https://scrutinizer-ci.com/g/nystudio107/craft-closure/?branch=develop) [![Build Status](https://scrutinizer-ci.com/g/nystudio107/craft-closure/badges/build.png?b=v1)](https://scrutinizer-ci.com/g/nystudio107/craft-closure/build-status/develop) [![Code Intelligence Status](https://scrutinizer-ci.com/g/nystudio107/craft-closure/badges/code-intelligence.svg?b=v1)](https://scrutinizer-ci.com/code-intelligence)

# Closure for Craft CMS

Allows you to use arrow function closures in Twig

While Closure is a bit of a monkey patch, it's a pretty clean/simple one that relies on functionality that is already built into Twig

## Requirements

Closure requires Craft CMS 4.0.0 or later

## Installation

To install Closure, follow these steps:

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to require the package:

        composer require nystudio107/craft-closure

## About Closure

Twig supports arrow function closures, but only in the [filter](https://twig.symfony.com/doc/3.x/filters/filter.html), [map](https://twig.symfony.com/doc/3.x/filters/map.html), and [reduce](https://twig.symfony.com/doc/3.x/filters/reduce.html) filters.

Twig unfortunately [has no plans](https://github.com/twigphp/Twig/issues/3402) to allow for more widespread usage of arrow function closures.

Craft Closure allows you to use arrow function closures anywhere, which is especially useful with [Laravel Collection methods](https://laravel.com/docs/9.x/collections#available-methods), many of which take a closure as a parameter.

## Using Closure

Once you've added the `nystudio107/craft-closure` package to your project, no further setup is needed. This is because it operates as an auto-bootstrapping Yii2 Module.

You can then pass an [arrow function](https://timkelty.github.io/twig-tips/10-arrow-fn.html) closure as a parameter to anything that accepts them, such as many [Laravel Collection methods](https://laravel.com/docs/9.x/collections#available-methods):

```twig
    {% set collection = collect(['a', 'b', 'c']) %}
    {% set contains = collection.contains((value, key) => value == 'z') %}
```

Or you can assign an arrow function closure to a Twig variable for re-use:

```twig
    {% set collection = collect(['a', 'b', 'c']) %}
    {% set closure = (value, key) => value == 'a' %}
    {% set contains = collection.contains(closure) %}
```

Using arrow function closures especially useful now that Craft element queries can all return a Collection via the [.collect()](https://docs.craftcms.com/api/v4/craft-db-query.html#method-collect) method.

## More on Arrow Functions in Twig

More here: [Twig Arrow Functions](https://craftquest.io/courses/arrow-functions-in-twig)

## Closure Roadmap

Some things to do, and ideas for potential features:

* Initial release

Brought to you by [nystudio107](https://nystudio107.com/)
