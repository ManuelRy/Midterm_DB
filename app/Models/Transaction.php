<?php

namespace App\Models;

use Elliptic\EC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function calculateHash()
    {
        return hash('sha256', $this->from . $this->to . $this->amount);
    }
    public function signTransaction()
    {
        $ec = new EC('secp256k1');
        $key = $ec->keyFromPrivate('f77ccb975e9a6c315026de48fd35e5299fb014296303b63eac4fe00f2f493ce7');
        if ($key->getPublic('hex') == $this->from) {
            return;
        }
        $hashTx = $this->calculateHash();
        $this->signature = $key->sign($hashTx)->toDER('hex');
        $this->save();
    }
    public function isValid()
    {
        $ec = new EC('secp256k1');
        $key = $ec->keyFromPrivate(env('PRI_KEY'));

        if (!$this->from || !$this->to || !$this->signature) {
            return false;
        }
   
        return $key->verify($this->calculateHash(), $this->signature);
    }
}
