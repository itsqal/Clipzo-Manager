<div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 h-full flex flex-col">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-3 shrink-0">
        <h2 class="text-lg font-semibold text-gray-800">Jumlah Pelanggan</h2>
        <select x-on:change="$wire.setViewMode($event.target.value)" 
                class="border rounded-md px-3 py-1 text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer focus:outline-none focus:ring-2 focus:ring-gray-200">
            <option value="daily" {{ $viewMode === 'daily' ? 'selected' : '' }}>Harian</option>
            <option value="weekly" {{ $viewMode === 'weekly' ? 'selected' : '' }}>Mingguan</option>
            <option value="monthly" {{ $viewMode === 'monthly' ? 'selected' : '' }}>Bulanan</option>
        </select>
    </div>
    
    {{-- Chart Area --}}
    <div class="relative flex-grow min-h-0" wire:ignore>
        <canvas id="customerChart"></canvas>
    </div>
</div>

@script
<script>
    let customerChart = null;
    
    function initRevenueChart() {
        const ctx = document.getElementById('customerChart');
        if (!ctx) {
            console.error('Canvas element not found');
            return;
        }
        const chartData = $wire.chartData;
        
        if (!chartData || !chartData.labels || !chartData.data) {
            console.error('Invalid chart data:', chartData);
            return;
        }
        
        if (customerChart) {
            customerChart.destroy();
        }
        
        customerChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Pelanggan (orang)',
                    data: chartData.data,
                    backgroundColor: '#2C82FF',
                    borderColor: 'rgba(0, 0, 0, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('id-ID') + ' orang';
                            }
                        },
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        console.log('Chart initialized successfully');
    }
    
    initRevenueChart();
    
    $wire.$watch('chartData', () => {
        console.log('Chart data changed, re-initializing...');
        setTimeout(() => initRevenueChart(), 100);
    });
</script>
@endscript