<?php

namespace App\Core\Factory;


abstract class DataFactory
{
    protected string $currency_output;

    public static function getData(array $file): array
    {
        $values = array_map('str_getcsv', file($file['tmp_name']));
        $keys = ['cust_id', 'vat_id', 'doc_id', 'type', 'parent_doc_id', 'currency', 'amount'];
        $result = array();
        foreach ($values as $value) {
            if ((isset($result[$value[0]]))) {
                array_push($result[$value[0]], array_combine($keys, $value));
            } else {
                $result[$value[0]] = [array_combine($keys, $value)];
            }
        }
        return $result;
    }

    public static function setException($ex)
    {
        print("<span><b>Exception:</b> " . $ex->getMessage()."</span>");
    }

    public static function getCurrencie(array $post): array
    {
        $pair_codes = json_decode($post['pair_code'], true);
        $pair_rates = json_decode($post['pair_rate'], true);

        return [
            "default" => [filter_var(strtoupper($post['currency_main']), FILTER_SANITIZE_STRING) => 1],
            'output' => filter_var(strtoupper($post['currency_output']), FILTER_SANITIZE_STRING),
            'rates' => array_combine(array_map('strtoupper', $pair_codes), $pair_rates)
        ];
    }

    public static function exchange(array $rates, float $amount, string $currency_from): string
    {
        $currency_output = $rates['output'];

        try {
            //is default currency from?
            if ($currency_from == array_keys($rates['default'])[0]) {
                if (in_array($currency_output, array_keys($rates['rates']))) {
                    $rate = $rates['rates'][$currency_output];
                    return round($amount * $rate, 2) ;
                }
            } else {
                if (in_array($currency_from, array_keys($rates['rates']))) {
                    $rate_default = array_values($rates['default'])[0];
                    $rate_current = $rates['rates'][$currency_from]?? null;
                    $rate_output = $rates['rates'][$currency_output]?? null;
                    if(!isset($rate_current))
                    {
                        throw new \Exception($currency_from . ' is not defined or unsupported!');
                    }
                    if(!isset($rate_output))
                    {
                        throw new \Exception($currency_output . ' is not defined or unsupported!');
                    }

                    return round(($amount / $rate_current) * $rate_output, 2);
                } else {
//                    return  $currency_from.' is not defined!';
                    throw new \Exception($currency_from . ' is not defined!');
                }
            }
        } catch (\Exception $e) {
            throw new\Exception($e->getMessage());
        }
    }


}