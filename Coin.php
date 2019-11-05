<?php

namespace app\modules\api\interfaces;

interface Coin
{

    public function __construct();

    /* @return string address|memo
     * @throws \Exception on error
     */
    public function getAddress(): string;

    /**
     * @return array List of transaction sorted by time DESC
     return [
                [
                    'txid' => '123124',//id transaction (required *)
                    'type' => 'receive',//send (required *)
                    'amount' => 1, //double amount of coins (required *)
                    'sender' => 'from',//address|name|account from (required *)
                    'receiver' => 'to',//address|name|account to (required *)
                    'comment' => null,//  description|memo of transaction  (optional *)
                    'confirmations' => null// confirmations of transaction   (optional *)
                ],
                [
                    'txid' => '123123',//id transaction (required *)
                    'type' => 'receive',//send (required *)
                    'amount' => 1.5, //double amount of coins (required *)
                    'sender' => 'from',//address from (required *)
                    'receiver' => 'to',//address to (required *)
                    'comment' => null,// description|memo of transaction  (optional *)
                    'confirmations' => null// confirmations of transaction   (optional *)
                ],
                //...
     ];
     * @param $address string coind address or NULL if get transactions in account
     * @param $block_number int last check block
     * @throws \Exception on error
     */
    public function getListTransactions(string $address = '', int $block_number = 0): array;

    /**
     * @param $address string
     * @return float balance of address(account)*
     * @throws \Exception on error
     */
    public function getBalance(string $address = ''): float;

    /**
     * @return string |bool Hash of transaction or false (if error)
     * @param $address string Address to send
     * @param $amount float amount of withdraw (example 0.1)
     * @param $describe string|null Description of withdraw
     * @throws \Exception on error
     */
    public function withdraw(string $address, float $amount, string $describe = null): string;

    /**
     * @return array Data transaction
     *
     return [
        'txid' => '123123',//id transaction (required *)
        'type' => 'receive',//send (required *)
        'amount' => 1.5, //double amount of coins (required *)
        'sender' => 'from',//address from (required *)
        'receiver' => 'to',//address to (required *)
        'comment' => null,// description|memo of transaction  (optional *)
        'confirmations' => null// confirmations of transaction   (optional *)
     ];
     *
     * @param $txid string ID of transaction
     * @throws \Exception on error
     */
    public function getTransactionById(string $txid): array;

//    If you use getListTransactions by block, you need to write function getBlockCount
//    /**
//    * @return integer Block high
//    */
//    public function getBlockCount(): int;

//    If lib don't use memo, description, etc., but use address generator
//    and there is no way to get common balance or/and it's necessary to specify address to withdraw from,
//    you need to write function (called drain) to drain balances from generated addresses to system address
//    /**
//     * @return string |bool Hash of transaction or false ( if error)
//     * @param $from_address string Address to drain from
//     * @param $amount double amount of drain (example 0.1)
//     * @param $describe string|null Description of withdraw
//     */
//    public function drain(string $from_address, float $amount): string;


//    If lib uses function drain and coin has gas to pay commission, you also need to write function sendGas
//    which will send gas from system address to generated address:
//     * @return string |bool Hash of transaction or false (if error)
//     * @param $to_address string Address to send gas to
//     * @param $amount float amount of gas (example 0.1)
//     */
//    public function sendGas(string $to_address, float $amount): string;
}