// Gestion globale de l'application

document.addEventListener('DOMContentLoaded', function() {
    // Confirmations de suppression
    document.querySelectorAll('.del-link').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?')) {
                e.preventDefault();
            }
        });
    });

    // Gestion des messages de succès
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('msg')) {
        const msg = urlParams.get('msg');
        showAlert('success', msg);
    }

    if (urlParams.has('error')) {
        const error = urlParams.get('error');
        showAlert('error', error);
    }
});

// Fonction d'alerte
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-hide après 5 secondes
        setTimeout(() => {
            alertDiv.style.display = 'none';
        }, 5000);
    }
}

// Validation des formulaires
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '';
        }
    });

    return isValid;
}

// Gestion du chargement de fichiers
function handleFileUpload(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Vérifier la taille (max 50MB)
            const maxSize = 50 * 1024 * 1024;
            if (file.size > maxSize) {
                showAlert('error', 'Le fichier est trop volumineux (max 50MB)');
                this.value = '';
                return;
            }

            // Vérifier l'extension
            const allowedExtensions = ['pdf', 'docx', 'ppt', 'zip'];
            const fileName = file.name.toLowerCase();
            const fileExtension = fileName.split('.').pop();
            
            if (!allowedExtensions.includes(fileExtension)) {
                showAlert('error', 'Format de fichier non autorisé. Formats acceptés: PDF, DOCX, PPT, ZIP');
                this.value = '';
                return;
            }
        }
    });
}

// Affichage des graphiques (pour statistiques)
function createChart(canvasId, type, data, options = {}) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    // Vérifier si Chart.js est chargé
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js not loaded');
        return;
    }

    new Chart(canvas, {
        type: type,
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: options.title || ''
                }
            },
            ...options
        }
    });
}

// Pagination
function setupPagination(itemsPerPage = 10) {
    const items = document.querySelectorAll('.pageable-item');
    const totalPages = Math.ceil(items.length / itemsPerPage);
    const paginationContainer = document.getElementById('pagination');

    if (!paginationContainer || totalPages <= 1) return;

    function showPage(pageNum) {
        items.forEach((item, index) => {
            item.style.display = (index >= (pageNum - 1) * itemsPerPage && index < pageNum * itemsPerPage) ? 'block' : 'none';
        });
    }

    // Créer les boutons de pagination
    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.className = i === 1 ? 'active' : '';
        button.addEventListener('click', () => {
            document.querySelectorAll('#pagination button').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            showPage(i);
        });
        paginationContainer.appendChild(button);
    }

    showPage(1);
}

// Recherche en temps réel
function setupLiveSearch(inputId, resultsId) {
    const input = document.getElementById(inputId);
    const resultsContainer = document.getElementById(resultsId);

    if (!input || !resultsContainer) return;

    input.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length < 2) {
            resultsContainer.innerHTML = '';
            return;
        }

        // Appel AJAX pour recherche en temps réel
        fetch(`search.php?q=${encodeURIComponent(query)}&ajax=1`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = '';
                
                if (data.results.length === 0) {
                    resultsContainer.innerHTML = '<div class="no-results">Aucun résultat</div>';
                    return;
                }

                data.results.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'search-result-item';
                    div.innerHTML = `
                        <a href="view.php?id=${item.id}">
                            <strong>${item.titre}</strong>
                            <span class="type">${item.type}</span>
                        </a>
                    `;
                    resultsContainer.appendChild(div);
                });
            })
            .catch(error => console.error('Search error:', error));
    });
}

// Export de fonctions
window.App = {
    validateForm,
    handleFileUpload,
    createChart,
    setupPagination,
    setupLiveSearch,
    showAlert
};
