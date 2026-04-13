<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Wallet;
use App\Rules\CurrencyCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'currency_code' => ['required', new CurrencyCode]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ]);
        }

        $currency = Currency::where('code', $request->currency_code)->first();

        $wallet = Wallet::create([
            'name' => $request->name,
            'currency_id' => $currency->id,
            'user_id' => Auth::user()->id
        ]);

        $wallet['currency_code'] = $currency->code;

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet added successful',
            'data' => $wallet
        ]);
    }

    public function update(Request $request, $walletId)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field',
                'errors' => $validate->errors()
            ]);
        }

        $wallet = Wallet::find($walletId);

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found'
            ]);
        }

        if (!Auth::user()->wallet->find($walletId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden access'
            ]);
        }

        $wallet->update(['name' => $request->name]);
        $wallet['currency_code'] = $wallet->currency->code;

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet updated successful',
            'data' => $wallet
        ]);
    }

    public function delete($walletId)
    {
        $wallet = Wallet::find($walletId);

        if (!$wallet) {
            return response()->json([
                "status" => "error",
                "message" => "Not found"
            ]);
        }

        if (!Auth::user()->wallet->find($walletId)) {
            return response()->json([
                "status" => "error",
                "message" => "Forbidden access"
            ]);
        }

        $wallet->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet deleted successful'
        ]);
    }

    public function index()
    {
        $wallets = Wallet::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Get all wallet successful',
            'date' => $wallets
        ]);
    }

    public function find(Wallet $wallet)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get detail wallet successful',
            'date' => $wallet
        ]);
    }
}
