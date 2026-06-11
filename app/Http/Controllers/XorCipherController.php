<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XorCipherController extends Controller
{
    private function charToAscii($char)
    {
        return ord($char);
    }

    private function asciiToChar($ascii)
    {
        return chr($ascii);
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
        $text = trim($text);

        if ($text == '') {
            return false;
        }

        $parts = explode(' ', $text);

        foreach ($parts as $part) {

            if (strlen($part) != 8) {
                return false;
            }

            for ($i = 0; $i < strlen($part); $i++) {

                if (
                    $part[$i] != '0' &&
                    $part[$i] != '1'
                ) {
                    return false;
                }
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

        $key = trim($request->key);

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

        if ($this->isBinaryInput($key)) {

            $keyBinary = explode(
                ' ',
                $key
            );

        } else {

            $keyBinary = $this->textToBinary(
                $key
            );
        }

        if (count($keyBinary) == 0) {

            return back()->with(
                'error',
                'Key tidak boleh kosong'
            );
        }

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

            if (
                ($decimal >= 0 && $decimal <= 31) ||
                $decimal == 127
            ) {

                $resultText .= '['.$decimal.']';

            } else {

                $resultText .= $this->asciiToChar(
                    $decimal
                );
            }
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
