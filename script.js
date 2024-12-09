document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('dataTable');
    const headers = table.querySelectorAll('thead th');
    const rows = table.querySelector('tbody').rows;


    searchInput.addEventListener('keyup', () => {
        const filter = searchInput.value.toLowerCase();
        for (let row of rows) {
            const cells = row.getElementsByTagName('td');
            let rowText = '';
            for (let cell of cells) {
                rowText += cell.textContent.toLowerCase();
            }
            row.style.display = rowText.includes(filter) ? '' : 'none';
        }
    });


    headers.forEach((header, index) => {
        header.addEventListener('click', () => {
            const order = header.dataset.order === 'asc' ? 'desc' : 'asc';
            header.dataset.order = order;

            const sortedRows = Array.from(rows).sort((a, b) => {
                const cellA = a.cells[index].textContent.trim();
                const cellB = b.cells[index].textContent.trim();

                if (isNaN(cellA) || isNaN(cellB)) {
                    return order === 'asc'
                        ? cellA.localeCompare(cellB)
                        : cellB.localeCompare(cellA);
                } else {
                    return order === 'asc'
                        ? Number(cellA) - Number(cellB)
                        : Number(cellB) - Number(cellA);
                }
            });

            for (let row of sortedRows) {
                table.querySelector('tbody').appendChild(row);
            }


            headers.forEach(h => h.textContent = h.textContent.replace(/[\u25B2\u25BC]/g, ''));
            header.textContent += order === 'asc' ? ' \u25B2' : ' \u25BC';
        });
    });
});

