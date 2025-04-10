document.addEventListener('DOMContentLoaded', function () {
    const popupClicks = promoBannerData.popup_clicks;
    const tickerClicks = promoBannerData.ticker_clicks;
    const bannerHeaderclick = promoBannerData.banner_header_clicks;

    new Chart(document.getElementById('popupChart'), {
        type: 'pie',
        data: {
            labels: ['Clicks', 'Other'],
            datasets: [{
                data: [popupClicks, Math.max(1, popupClicks * 0.25)],
                backgroundColor: ['#0073aa', '#e0e0e0'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12 }
                }
            }
        }
    });

    new Chart(document.getElementById('tickerChart'), {
        type: 'pie',
        data: {
            labels: ['Clicks', 'Other'],
            datasets: [{
                data: [tickerClicks, Math.max(1, tickerClicks * 0.25)],
                backgroundColor: ['#46b450', '#e0e0e0'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12 }
                }
            }
        }
    });

    new Chart(document.getElementById('bannerHeader'), {
        type: 'pie',
        data: {
            labels: ['Clicks', 'Other'],
            datasets: [{
                data: [bannerHeaderclick, Math.max(1, bannerHeaderclick * 0.25)],
                backgroundColor: ['#dc3232', '#e0e0e0'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12 }
                }
            }
        }
    });
});
