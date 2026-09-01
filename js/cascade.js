function openModal(id) {
        var modal = document.getElementById('modal-' + id);
        if (modal) {
            modal.classList.add('active');
        }
    }

    function closeModal(id) {
        var modal = document.getElementById('modal-' + id);
        if (modal) {
            modal.classList.remove('active');
        }
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal-backdrop')) {
            event.target.classList.remove('active');
        }
    }