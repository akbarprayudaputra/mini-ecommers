<?php

namespace MiniECommers\Backend\Helpers;

class Json
{
    /**
     * Encode data (object or array) to JSON.
     *
     * @param mixed $data The data to be encoded (object or array)
     * @return string JSON representation of the data
     * @throws \InvalidArgumentException If the input is not an array or object
     */
    public static function encode($data): string
    {
        // Validasi apakah data adalah array atau object
        if (!is_array($data) && !is_object($data)) {
            throw new \InvalidArgumentException("Input harus berupa array atau object.");
        }

        // Konversi ke JSON
        $json = json_encode($data);

        // Periksa error dalam proses konversi
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Gagal mengonversi ke JSON: " . json_last_error_msg());
        }

        return $json;
    }

    /**
     * Decode JSON to array or object.
     *
     * @param string $json The JSON string to be decoded
     * @param bool $assoc If true, decode to associative array; if false, decode to object
     * @return mixed Decoded data (array or object)
     * @throws \InvalidArgumentException If the input is not a valid JSON string
     */
    public static function decode(string $json, bool $assoc = false): mixed
    {
        // Validasi apakah input adalah string
        if (!is_string($json)) {
            throw new \InvalidArgumentException("Input harus berupa string JSON.");
        }

        // Konversi dari JSON ke array atau object
        $data = json_decode($json, $assoc);

        // Periksa error dalam proses decoding
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Gagal mengonversi dari JSON: " . json_last_error_msg());
        }

        return $data;
    }
}
