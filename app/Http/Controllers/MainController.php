<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MainController extends Controller
{
    private int $miningAmt = 200;
    private int $difficulty = 2;
    public function index()
    {
        $blocks = Block::all();
        if (!count($blocks)) {
            $this->addBlock();
            $blocks = Block::all();
        }
        $trans = Transaction::all();
        $isChainValid = $this->isChainValid();
        $balance = $this->getBalanceOfAddress();
        return view('main', compact('blocks', 'isChainValid', 'trans', 'balance'));
    }

    public function addBlock()
    {
        $block = Block::create([
            "preHash" => Block::orderBy('created_at', 'desc')->first()->hash ?? '0',
        ]);
        $block->hash = $block->calculateHash();
        $block->save();
    }
    public function addTransaction($id)
    {
        $trans = Transaction::create([
            'block_id' => $id,
            'from' => '4b68527afa91570e4c32d6b5617582bc59fb4777ca81e1d8da295d0bc70146b0',
            'to' => 'public key',
        ]);
        $trans->signTransaction();
        return redirect('/');
    }

    public function minePendingTransactions($id)
    {
        $block = Block::find($id);
        $block->mineBlock($this->difficulty);
        Transaction::where('block_id', $id)->update(['block_id' => 0]);
        $this->addBlock();
        return redirect('/');
    }
    public function getBalanceOfAddress()
    {
        $counts = Transaction::where('block_id', 0)->count();
        return $counts * $this->miningAmt;
    }
    public function reset()
    {
        Transaction::truncate();
        Block::truncate();
        return redirect('/');
    }
    public function isChainValid()
    {
        $blocks = Block::all();
        for ($i = 1; $i < count($blocks); $i++) {
            $currentBlock = $blocks[$i];
            $lastBlock = $blocks[$i - 1];
            if ($currentBlock->preHash != $lastBlock->hash) return false;
        }
        return true;
    }
}
