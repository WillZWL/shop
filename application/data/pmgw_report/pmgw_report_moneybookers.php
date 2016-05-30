<?php
//mapping_array
$mapping_file =
[
    'type' =>'vo',
    'store' => 'PmgwReportMoneybookersDto',
    'mapping' => [
        0 => ['ID' => 'txn_id'],
        1 => ['Time (CET)' => 'txn_time'],
        2 => ['Type' => 'type'],
        3 => ['Transaction Details' => 'transaction_detail'],
        4 => [4 => 'amount_debit'],
        5 => [5 => 'amount_credit'],
        6 => ['Status' => 'status'],
        7 => ['Balance' => 'balance'],
        8 => ['Reference' => 'reference'],
        9 => ['Amount Sent' => 'order_amount_ref'],
        10 => ['Currency Sent' => 'currency_id'],
        11 => ['More Information' => 'so_no'],
        12 => ['ID of the coresponding Skrill transaction' => 'original_order_txn_id'],
        13 => ['Payment Instrument' => '']
    ]
]



?>