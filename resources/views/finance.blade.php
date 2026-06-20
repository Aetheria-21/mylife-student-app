<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suivi des finances - MyLife</title>
    @include('partials.indie-theme')
</head>
<body class="indie-page theme-{{ (auth()->user()->gender ?? 'male') === 'female' ? 'female' : 'male' }}">

<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-8">

        <!-- Header -->
        <div class="indie-panel p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="indie-kicker">Finances personnelles</p>
                    <h1 class="indie-title text-4xl font-black">💰 Suivi des finances</h1>
                    <p class="mt-2" style="color: var(--text-muted);">Suivez vos revenus, dépenses, dettes et listes de souhaits.</p>
                </div>
                <div class="flex gap-4 text-center">
                    <div class="indie-soft-panel px-5 py-4 min-w-28">
                        <p class="text-sm" style="color: var(--text-muted);">Balance du mois</p>
                        <p class="text-3xl font-black" style="color: var(--accent);">{{ number_format($monthlyIncome - $monthlyExpense, 2) }} DH</p>
                    </div>
                    <div class="indie-soft-panel px-5 py-4 min-w-28">
                        <p class="text-sm" style="color: var(--text-muted);">Total des dettes</p>
                        <p class="text-3xl font-black" style="color: var(--accent);">{{ number_format($totalDebt, 2) }} DH</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="indie-soft-panel text-emerald-700 px-5 py-4 font-bold" role="alert">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="indie-soft-panel p-6 text-center">
                <p class="text-sm font-medium" style="color: var(--text-muted);">Revenus du jour</p>
                <h3 class="text-3xl font-black mt-2" style="color: var(--accent-deep);">{{ number_format($dailyIncome, 2) }} DH</h3>
            </div>
            <div class="indie-soft-panel p-6 text-center">
                <p class="text-sm font-medium" style="color: var(--text-muted);">Dépenses du jour</p>
                <h3 class="text-3xl font-black mt-2" style="color: var(--accent-deep);">{{ number_format($dailyExpense, 2) }} DH</h3>
            </div>
            <div class="indie-soft-panel p-6 text-center">
                <p class="text-sm font-medium" style="color: var(--text-muted);">Revenus du mois</p>
                <h3 class="text-3xl font-black mt-2" style="color: var(--accent-deep);">{{ number_format($monthlyIncome, 2) }} DH</h3>
            </div>
            <div class="indie-soft-panel p-6 text-center">
                <p class="text-sm font-medium" style="color: var(--text-muted);">Dépenses du mois</p>
                <h3 class="text-3xl font-black mt-2" style="color: var(--accent-deep);">{{ number_format($monthlyExpense, 2) }} DH</h3>
            </div>
        </div>

        <!-- Balance Overview -->
        <div class="indie-panel p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div>
                    <p class="text-sm font-bold" style="color: var(--text-muted);">Balance du mois</p>
                    <h2 class="text-4xl font-black mt-2" style="color: var(--accent);">{{ number_format($monthlyIncome - $monthlyExpense, 2) }} DH</h2>
                </div>
                <div>
                    <p class="text-sm font-bold" style="color: var(--text-muted);">Total des dettes</p>
                    <h2 class="text-4xl font-black mt-2" style="color: var(--accent);">{{ number_format($totalDebt, 2) }} DH</h2>
                </div>
                <div>
                    <p class="text-sm font-bold" style="color: var(--text-muted);">Total payé</p>
                    <h2 class="text-4xl font-black mt-2" style="color: var(--accent);">{{ number_format($totalPaid, 2) }} DH</h2>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Income Section -->
            <div class="indie-panel p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-black" style="color: var(--text-main);">💵 Revenus</h2>
                    <button onclick="openIncomeModal()" class="indie-button px-4 py-2 text-sm">➕ Ajouter</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="indie-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Source</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incomes as $income)
                                <tr>
                                    <td>{{ $income->date->format('d/m/Y') }}</td>
                                    <td class="font-medium">{{ $income->source }}</td>
                                    <td class="font-bold" style="color: var(--accent-deep);">+{{ number_format($income->amount, 2) }} DH</td>
                                    <td>
                                        <form action="{{ route('finance.income.delete', $income->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Êtes-vous sûr ?')" class="text-xs font-bold px-3 py-1.5 rounded-full transition" style="background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.3);">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8" style="color: var(--text-muted);">Aucun revenu enregistré</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expense Section -->
            <div class="indie-panel p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-black" style="color: var(--text-main);">💸 Dépenses</h2>
                    <button onclick="openExpenseModal()" class="indie-button px-4 py-2 text-sm">➕ Ajouter</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="indie-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Nom</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->date->format('d/m/Y') }}</td>
                                    <td class="font-medium">{{ $expense->name }}</td>
                                    <td class="font-bold" style="color: var(--accent-deep);">-{{ number_format($expense->amount, 2) }} DH</td>
                                    <td>
                                        <form action="{{ route('finance.expense.delete', $expense->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Êtes-vous sûr ?')" class="text-xs font-bold px-3 py-1.5 rounded-full transition" style="background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.3);">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8" style="color: var(--text-muted);">Aucune dépense enregistrée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Debts Section -->
        <div class="indie-panel p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black" style="color: var(--text-main);">💳 Suivi des Dettes</h2>
                <button onclick="openDebtModal()" class="indie-button px-4 py-2 text-sm">➕ Ajouter</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($debts as $debt)
                    <div class="indie-soft-panel p-6 {{ $debt->status === 'paid' ? 'opacity-75' : '' }}">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold" style="color: var(--text-heading);">{{ $debt->creditor }}</h3>
                                <p class="text-sm" style="color: var(--text-muted);">
                                    @if($debt->due_date)
                                        Échéance: {{ $debt->due_date->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                            <span class="indie-pill text-xs font-semibold">
                                {{ $debt->status === 'paid' ? '✅ Payé' : ($debt->status === 'partial' ? '⏳ Partiel' : '❌ En attente') }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--text-muted);">Total:</span>
                                <span class="font-bold">{{ number_format($debt->total_amount, 2) }} DH</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--text-muted);">Payé:</span>
                                <span class="font-bold" style="color: var(--accent-deep);">{{ number_format($debt->paid_amount, 2) }} DH</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span style="color: var(--text-muted);">Restant:</span>
                                <span class="font-bold" style="color: var(--accent-deep);">{{ number_format($debt->remaining_amount, 2) }} DH</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="indie-progress mb-4">
                            <div class="indie-progress-bar" style="width: {{ ($debt->paid_amount / $debt->total_amount) * 100 }}%"></div>
                        </div>

                        <div class="flex gap-2">
                            <button onclick="openUpdateDebtModal({{ $debt->id }}, {{ $debt->total_amount }}, {{ $debt->paid_amount }})"
                                class="indie-button flex-1 px-3 py-2 text-xs font-black text-white">
                                💰 Payer
                            </button>
                            <form action="{{ route('finance.debt.delete', $debt->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr ?')"
                                    class="w-full secondary-button px-3 py-2 text-xs font-bold transition">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12" style="color: var(--text-muted);">
                        <p class="text-xl">🎉 Aucune dette enregistrée !</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Wishlist Section -->
        <div class="indie-panel p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black" style="color: var(--text-main);">🎁 Liste de Souhaits</h2>
                <button onclick="openWishlistModal()" class="indie-button px-4 py-2 text-sm">➕ Ajouter</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($wishlists as $item)
                    <div class="indie-soft-panel p-6 {{ $item->purchased ? 'opacity-75' : '' }}">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-bold {{ $item->purchased ? 'line-through' : '' }}" style="color: var(--text-heading);">
                                {{ $item->item_name }}
                            </h3>
                            <span class="indie-pill text-xs font-bold">
                                {{ $item->priority === 'high' ? '🔥 Haute' : ($item->priority === 'medium' ? '⚡ Moyenne' : '✨ Basse') }}
                            </span>
                        </div>

                        @if($item->price)
                            <p class="text-2xl font-bold mb-2" style="color: var(--accent);">{{ number_format($item->price, 2) }} DH</p>
                        @endif

                        @if($item->description)
                            <p class="text-sm mb-4" style="color: var(--text-muted);">{{ $item->description }}</p>
                        @endif

                        @if($item->url)
                            <a href="{{ $item->url }}" target="_blank" class="indie-link text-sm block mb-4">
                                🔗 Voir le produit
                            </a>
                        @endif

                        @if($item->purchased)
                            <p class="text-sm font-semibold mb-4" style="color: var(--accent-deep);">
                                ✅ Acheté le {{ $item->purchased_date->format('d/m/Y') }}
                            </p>
                        @endif

                        <div class="flex gap-2">
                            @if(!$item->purchased)
                                <form action="{{ route('finance.wishlist.purchased', $item->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="indie-button w-full px-3 py-2 text-xs font-black text-white">
                                        ✅ Marquer acheté
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('finance.wishlist.delete', $item->id) }}" method="POST" class="{{ $item->purchased ? 'w-full' : 'flex-1' }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr ?')"
                                    class="w-full secondary-button px-3 py-2 text-xs font-bold transition">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12" style="color: var(--text-muted);">
                        <p class="text-xl">📝 Aucun article dans votre liste de souhaits</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Income Modal -->
    <div id="incomeModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="indie-modal-card p-8 max-w-md w-full">
            <h3 class="indie-title text-2xl font-bold mb-6" style="color: var(--text-heading);">💵 Ajouter un Revenu</h3>
            <form action="{{ route('finance.income.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Source</label>
                        <input type="text" name="source" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Montant (DH)</label>
                        <input type="number" step="0.01" name="amount" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Date</label>
                        <input type="date" name="date" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Catégorie</label>
                        <select name="category" required class="indie-input w-full px-4 py-2 rounded-xl">
                            <option value="salary">Salaire</option>
                            <option value="freelance">Freelance</option>
                            <option value="investment">Investissement</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Description (optionnel)</label>
                        <textarea name="description" rows="3" class="indie-input w-full px-4 py-2 rounded-xl resize-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="indie-button flex-1 py-2.5 text-sm font-black text-white">Ajouter</button>
                    <button type="button" onclick="closeIncomeModal()" class="secondary-button flex-1 py-2.5 text-sm font-bold">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Expense Modal -->
    <div id="expenseModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="indie-modal-card p-8 max-w-md w-full">
            <h3 class="indie-title text-2xl font-bold mb-6" style="color: var(--text-heading);">💸 Ajouter une Dépense</h3>
            <form action="{{ route('finance.expense.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Nom</label>
                        <input type="text" name="name" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Montant (DH)</label>
                        <input type="number" step="0.01" name="amount" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Date</label>
                        <input type="date" name="date" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Catégorie</label>
                        <select name="category" required class="indie-input w-full px-4 py-2 rounded-xl">
                            <option value="food">Nourriture</option>
                            <option value="transport">Transport</option>
                            <option value="entertainment">Divertissement</option>
                            <option value="bills">Factures</option>
                            <option value="shopping">Shopping</option>
                            <option value="health">Santé</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Description (optionnel)</label>
                        <textarea name="description" rows="3" class="indie-input w-full px-4 py-2 rounded-xl resize-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="indie-button flex-1 py-2.5 text-sm font-black text-white">Ajouter</button>
                    <button type="button" onclick="closeExpenseModal()" class="secondary-button flex-1 py-2.5 text-sm font-bold">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Debt Modal -->
    <div id="debtModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="indie-modal-card p-8 max-w-md w-full">
            <h3 class="indie-title text-2xl font-bold mb-6" style="color: var(--text-heading);">💳 Ajouter une Dette</h3>
            <form action="{{ route('finance.debt.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Créancier</label>
                        <input type="text" name="creditor" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Montant Total (DH)</label>
                        <input type="number" step="0.01" name="total_amount" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Montant Payé (DH)</label>
                        <input type="number" step="0.01" name="paid_amount" value="0" class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Date d'échéance (optionnel)</label>
                        <input type="date" name="due_date" class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Description (optionnel)</label>
                        <textarea name="description" rows="3" class="indie-input w-full px-4 py-2 rounded-xl resize-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="indie-button flex-1 py-2.5 text-sm font-black text-white">Ajouter</button>
                    <button type="button" onclick="closeDebtModal()" class="secondary-button flex-1 py-2.5 text-sm font-bold">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Debt Modal -->
    <div id="updateDebtModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="indie-modal-card p-8 max-w-md w-full">
            <h3 class="indie-title text-2xl font-bold mb-6" style="color: var(--text-heading);">💰 Mettre à jour le paiement</h3>
            <form id="updateDebtForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Montant Payé (DH)</label>
                        <input type="number" step="0.01" name="paid_amount" id="paidAmountInput" required class="indie-input w-full px-4 py-2 rounded-xl">
                        <p class="text-sm mt-1" style="color: var(--text-muted);">Total: <span id="totalAmountDisplay"></span> DH</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="indie-button flex-1 py-2.5 text-sm font-black text-white">Mettre à jour</button>
                    <button type="button" onclick="closeUpdateDebtModal()" class="secondary-button flex-1 py-2.5 text-sm font-bold">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Wishlist Modal -->
    <div id="wishlistModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="indie-modal-card p-8 max-w-md w-full">
            <h3 class="indie-title text-2xl font-bold mb-6" style="color: var(--text-heading);">🎁 Ajouter à la Liste de Souhaits</h3>
            <form action="{{ route('finance.wishlist.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Nom de l'article</label>
                        <input type="text" name="item_name" required class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Prix (DH) - optionnel</label>
                        <input type="number" step="0.01" name="price" class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Priorité</label>
                        <select name="priority" required class="indie-input w-full px-4 py-2 rounded-xl">
                            <option value="low">✨ Basse</option>
                            <option value="medium" selected>⚡ Moyenne</option>
                            <option value="high">🔥 Haute</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">URL (optionnel)</label>
                        <input type="url" name="url" class="indie-input w-full px-4 py-2 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Description (optionnel)</label>
                        <textarea name="description" rows="3" class="indie-input w-full px-4 py-2 rounded-xl resize-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="indie-button flex-1 py-2.5 text-sm font-black text-white">Ajouter</button>
                    <button type="button" onclick="closeWishlistModal()" class="secondary-button flex-1 py-2.5 text-sm font-bold">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Income Modal
        function openIncomeModal() {
            document.getElementById('incomeModal').classList.remove('hidden');
        }
        function closeIncomeModal() {
            document.getElementById('incomeModal').classList.add('hidden');
        }

        // Expense Modal
        function openExpenseModal() {
            document.getElementById('expenseModal').classList.remove('hidden');
        }
        function closeExpenseModal() {
            document.getElementById('expenseModal').classList.add('hidden');
        }

        // Debt Modal
        function openDebtModal() {
            document.getElementById('debtModal').classList.remove('hidden');
        }
        function closeDebtModal() {
            document.getElementById('debtModal').classList.add('hidden');
        }

        // Update Debt Modal
        function openUpdateDebtModal(debtId, totalAmount, paidAmount) {
            const modal = document.getElementById('updateDebtModal');
            const form = document.getElementById('updateDebtForm');
            const input = document.getElementById('paidAmountInput');
            const display = document.getElementById('totalAmountDisplay');

            form.action = `/finance/debt/${debtId}`;
            input.value = paidAmount;
            display.textContent = totalAmount;

            modal.classList.remove('hidden');
        }
        function closeUpdateDebtModal() {
            document.getElementById('updateDebtModal').classList.add('hidden');
        }

        // Wishlist Modal
        function openWishlistModal() {
            document.getElementById('wishlistModal').classList.remove('hidden');
        }
        function closeWishlistModal() {
            document.getElementById('wishlistModal').classList.add('hidden');
        }

        // Close modals on outside click
        window.onclick = function(event) {
            const modals = ['incomeModal', 'expenseModal', 'debtModal', 'updateDebtModal', 'wishlistModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }

        // Set today's date as default
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                if (!input.value && input.name !== 'due_date') {
                    input.value = today;
                }
            });
        });
    </script>
</div>{{-- /page-layout --}}
</body>
</html>
