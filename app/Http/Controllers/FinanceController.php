<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Debt;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Récupérer toutes les données
        $incomes = Income::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        $expenses = Expense::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        $debts = Debt::where('user_id', $user->id)
            ->orderBy('due_date', 'asc')
            ->get();

        $wishlists = Wishlist::where('user_id', $user->id)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques du mois en cours
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyIncome = Income::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $monthlyExpense = Expense::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // Statistiques du jour
        $today = Carbon::today();

        $dailyIncome = Income::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->sum('amount');

        $dailyExpense = Expense::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->sum('amount');

        // Total des dettes
        $totalDebt = Debt::where('user_id', $user->id)
            ->where('status', '!=', 'paid')
            ->sum('total_amount');

        $totalPaid = Debt::where('user_id', $user->id)
            ->sum('paid_amount');

        return view('finance', compact(
            'incomes',
            'expenses',
            'debts',
            'wishlists',
            'monthlyIncome',
            'monthlyExpense',
            'dailyIncome',
            'dailyExpense',
            'totalDebt',
            'totalPaid'
        ));
    }

    // Income methods
    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();
        Income::create($validated);

        return redirect()->route('finance.index')->with('success', 'Revenu ajouté avec succès!');
    }

    public function deleteIncome($id)
    {
        $income = Income::where('user_id', Auth::id())->findOrFail($id);
        $income->delete();

        return redirect()->route('finance.index')->with('success', 'Revenu supprimé avec succès!');
    }

    // Expense methods
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();
        Expense::create($validated);

        return redirect()->route('finance.index')->with('success', 'Dépense ajoutée avec succès!');
    }

    public function deleteExpense($id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        $expense->delete();

        return redirect()->route('finance.index')->with('success', 'Dépense supprimée avec succès!');
    }

    // Debt methods
    public function storeDebt(Request $request)
    {
        $validated = $request->validate([
            'creditor' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['paid_amount'] = $validated['paid_amount'] ?? 0;

        // Déterminer le statut
        if ($validated['paid_amount'] == 0) {
            $validated['status'] = 'pending';
        } elseif ($validated['paid_amount'] >= $validated['total_amount']) {
            $validated['status'] = 'paid';
        } else {
            $validated['status'] = 'partial';
        }

        Debt::create($validated);

        return redirect()->route('finance.index')->with('success', 'Dette ajoutée avec succès!');
    }

    public function updateDebt(Request $request, $id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0'
        ]);

        $debt->paid_amount = $validated['paid_amount'];

        // Mettre à jour le statut
        if ($debt->paid_amount == 0) {
            $debt->status = 'pending';
        } elseif ($debt->paid_amount >= $debt->total_amount) {
            $debt->status = 'paid';
        } else {
            $debt->status = 'partial';
        }

        $debt->save();

        return redirect()->route('finance.index')->with('success', 'Dette mise à jour avec succès!');
    }

    public function deleteDebt($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        $debt->delete();

        return redirect()->route('finance.index')->with('success', 'Dette supprimée avec succès!');
    }

    // Wishlist methods
    public function storeWishlist(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'priority' => 'required|string',
            'description' => 'nullable|string',
            'url' => 'nullable|url'
        ]);

        $validated['user_id'] = Auth::id();
        Wishlist::create($validated);

        return redirect()->route('finance.index')->with('success', 'Article ajouté à la liste de souhaits!');
    }

    public function markPurchased($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->purchased = true;
        $wishlist->purchased_date = Carbon::now();
        $wishlist->save();

        return redirect()->route('finance.index')->with('success', 'Article marqué comme acheté!');
    }

    public function deleteWishlist($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();

        return redirect()->route('finance.index')->with('success', 'Article supprimé de la liste!');
    }
}
