<div>
    <header class="flex justify-between items-center mb-4 shrink-0">
        <x-date-display :month-date="$monthDate" :day-indo="$dayIndo" />

        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gray-300 rounded-lg flex items-center justify-center text-clipzo-text-gray">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="text-leftclaer">
                <p class="font-semibold text-clipzo-dark">{{ ucfirst(auth()->user()->username ) }}</p>
                <p class="text-sm text-clipzo-text-gray">management team</p>
            </div>
        </div>
    </header>
    
    <div class="grid grid-cols-4 gap-4 mb-4 shrink-0">
        {{-- Monthly Revenue Card --}}
        <a href="{{ route('superadmin.cash-in') }}" class="bg-black hover:opacity-90 text-white p-4 rounded-xl shadow-lg flex justify-between items-start">
            <div>
                <p class="text-xs font-medium opacity-80">Net Profit Bulan Ini</p>
                <h2 class="text-2xl font-bold mt-1">Rp {{ number_format($monthlyNetProfit, 0, ',', '.') }}</h2>
                <span class="text-xs 
                    @if(is_null($netProfitChange))
                        text-gray-400
                    @elseif($netProfitChange >= 0)
                        text-green-400
                    @else
                        text-red-400
                    @endif
                    mt-1 block">
                    @if(is_null($netProfitChange))
                        -
                    @else
                        {{ $netProfitChange >= 0 ? '+' : '' }}{{ number_format($netProfitChange, 1) }}% vs bulan lalu
                    @endif
                </span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19L19 5M19 5H9m10 0v10" />
            </svg>
        </a>

        {{-- Monthly Revenue Card --}}
        <a href="{{ route('superadmin.cash-in') }}" class="bg-white hover:opacity-90 p-4 rounded-xl shadow-lg flex justify-between items-start">
            <div>
                <p class="text-xs font-medium opacity-80">Pemasukan Bulan Ini</p>
                <h2 class="text-2xl font-bold mt-1">Rp {{ number_format($monthlyAmountTransactions, 0, ',', '.') }}</h2>
                <span class="text-xs 
                    @if(is_null($amountChange))
                        text-gray-400
                    @elseif($amountChange >= 0)
                        text-green-400
                    @else
                        text-red-400
                    @endif
                    mt-1 block">
                    @if(is_null($amountChange))
                        -
                    @else
                        {{ $amountChange >= 0 ? '+' : '' }}{{ number_format($amountChange, 1) }}% vs bulan lalu
                    @endif
                </span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19L19 5M19 5H9m10 0v10" />
            </svg>
        </a>
        
        {{-- Monthly Expenses Card --}}
        <a href="{{ route('superadmin.cash-out') }}" class="bg-white hover:bg-gray-200 p-4 rounded-xl shadow-md border border-gray-200 flex justify-between items-start text-clipzo-dark">
            <div>
                <p class="text-xs font-medium opacity-80">Pengeluaran Bulan Ini</p>
                <h2 class="text-2xl font-bold mt-1">Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}</h2>
                <span class="text-xs 
                    @if(is_null($expenseChange))
                        text-gray-400
                    @elseif($expenseChange < 0)
                        text-green-400
                    @else
                        text-red-400
                    @endif
                    mt-1 block">
                    @if(is_null($expenseChange))
                        -
                    @else
                        {{ $expenseChange >= 0 ? '+' : '' }}{{ number_format($expenseChange, 1) }}% vs bulan lalu
                    @endif
                </span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19L19 5M19 5H9m10 0v10" />
            </svg>
        </a>

        {{-- Daily Busy Hour --}}
        <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 flex justify-between items-start text-clipzo-dark">
            <div class="flex-1">
                <p class="text-xs font-medium text-clipzo-text-gray">Jam Sibuk Harian</p>
                <h2 class="text-xl font-bold mt-1">{{ $busiestHour['time'] }}</h2>
                @if($busiestHour['count'] > 0)
                    <span class="text-xs text-blue-600 mt-1 block">{{ $busiestHour['count'] }} transaksi</span>
                @endif
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
</div>