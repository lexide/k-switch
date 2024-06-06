<?php

namespace Lexide\KSwitch\Test;

use Lexide\KSwitch\StringCases;
use PHPUnit\Framework\TestCase;

/**
 * NameConverterTraitTest
 */
class NameConverterTraitTest extends TestCase
{

    /**
     * @var NameConverterImplementation
     */
    protected $model;

    public function setup(): void
    {
        $this->model = new NameConverterImplementation();
    }

    /**
     * @dataProvider studlyCapsProvider
     *
     * @param string $value
     * @param string $expected
     */
    public function testToStudlyCaps(string $value, string $expected)
    {
        $this->assertEquals($expected, $this->model->toStudlyCaps($value));
    }

    /**
     * @dataProvider splitCaseProvider
     *
     * @param string $value
     * @param string $expected
     * @param string $separator
     * @param bool $screaming
     */
    public function testToSplitCase(string $value, string $expected, string $separator = "_", bool $screaming = false)
    {
        $this->assertEquals($expected, $this->model->toSplitCase($value, $separator, $screaming));
    }

    /**
     * @dataProvider arrayKeyProvider
     *
     * @param array $data
     * @param string $case
     * @param array $expected
     */
    public function testConvertingArrayKeys(array $data, string $case, array $expected)
    {
        $this->assertEqualsCanonicalizing($expected, $this->model->convertArrayKeys($data, $case));
    }

    public function studlyCapsProvider(): array
    {
        return [
            "Simple, from lowercase" => [
                "single",
                "Single"
            ],
            "Snake case" => [
                "double_trouble",
                "DoubleTrouble"
            ],
            "Screaming snake case" => [
                "THE_SNAKES_ARE_SCREAMING",
                "TheSnakesAreScreaming"
            ],
            "Already in StudlyCaps" => [
                "StudlyCaps",
                "StudlyCaps"
            ],
            "Word split" => [
                "with spaces",
                "WithSpaces"
            ],
            "Split by random characters" => [
                "with-other~characters",
                "WithOtherCharacters"
            ],
            "Collapsing spaces" => [
                "collapsing    separators",
                "CollapsingSeparators"
            ],
            "Including numbers" => [
                "inc1uding_numb3rs_4_gigglez",
                "Inc1udingNumb3rs4Gigglez"
            ]
        ];
    }

    public function splitCaseProvider(): array
    {
        return [
            "Simple" => [
                "oneword",
                "oneword"
            ],
            "Camel case" => [
                "twoWords",
                "two_words"
            ],
            "Studly caps" => [
                "StudlyCaps",
                "studly_caps"
            ],
            "Already in snake case" => [
                "already_in_snake_case",
                "already_in_snake_case"
            ],
            "Screaming!" => [
                "ALLCAPS",
                "a_l_l_c_a_p_s"
            ],
            "Camel case with numbers" => [
                "W1thNumb3rs",
                "w_1th_numb_3rs"
            ],
            "Studly caps with numbers" => [
                "All4One",
                "all_4_one"
            ],
            "Convert to words" => [
                "SpaceOutTheWords",
                "space out the words",
                " "
            ],
            "Convert to alternate snake case" => [
                "WithSomeOtherCharacter",
                "with~some~other~character",
                "~"
            ],
            "Snake case with numbers" => [
                "with_numbers_4nd_underscores",
                "with_numbers_4nd_underscores"
            ],
            "Screaming snake case" => [
                "TheSnakesAreScreaming",
                "THE_SNAKES_ARE_SCREAMING",
                "_",
                true
            ]
        ];
    }

    public function arrayKeyProvider(): \Generator
    {
        $camel = [
            "propOne" => "value1",
            "aLongishPropName" => "value2",
            "shorty" => "value3"
        ];
        $studly = [
            "PropOne" => "value1",
            "ALongishPropName" => "value2",
            "Shorty" => "value3"
        ];
        $snake = [
            "prop_one" => "value1",
            "a_longish_prop_name" => "value2",
            "shorty" => "value3"
        ];
        $dash = [
            "prop-one" => "value1",
            "a-longish-prop-name" => "value2",
            "shorty" => "value3"
        ];
        $screaming = [
            "PROP_ONE" => "value1",
            "A_LONGISH_PROP_NAME" => "value2",
            "SHORTY" => "value3"
        ];

        $toTest = [
            StringCases::CAMEL_CASE => $camel,
            StringCases::STUDLY_CAPS => $studly,
            StringCases::SNAKE_CASE => $snake,
            StringCases::DASH_CASE => $dash,
            StringCases::SCREAMING_SNAKE_CASE => $screaming
        ];

        foreach ($toTest as $input) {
            foreach ($toTest as $case => $output) {
                yield [$input, $case, $output];
            }
        }
    }

}
