<?php

namespace Lexide\KSwitch;

trait NameConverterTrait
{
    /**
     * @param string $string
     * @return string
     */
    private function toStudlyCaps(string $string): string
    {
        // handle screaming snake case by converting to lowercase if there are no lowercase characters found
        if (strtoupper($string) == $string) {
            $string = strtolower($string);
        }

        return str_replace( // remove the spaces
            " ",
            "",
            ucwords( // uppercase the 1st letter of each word
                preg_replace( // replace non-alphanumeric characters with spaces
                    "/[^A-Za-z0-9]/",
                    " ",
                    $string
                )
            )
        );
    }

    /**
     * @param string $string
     * @return string
     */
    private function toCamelCase(string $string): string
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    /**
     * @param string $string
     * @param string $separator
     * @param bool $screaming
     * @return string
     */
    private function toSplitCase(string $string, string $separator = "_", bool $screaming = false): string
    {
        // handle screaming snake case by converting to lowercase if there are no lowercase characters found
        if (strtoupper($string) == $string) {
            $string = strtolower($string);
        }

        $converted = preg_replace( // precede any capital letters or numbers with the separator (except when the character starts the string)
            "/(?<!^|_)([A-Z]|\\d+)/",
            $separator . '$1',
            preg_replace( // replace any non-word characters with the separator (e.g. for converting dash case to snake case)
                "/[^A-Za-z0-9]/",
                $separator,
                $string
            )
        );
        return $screaming ? strtoupper($converted) : strtolower($converted);
    }

    /**
     * convert the keys of an array
     *
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
            case StringCases::SCREAMING_SNAKE_CASE:
                $string = $this->toSplitCase($string, screaming: true);
                break;
            case StringCases::DASH_CASE:
            case StringCases::HYPHEN_CASE:
                $string = $this->toSplitCase($string, "-");
                break;
            default:
                if (strlen($case) == 1) {
                    $string = $this->toSplitCase($string, $case);
                }
                break;
        }
        return $string;
    }
} 
