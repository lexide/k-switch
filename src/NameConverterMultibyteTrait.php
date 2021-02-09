<?php

namespace Lexide\KSwitch;

trait NameConverterMultibyteTrait
{
    /**
     * @var string
     */
    private $encoding = "UTF-8";

    /**
     * @param string $string
     * @return string
     */
    private function toStudlyCaps(string $string): string
    {
        return preg_replace_callback(
            "/([^\\p{L&}\\d]+|^)[\\p{L&}\\d]/u",
            function ($matches) {
                return mb_convert_case(
                    mb_substr($matches[0], -1, null, $this->encoding),
                    MB_CASE_UPPER_SIMPLE,
                    $this->encoding
                );
            },
            $string
        );
    }

    /**
     * @param string $string
     * @return string
     */
    private function toCamelCase(string $string): string
    {
        $studly = $this->toStudlyCaps($string);

        return mb_convert_case(mb_substr($studly, 0, 1, $this->encoding),
                MB_CASE_LOWER_SIMPLE,
                $this->encoding)
            . mb_substr($studly, 1, null, $this->encoding);
    }

    /**
     * @param string $string
     * @param string $separator
     * @return string
     */
    private function toSplitCase(string $string, string $separator = "_"): string
    {
        return mb_convert_case(
            preg_replace( // precede any capital letters or numbers with the separator (except when the character starts the string)
                "/(?<!^|" . preg_quote($separator) . ")(\\p{Lu}|\\d+)/u",
                addcslashes($separator, "$\\") . '$1',
                preg_replace( // replace any non-word characters with the separator (e.g. for converting dash case to snake case)
                    "/[^\\p{L&}\\d]/u",
                    $separator,
                    $string
                )
            ),
            MB_CASE_LOWER_SIMPLE,
            $this->encoding
        );
    }

    /**
     * @param array $data
     * @param string $case
     * @return array
     */
    private function convertArrayKeys(array $data, string $case): array
    {

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
    private function convertString(string $string, string $case): string
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
