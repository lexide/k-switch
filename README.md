# K-Switch
A PHP library to switch cases for property, field and element names

## Say what, now?
When dealing with different sources of data, it is often the case that field names will be in one case, typically 
"snake_case", and PHP classes will use "camelCase" for property names. Mapping between the two is non-trivial to do by 
hand.

K-Switch is a utility library to allow case switching with the minimum of fuss.

## Installation

Via composer OfC!

```
composer require lexide/k-switch
``` 

## How to use
Any class that needs to switch cases can use the `NameConverterTrait`. This trait adds private methods to convert
name strings into "StudlyCaps", "camelCase", "snake_case", "dash-case" and any other case that splits words with delimiters
(spaces, pipes, etc...)

```php

class Converter {
    use Lexide\KSwitch\NameConverterTrait;
    
    public function convertStudly($string)
    {
        return $this->toStudlyCaps($string);
    }
    
    public function convertCamel($string)
    {
        return $this->toCamelCase($string);
    }
    
    public function convertSnake($string)
    {
        return $this->toSplitCase($string);
    }
    
}



$converter = new Converter();

$converter->convertStudly("aCamelCaseName"); // ACamelCaseName
$studly = $converter->convertStudly("a_snake_case_name"); // returns "ASnakeCaseName"

$converter->convertSnake($studly); // back to "a_snake_case_name"

```

## ... and that's it?
Yup! Enjoy!
