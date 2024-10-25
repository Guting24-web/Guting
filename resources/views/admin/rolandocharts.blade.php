@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    <br>
    <h2>Dynamic Charts - Categories, Products</h2>
    <br>
    <div class="row d-flex align-items-start">
        <div class="col-md-6">
            <h4>Products per Category</h4>
            <h5 class="text-center">Number of Products per Category</h5>
            <div style="height: 300px;">
                <canvas id="productBarChart" class="chart" style="height: 100%;"></canvas>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <h4>Category Distribution</h4>
            <h6 class="text-center" style="user-select: none;">Category Distribution</h6> <!-- New header for pie chart -->
            <div style="height: 350px; display: flex; justify-content: center; align-items: center;">
                <canvas id="categoryPieChart" class="chart" style="height: 100%; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    // Register the datalabels plugin
    Chart.register(ChartDataLabels);

    // Pie Chart Configuration
    const pieCtx = document.getElementById('categoryPieChart').getContext('2d');
    const productsCount = @json($categories->pluck('products_count'));
    const totalProducts = productsCount.reduce((sum, count) => sum + count, 0);

    const colors = [
        'rgba(102, 16, 242, 1)',
        'rgba(220, 53, 69, 1)',
        'rgba(255, 206, 86, 1)',
    ];

    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: @json($categories->pluck('name')),
            datasets: [{
                label: 'Category Distribution',
                data: productsCount,
                backgroundColor: colors,
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 2,
                hoverBackgroundColor: colors.map(color => {
                    return color.replace(/, 1\)/, ', 0.8)');
                })
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const category = tooltipItem.label;
                            const count = tooltipItem.raw;
                            return `${category}: ${count} products`;
                        }
                    },
                    position: 'nearest',
                },
                datalabels: {
                    color: 'white',
                    font: {
                        weight: 'bold',
                        size: 16,
                    },
                    anchor: 'end',
                    align: 'start',
                    formatter: (value) => {
                        let percentage = ((value / totalProducts) * 100).toFixed(1);
                        return percentage + '%';
                    }
                }
            },
            responsive: true,
            layout: {
                padding: {
                    right: 50,
                }
            }
        }
    });

    // Custom legend for category names as circles
    const legendContainer = document.createElement('div');
    legendContainer.style.position = 'absolute';
    legendContainer.style.top = '10px';
    legendContainer.style.right = '10px';
    legendContainer.style.display = 'flex';
    legendContainer.style.flexDirection = 'column';

    const categories = @json($categories->pluck('name'));

    categories.forEach((category, index) => {
        const legendItem = document.createElement('div');
        legendItem.style.display = 'flex';
        legendItem.style.alignItems = 'center';
        legendItem.style.marginBottom = '5px';

        const colorBox = document.createElement('span');
        colorBox.style.backgroundColor = colors[index % colors.length];
        colorBox.style.borderRadius = '50%';
        colorBox.style.width = '15px';
        colorBox.style.height = '15px';
        colorBox.style.marginRight = '5px';

        const categoryName = document.createElement('span');
        categoryName.innerText = category;
        categoryName.style.color = 'black';

        legendItem.appendChild(colorBox);
        legendItem.appendChild(categoryName);
        legendContainer.appendChild(legendItem);

        // Add hover effect for legend items
        legendItem.addEventListener('mouseenter', () => {
            pieChart.data.datasets[0].backgroundColor = colors.map((color, i) => {
                return i === index ? color : 'rgba(200, 200, 200, 0.5)';
            });
            pieChart.update();
        });

        legendItem.addEventListener('mouseleave', () => {
            pieChart.data.datasets[0].backgroundColor = colors;
            pieChart.update();
        });
    });

    document.getElementById('categoryPieChart').parentNode.appendChild(legendContainer);

    // Bar Chart Configuration
    const barCtx = document.getElementById('productBarChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: @json($categories->pluck('name')),
            datasets: [{
                label: 'Number of Products',
                data: productsCount,
                backgroundColor: 'rgba(38, 143, 255, 1)', 
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                hoverBackgroundColor: 'rgba(54, 162, 235, 0.8)', 
                hoverBorderColor: 'rgba(54, 162, 235, 0.8)' 
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(1);
                        },
                        stepSize: 0.2
                    }
                }
            },
            plugins: {
                tooltip: {
                    enabled: true,
                },
                datalabels: {
                    anchor: 'center',
                    align: 'center',
                    color: 'white',
                    formatter: (value) => {
                        return value;
                    }
                }
            },
            responsive: true
        }
    });

    barChart.canvas.addEventListener('click', (event) => {
    const activePoints = barChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, false);
    if (activePoints.length) {
        const { index } = activePoints[0];
        const categoryName = barChart.data.labels[index];
        const productCount = barChart.data.datasets[0].data[index];
        
        // Implement your desired action here, for example:
        alert(`You selected: ${categoryName}\nNumber of Products: ${productCount}`);
        
        // Optionally, you can navigate to another page or trigger another function
        // window.location.href = `/products/${categoryName}`;
    }
});
</script>


<style>
    /* Centering the title for pie chart */
    #categoryPieChart + h4, #categoryPieChart + h5 {
        text-align: center; 
        user-select: none; 
        margin-top: 20px; 
    }
</style>
@endsection
