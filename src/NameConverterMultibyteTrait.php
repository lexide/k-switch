<?php

namespace Lexide\KSwitch;

trait NameConverterMultibyteTrait
{

    private $encoding = "UTF-8";

    /**
     * @param string $string
     * @return string
     */
    private function toStudlyCaps($string)
    {
        return preg_replace_callback(
            "/([^\\p{L&}\\d]+|^)[\\p{L&}\\d]/u",
            function ($matches) {
                return mb_strtoupper(mb_substr($matches[0], -1, null, $this->encoding), $this->encoding);
            },
            $string
        );
    }

    private function toCamelCase($string)
    {
        $studly = $this->toStudlyCaps($string);

        return mb_strtolower(mb_substr($studly, 0, 1, $this->encoding), $this->encoding) . mb_substr($studly, 1, null, $this->encoding);
    }

    /**
     * @param string $string
     * @param string $separator
     * @return string
     */
    private function toSplitCase($string, $separator = "_")
    {
        return mb_strtolower(
            preg_replace( // precede any capital letters or numbers with the separator (except when the character starts the string)
                "/(?<!^|" . preg_quote($separator) . ")(\\p{Lu}|\\d+)/u",
                addcslashes($separator, "$\\") . '$1',
                preg_replace( // replace any non-word characters with the separator (e.g. for converting dash case to snake case)
                    "/[^\\p{L&}\\d]/u",
                    $separator,
                    $string
                )
            ),
            $this->encoding
        );
    }

    /**
     * @param array $data
     * @param string $case
     * @return array
     */
    private function convertArrayKeys(array $data, $case) {

        foreach ($data as $property => $value) {
            $originalProperty = $property;
            $property = $this->convertString($property, $case);
            if ($property != $originalProperty) {
                unset($data[$originalProperty]);
                $data[$property] = $value;
            }
        }

        return $data;
    }

    /**
     * @param string $string
     * @param string $case
     * @return string
     */
    private function convertString($string, $case)
    {
        switch ($case) {
            case StringCases::STUDLY_CAPS:
                $string = $this->toStudlyCaps($string);
                break;
            case StringCases::CAMEL_CASE:
                $string = $this->toCamelCase($string);
                break;
            case StringCases::SNAKE_CASE:
            case StringCases::UNDERSCORES:
                $string = $this->toSplitCase($string);
                break;
            case StringCases::DASH_CASE:
            case StringCases::HYPHEN_CASE:
                $string = $this->toSplitCase($string, "-");
                break;
            default:
                if (mb_strlen($case) == 1) {
                    $string = $this->toSplitCase($string, $case);
                }
                break;
        }
        return $string;
    }
} 
