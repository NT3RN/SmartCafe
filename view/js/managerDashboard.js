
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    const addMenuItemButton = document.getElementById('add-menu-item');
    const restockItemButton = document.getElementById('restock-item');
    tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const tab = button.getAttribute('data-tab');
            showTab(tab);
        });
    });
    function showTab(tab) {
        tabContents.forEach(function (content) {
            content.classList.remove('active');
        });
        const activeTab = document.getElementById(tab);
        activeTab.classList.add('active');
        tabButtons.forEach(function (button) {
            button.classList.remove('active');
        });
        document.querySelector(`.tab-button[data-tab="${tab}"]`).classList.add('active');
    }
    function addMenuItem() {
        const name = prompt("Enter item name:");
        const description = prompt("Enter item description:");
        const price = prompt("Enter item price:");
        if (!name || !price) return;
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/SmartCafe/controller/menuController.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Menu item added successfully!");
                location.reload();
            } else if (xhr.readyState === 4) {
                alert("Error: " + xhr.responseText);
            }
        };
        xhr.send(JSON.stringify({ 
            name, 
            description, 
            price: parseFloat(price) 
        }));
    }
    function restockItem(menu_item_id) {
        const quantity = prompt("Enter quantity to restock:");
        if (!quantity) return;
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/SmartCafe/controller/inventoryController.php?action=receive&menu_item_id=" + menu_item_id, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Item restocked successfully!");
                location.reload();
            } else if (xhr.readyState === 4) {
                alert("Error: " + xhr.responseText);
            }
        };
        xhr.send(JSON.stringify({ quantity: parseInt(quantity) }));
    }
    function updateOrderStatus(order_id) {
        const status = prompt("Enter new status (Pending, Completed, Cancelled):");
        if (!status) return;
        const xhr = new XMLHttpRequest();
        xhr.open("PATCH", "/SmartCafe/controller/ordersController.php?id=" + order_id, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Order status updated successfully!");
                location.reload();
            } else if (xhr.readyState === 4) {
                alert("Error: " + xhr.responseText);
            }
        };
        xhr.send(JSON.stringify({ to: status }));
    }
    showTab('menu');
});
