import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    console.log('Departments page loaded');
    // Delete Modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('deleteName').textContent = this.dataset.name;
            document.getElementById('deleteForm').action = this.dataset.action;
            deleteModal.show();
        });
    });

    // DONT APPLY YET
    // ── Search ──
    const searchInput = document.getElementById('searchInput');
    const tbody = document.getElementById('tableBody');
    const rowCount = document.getElementById('rowCount');
 
    function updateCount() {
        const visible = tbody.querySelectorAll('tr:not([style*="display: none"]):not(#emptyRow)').length;
        rowCount.textContent = visible + ' hasil';
    }
 
    // Search logic
    const searchForm  = document.getElementById('searchForm');

    let searchTimeout;
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 400); // delay 400ms agar tidak submit setiap ketikan
    });

    // Client Side Search (optional, karena sudah pakai server-side search)
    // searchInput.addEventListener('input', function () {
    //     const searchQuery = this.value.toLowerCase().trim();
    //     let visibleCount = 0;
 
    //     tbody.querySelectorAll('tr:not(#emptyRow)').forEach(row => {
    //         const text = row.textContent.toLowerCase();
    //         const show = !searchQuery || text.includes(searchQuery);
    //         row.style.display = show ? '' : 'none';
    //         if (show) visibleCount++;
    //     });
 
    //     // Empty state saat search
    //     let noResult = document.getElementById('noResult');
    //     if (visibleCount === 0 && searchQuery) {
    //         if (!noResult) {
    //             noResult = document.createElement('tr');
    //             noResult.id = 'noResult';
    //             noResult.innerHTML = `<td colspan="6" class="text-center py-4 text-muted" style="font-size:13px">Tidak ada hasil untuk "<strong>${searchQuery}</strong>"</td>`;
    //             tbody.appendChild(noResult);
    //         } else {
    //             noResult.querySelector('td').innerHTML = `Tidak ada hasil untuk "<strong>${searchQuery}</strong>"`;
    //             noResult.style.display = '';
    //         }
    //     } else if (noResult) {
    //         noResult.style.display = 'none';
    //     }
 
    //     updateCount();
    // });
 
    updateCount();
 
    // ── Sortable Table ──
    const headers = document.querySelectorAll('th.sortable');
    let currentCol = -1;
    let currentDir = 'asc';
 
    headers.forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
 
            // Check current sort state (asc or desc)
            if (currentCol === col) {
                currentDir = currentDir === 'asc' ? 'desc' : 'asc';
            } else {
                currentCol = col;
                currentDir = 'asc';
            }
 
            // Update header column styles based on sort state
            headers.forEach(h => h.classList.remove('asc', 'desc'));
            th.classList.add(currentDir);
 
            // Sort rows
            const rows = Array.from(tbody.querySelectorAll('tr:not(#emptyRow):not(#noResult)'));
            rows.sort((a, b) => {
                const aText = a.cells[col]?.textContent.trim().toLowerCase() ?? '';
                const bText = b.cells[col]?.textContent.trim().toLowerCase() ?? '';
 
                // Numeric sort untuk kolom #
                if (col === 0 || col === 3) {
                    return currentDir === 'asc'
                        ? (parseInt(aText) || 0) - (parseInt(bText) || 0)
                        : (parseInt(bText) || 0) - (parseInt(aText) || 0);
                }
 
                return currentDir === 'asc'
                    ? aText.localeCompare(bText, 'id')
                    : bText.localeCompare(aText, 'id');
            });
 
            rows.forEach(row => tbody.appendChild(row));
            updateCount();
        });
    });

    // Confirm delete dengan Enter
    document.getElementById('deleteModal').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('deleteForm').submit();
        }
    });
});