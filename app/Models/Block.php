<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function calculateHash()
    {
        return hash('sha256', $this->preHash . $this->created_at . $this->nonce);
    }
    public function hasValidTransaction()
    {
        $transactions = Transaction::all()->where('block_id', $this->id);
        foreach ($transactions as $trans) {
            if (!$trans->isValid()) {
                return false;
            }
        }
        return true;
    }
    public function isSolved($difficulty)
    {
        return substr($this->hash, 0, $difficulty) === str_repeat("0", $difficulty);
    }
    public function mineBlock($difficulty)
    {
        while (!$this->isSolved($difficulty)) {
            $this->nonce++;
            $this->hash = $this->calculateHash();
            $this->save();
        }
    }
}
