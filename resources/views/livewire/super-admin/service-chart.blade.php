<div class="bg-white rounded-xl shadow-md border border-gray-200">
    <div class="p-4 border-b border-gray-200 shrink-0 flex justify-between items-center">
        <h3 class="text-base font-semibold text-clipzo-dark">Layanan Terpopuler</h3>
        <select wire:model.live="period" 
                class="text-xs border-gray-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-gray-200">
            <option value="current_month">Bulan Ini</option>
            <option value="3_months">3 Bulan Terakhir</option>
            <option value="6_months">6 Bulan Terakhir</option>
            <option value="year">Tahun Ini</option>
        </select>
    </div>
    
    <div class="flex-grow p-4 min-h-0" wire:ignore>
        <canvas id="servicesChart"></canvas>
    </div>
</div>

@script
<script>
    let servicesChart = null;
    
    function initServicesChart() {
        const ctx = document.getElementById('servicesChart');
        if (!ctx) {
            console.error('Canvas element not found');
            return;
        }
        
        const chartData = $wire.chartData;
        
        if (!chartData || !chartData.labels || !chartData.data) {
            console.error('Invalid chart data:', chartData);
            return;
        }
        
        if (servicesChart) {
            servicesChart.destroy();
        }
        
        servicesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: chartData.data,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
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
                                return 'Terjual: ' + context.parsed.y + ' kali';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            callback: function(value) {
                                return value + ' x';
                            }
                        },
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
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
        
        console.log('Service chart initialized successfully');
    }
    
    initServicesChart();
    
    $wire.$watch('chartData', () => {
        console.log('Chart data changed, re-initializing...');
        setTimeout(() => initServicesChart(), 100);
    });
</script>
@endscript