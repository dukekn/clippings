<?php
namespace App\Core\Classes;

use App\Core\Factory\DataFactory;

class DataHandler
{
    private array $file_data;
    public  array $currency_data;

    public function __construct(array $file , array $post)
    {
        set_exception_handler('\App\Core\Factory\DataFactory::setException');

        $this->file_data = \App\Core\Factory\DataFactory::getData($file);
        $this->currency_data = \App\Core\Factory\DataFactory::getCurrencie($post);

         $this->getResults();
    }

    private function getResults()
    {
        $headers = array_values($this->file_data ['Customer'][0]);
        unset($this->file_data ['Customer']);
        $customers_count = count($this->file_data);
        $total_grand = 0;
        for($i = 0; $i < $customers_count; $i++)
        {
            $customer = $this->file_data[array_keys($this->file_data)[$i ]];
            $cust_id = array_keys($this->file_data)[$i];
            $html = '<table class="table_client" data-customer="'.strtolower(str_replace(' ','',$cust_id)).'">';

            $html .= '<thead><tr><td></td>';

            for($a = 0; $a < count($headers); $a++)
            {
                $html .= '<td>'. $headers[$a] .'</td>';
            }
            $html .= '</tr></thead><tbody>';

            $customer_total = 0;
            foreach ($customer as $key => $document) {
                $values = array_values($document);
                $currency = $document['currency'];
                $amount = $document['amount'];
                $amount = DataFactory::exchange($this->currency_data , $amount , $currency);
                $type = $document['type'];

                $doc_id= $document['doc_id'];
                $parent_doc_id_current = $document['parent_doc_id'];
                $symbol  = '';
                $html .= ($parent_doc_id_current != '')? '<tr data-id="'.$doc_id.'" data-parent="'.$parent_doc_id_current.'">' : '<tr data-id="'.$doc_id.'">';

                if($parent_doc_id_current != '' && is_numeric($parent_doc_id_current) )
                {
                    try{
                        if(!in_array($parent_doc_id_current , array_column($customer , 'doc_id'))){
                            throw new \Exception('Parent document is missing!');
                            $html .= '<td></td>';
                        }else{
                            switch ($type)
                            {
                                case  '1' :    $html .= '<td> </td>';//invoice
                                    break;
                                case  '2' :   $symbol = '-';  $html .= '<td> - </td>';//credit note -/total
                                    break;
                                case  '3'  : $symbol = '+';   $html .= '<td> + </td>';//debit note +/total
                                    break;
                            }
                        }
                    }catch(\Exception $e){
                        throw new \Exception($e);
                    }
                }else{
                    $html .= '<td></td>';
                }

                    // check if parent is missing
                    for($b = 0; $b < count($values); $b++)
                    {

                        if($b == (count($values ) - 1))
                        {
//                            var_dump(DataFactory::exchange($this->currency_data , $amount , $currency));
                            $html .= '<td>'. $symbol.' '.$amount .' '.$this->currency_data['output'].'</td>';
                            $customer_total = ($type == 2)? $customer_total - $amount : $customer_total + $amount;
                        }else{
                            $html .= '<td>'. $values[$b] .'</td>';
                        }

                    }

                    if($key == (count($customer) - 1) )
                    {
                        $total_grand +=$customer_total;
                        $html .= ' <tr><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td class="total_sub">TOTAL</td><td class="total_sub">'.$customer_total.'  '.$this->currency_data['output'].'</td></tr>';
                    }
            }
            $html .= '</tbody></table>';
            print($html);
        } // end for each customer

        $html_grand = '<table class="totals_grand"><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="total">GRAND TOTAL</td><td class="total">'.$total_grand.'  '.$this->currency_data['output'].'</td></tr></table>';
        print($html_grand);

    }
}