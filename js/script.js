document.addEventListener('DOMContentLoaded', function() {

    // sidebar links
    const dashboardLink = document.querySelector('.dashboard');
    const manageLink = document.querySelector('.manage');
    const paymentsLink = document.querySelector('.payments');

    
    // containers
    const dashboardContainer = document.getElementById('dashboard-con');
    const manageContainer = document.getElementById('manage-con');
    const paymentsContainer = document.getElementById('payments-con');
 
    //  show only the dashboard and hide others
    manageContainer.style.display = 'none';
    paymentsContainer.style.display = 'none';
 
    // dashboard
    dashboardLink.addEventListener('click', function(e) {
        e.preventDefault(); 
        dashboardContainer.style.display = 'block';
        manageContainer.style.display = 'none';
       paymentsContainer.style.display='none';
    });
 
    //management
    manageLink .addEventListener('click', function(e) {
        e.preventDefault();
        dashboardContainer.style.display = 'none';
        manageContainer.style.display = 'block';
        paymentsContainer.style.display='none';
    });
 
    //payments
    paymentsLink .addEventListener('click', function(e) {
        e.preventDefault();
        dashboardContainer.style.display = 'none';
        manageContainer.style.display = 'none';
        paymentsContainer.style.display='block';
    });
 
     // live date and time   
     function updateClock() {
        var now = new Date();
        var datetime = now.toLocaleString();
        document.getElementById("datetime").innerHTML = datetime;
        document.getElementById("datetime1").innerHTML = datetime;
    
    }
    setInterval(updateClock, 1000);
    updateClock();
    
 });
 
// view modal
const viewModal = document.getElementById('modalview');
viewModal.addEventListener('shown.bs.modal', () => {
  
});

// edit modal
const editModal = document.getElementById('editmodal');
editModal.addEventListener('shown.bs.modal', () => {
 
});

