<?php

namespace app\modules\api\libs\algo;
use app\modules\api\interfaces\Coin;
use Yii;

class CoinLib implements Coin {
    public $api, $params;
    const DECIMAL = 1000000;

    public function __construct() {
        $this->params = Yii::$app->params['lib']["algo"];
        $this->api = new ALGOInterface($this->params["host"], $this->params["system_address"], $this->params["password"]);
    }

    /* @return string address
     * @throws \Exception on error
     */
    public function getAddress(): string {
        $new_address = $this->api->createAddress();
        if ($new_address === false){
            throw new \Exception("Can't generate new address");
        }
        else{
            return $new_address;
        }
    }

    /**
     * @return array List of transaction sorted by time DESC
     * @param $address string coind address or NULL if get transactions in account
     * @param $block_number int last check block tbPcPuF7g9YRE43o3rrY2zYFv7LvHrLp
     * @throws \Exception on error
     */

    public function getListTransactions(string $address = '', int $block_number = 0): array {

        $final_tx_list = [];

        $block_info = $this->api->getBlockByHeight($block_number);

        if ($block_info === false){
            throw new \Exception("Can't get block info");
        }

        if(isset($block_info["txns"]["transactions"])){
            foreach($block_info["txns"]["transactions"] as $tx_info){
                if($tx_info == false){
                    throw new \Exception("Can't get transactions list");
                }

                array_push($final_tx_list, array(
                    'txid' => $tx_info["tx"],//id transaction (required *)
                    'type' => null,//send (required *)
                    'amount' => $tx_info["payment"]["amount"] / self::DECIMAL, //double amount of coins (required *)
                    'sender' => $tx_info["from"],//address|name|account from (required *)
                    'receiver' => $tx_info["payment"]["to"],//address|name|account to (required *)
                    'comment' => null,//  description|memo of transaction  (optional *)
                    'confirmations' => null// confirmations of transaction   (optional *)
                ));

            }
        }


        return $final_tx_list;
    }

    /**
     * @param $address string
     * @param $asset string ont or ong
     * @return double balance of address(account)*
     * @throws \Exception on error
     */
    public function getBalance(string $address = '', $asset = 'ALGO'): float {
        $balance = $this->api->getBalance($address);
        if ($balance === false){
            throw new \Exception("Can't get balance of this address or asset");
        }
        else{
            return $balance;
        }
    }

    /**
     * @return int
     * @throws \Exception on error
     */
    public function getBlockCount(): int{
        $blockcount = $this->api->getBlockCount();
        if ($blockcount === false){
            throw new \Exception("Can't get block count");
        }
        else{
            return $blockcount;
        }
    }

    /**
     * @return array Data transaction
     * @param $txid string ID of transaction
     * @throws \Exception on error
     */
    public function getTransactionById(string $txid): array {
        $tx_info = $this->api->getTX($txid);

        //print_r($tx_info); die();
        if ($tx_info == false){
            throw new \Exception("Can't transaction with this id");
        }
        return [
            'txid' => $tx_info["hash"],//id transaction (required *)
            'type' => null,//send (required *)
            'amount' => $tx_info["to"][0]["amount"] / self::DECIMAL, //double amount of coins (required *)
            'sender' => $tx_info["from"][0]["address"],//address|name|account from (required *)
            'receiver' => $tx_info["to"][0]["address"],//address|name|account to (required *)
            'comment' => $tx_info["remark"],//  description|memo of transaction  (optional *)
            'confirmations' => null// confirmations of transaction   (optional *)
        ];
    }

    public function withdraw(string $address, float $amount, string $describe = null) : string {
        $txid = $this->api->withdraw($address, $amount);
        if ($txid == false){
            throw new \Exception("Can't make withdraw");
        }
        else{
            return $txid;
        }
    }

    /**
     * @return string
     * @param $address string
     * @param $describe string
     * @param $amount float
     * @throws \Exception on error
     */
    public function drain(string $address, float $amount, string $describe = null) : string {
        $txid = $this->api->drain($address, $amount);
        if ($txid == false){
            throw new \Exception("Can't make drain");
        }
        else{
            return $txid;
        }
    }

}