document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('search').addEventListener('input', function(e) {
        const query = e.target.value;
        const searchUrl = this.getAttribute('data-search-url');

        fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('table-container').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
    });
});
