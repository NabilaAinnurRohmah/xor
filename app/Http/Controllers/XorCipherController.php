<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XorCipherController extends Controller
{
    private $ascii = [

        'A' => 65, 'B' => 66, 'C' => 67, 'D' => 68, 'E' => 69,
        'F' => 70, 'G' => 71, 'H' => 72, 'I' => 73, 'J' => 74,
        'K' => 75, 'L' => 76, 'M' => 77, 'N' => 78, 'O' => 79,
        'P' => 80, 'Q' => 81, 'R' => 82, 'S' => 83, 'T' => 84,
        'U' => 85, 'V' => 86, 'W' => 87, 'X' => 88, 'Y' => 89,
        'Z' => 90,

        'a' => 97, 'b' => 98, 'c' => 99, 'd' => 100, 'e' => 101,
        'f' => 102, 'g' => 103, 'h' => 104, 'i' => 105, 'j' => 106,
        'k' => 107, 'l' => 108, 'm' => 109, 'n' => 110, 'o' => 111,
        'p' => 112, 'q' => 113, 'r' => 114, 's' => 115, 't' => 116,
        'u' => 117, 'v' => 118, 'w' => 119, 'x' => 120, 'y' => 121,
        'z' => 122,

        '0' => 48, '1' => 49, '2' => 50, '3' => 51, '4' => 52,
        '5' => 53, '6' => 54, '7' => 55, '8' => 56, '9' => 57,

        ' ' => 32,
        '.' => 46,
        ',' => 44,
        '-' => 45,
        '_' => 95,
        '/' => 47,
        '@' => 64,
        '!' => 33,
        '#' => 35,
        ':' => 58,
        ';' => 59,
        '?' => 63,
        '=' => 61,
        '+' => 43,
        '*' => 42,
        '%' => 37,
        '$' => 36,
        '&' => 38,
        '(' => 40,
        ')' => 41,
        '[' => 91,
        ']' => 93,
        '{' => 123,
        '}' => 125,
        '<' => 60,
        '>' => 62,
        '"' => 34,
        "'" => 39,
        '\\' => 92,
        '|' => 124,
        '^' => 94,
        '~' => 126,
        '`' => 96,
    ];

    private function charToAscii($char)
    {
        return $this->ascii[$char] ?? 0;
    }

    private function asciiToChar($ascii)
    {
        foreach ($this->ascii as $char => $code) {

            if ($code == $ascii) {
                return $char;
            }
        }

        return '-';
    }

    private function decimalToBinary($number)
    {
        $binary = '';

        while ($number > 0) {

            $binary = ($number % 2).$binary;

            $number = (int) ($number / 2);
        }

        while (strlen($binary) < 8) {
            $binary = '0'.$binary;
        }

        return $binary;
    }

    private function binaryToDecimal($binary)
    {
        $decimal = 0;

        $power = 0;

        for ($i = strlen($binary) - 1; $i >= 0; $i--) {

            if ($binary[$i] == '1') {

                $decimal += 2 ** $power;
            }

            $power++;
        }

        return $decimal;
    }

    private function textToBinary($text)
    {
        $result = [];

        for ($i = 0; $i < strlen($text); $i++) {

            $ascii = $this->charToAscii(
                $text[$i]
            );

            $binary = $this->decimalToBinary(
                $ascii
            );

            $result[] = $binary;
        }

        return $result;
    }

    private function xorBinary($bin1, $bin2)
    {
        $hasil = '';

        for ($i = 0; $i < 8; $i++) {

            if ($bin1[$i] == $bin2[$i]) {
                $hasil .= '0';
            } else {
                $hasil .= '1';
            }
        }

        return $hasil;
    }

    private function isBinaryInput($text)
    {
        for ($i = 0; $i < strlen($text); $i++) {

            if (
                $text[$i] != '0' &&
                $text[$i] != '1' &&
                $text[$i] != ' '
            ) {
                return false;
            }
        }

        return true;
    }

    public function index()
    {
        return view('xor');
    }

    public function process(Request $request)
    {
        $mode = $request->mode;

        $input = trim($request->text);

        $key = $request->key;

        if ($this->isBinaryInput($input)) {

            $inputBinary = explode(
                ' ',
                $input
            );

        } else {

            $inputBinary = $this->textToBinary(
                $input
            );
        }

        $keyBinary = $this->textToBinary(
            $key
        );

        $resultBinary = [];

        $resultText = '';

        for ($i = 0; $i < count($inputBinary); $i++) {

            $binInput = $inputBinary[$i];

            $binKey = $keyBinary[
                $i % count($keyBinary)
            ];

            $xor = $this->xorBinary(
                $binInput,
                $binKey
            );

            $resultBinary[] = $xor;

            $decimal = $this->binaryToDecimal(
                $xor
            );

            $resultText .= $this->asciiToChar(
                $decimal
            );
        }

        return view('xor', [

            'mode' => $mode,

            'input' => $input,

            'key' => $key,

            'resultBinary' => implode(
                ' ',
                $resultBinary
            ),

            'resultText' => $resultText,
        ]);
    }
}
