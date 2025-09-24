document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('adminTab').onclick = function() {
        showSection('adminSection');
    };
    document.getElementById('managerTab').onclick = function() {
        showSection('managerSection');
    };
    document.getElementById('customerTab').onclick = function() {
        showSection('customerSection');
    };
    document.getElementById('settingsTab').onclick = function() {
        showSection('settingsSection');
    };

    function showSection(id) {
        document.getElementById('adminSection').style.display = 'none';
        document.getElementById('managerSection').style.display = 'none';
        document.getElementById('customerSection').style.display = 'none';
        document.getElementById('settingsSection').style.display = 'none';
        document.getElementById(id).style.display = 'block';
    }

    document.getElementById('logoutBtn').onclick = function() {
        window.location.href = '../../controller/logout.php';
    };
});