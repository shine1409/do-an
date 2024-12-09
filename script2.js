document.addEventListener('DOMContentLoaded', () => {
    const rowsPerPage = 10; 
    const table = document.getElementById('dataTable');
    const tbody = table.querySelector('tbody');
    const pagination = document.getElementById('pagination');
    
    let rows = Array.from(tbody.rows); 
    let currentPage = 1; 
    let filteredRows = rows; 


    function displayRows(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

 
        filteredRows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });
    }

  
    function setupPagination() {
        pagination.innerHTML = ''; 
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage); 


        const createButton = (text, page) => {
            const button = document.createElement('button');
            button.textContent = text;
            button.classList.add('pagination-btn');
            if (page === currentPage) button.classList.add('active');

            button.addEventListener('click', () => {
                if (page >= 1 && page <= totalPages) {
                    currentPage = page; 
                    displayRows(currentPage); 
                    setupPagination(); 
                }
            });

            return button;
        };


        const prevButton = createButton('«', currentPage - 1);
        prevButton.disabled = currentPage === 1;
        pagination.appendChild(prevButton);


        for (let i = 1; i <= totalPages; i++) {
            pagination.appendChild(createButton(i, i));
        }

   
        const nextButton = createButton('»', currentPage + 1);
        nextButton.disabled = currentPage === totalPages;
        pagination.appendChild(nextButton);
    }

  
    displayRows(currentPage);
    setupPagination();
});

document.addEventListener('DOMContentLoaded', function () {
    const actionButtons = document.querySelectorAll('.action-btn');
    
  
    actionButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation(); 

            const menu = this.nextElementSibling; 


            if (menu.style.display === 'block') {
                menu.style.display = 'none';
                this.classList.remove('active');
            } else {

                document.querySelectorAll('.action-menu').forEach(m => {
                    m.style.display = 'none';
                });
                document.querySelectorAll('.action-btn').forEach(btn => {
                    btn.classList.remove('active');
                });


                menu.style.display = 'block';
                this.classList.add('active');
            }
        });
    });

    document.addEventListener('click', function (e) {
  
        const actionMenu = document.querySelectorAll('.action-menu');
        const actionBtns = document.querySelectorAll('.action-btn');


        if (![...actionBtns].includes(e.target) && ![...actionMenu].includes(e.target) && !e.target.closest('.action-menu')) {
            actionMenu.forEach(menu => {
                menu.style.display = 'none';
            });
            actionBtns.forEach(btn => {
                btn.classList.remove('active');
            });
        }
    });
});
