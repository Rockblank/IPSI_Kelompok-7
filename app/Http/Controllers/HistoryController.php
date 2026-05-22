<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Loan;

class HistoryController extends Controller
{
    // GET /history
    public function index(Request $request)
    {
        $user_id = session('user_id');
        $filter  = $request->query('status');

        $query = Loan::with('book')
            ->where('user_id', $user_id)
            ->orderBy('loan_date', 'desc');

        if ($filter === 'borrowed' || $filter === 'returned') {
            $query->where('transaction_status', $filter);
        }

        $loans = $query->get();

        $loans->each(function ($loan) {
            if ($loan->transaction_status === 'borrowed') {
                $sisa = Carbon::today()->diffInDays(Carbon::parse($loan->due_date), false);
                $loan->sisa_hari = (int) $sisa;
            } else {
                $loan->sisa_hari = null;
            }
        });

        return view('history.index', compact('loans', 'filter'));
    }
}