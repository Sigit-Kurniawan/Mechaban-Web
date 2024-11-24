document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.querySelector('.report-form');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const reportType = document.getElementById('report_type');

    // Set default dates based on report type
    reportType.addEventListener('change', function() {
        const today = new Date();
        const startOfDay = new Date(today.setHours(0, 0, 0, 0));

        switch(this.value) {
            case 'daily':
                startDate.value = formatDate(startOfDay);
                endDate.value = formatDate(startOfDay);
                break;
            case 'weekly':
                const weekAgo = new Date(startOfDay);
                weekAgo.setDate(weekAgo.getDate() - 7);
                startDate.value = formatDate(weekAgo);
                endDate.value = formatDate(startOfDay);
                break;
            case 'monthly':
                const monthAgo = new Date(startOfDay);
                monthAgo.setMonth(monthAgo.getMonth() - 1);
                startDate.value = formatDate(monthAgo);
                endDate.value = formatDate(startOfDay);
                break;
            case 'annual':
                const yearAgo = new Date(startOfDay);
                yearAgo.setFullYear(yearAgo.getFullYear() - 1);
                startDate.value = formatDate(yearAgo);
                endDate.value = formatDate(startOfDay);
                break;
        }
    });

    // Validate date range
    reportForm.addEventListener('submit', function(e) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);

        if (start > end) {
            e.preventDefault();
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }
    });

    // Helper function to format date as YYYY-MM-DD
    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }
});