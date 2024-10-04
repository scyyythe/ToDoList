// Get references to the links and sections
const dashboardLink = document.getElementById('dashboard-link');
const myTaskLink = document.getElementById('my-task-link');
const settingLink = document.getElementById('settings-link'); 


const dashboardSection = document.getElementById('dashboard');
const myTaskSection = document.getElementById('my-task');
const settingsSection = document.getElementById('settings'); 

// Add click event listeners to the links
dashboardLink.addEventListener('click', function() {
    dashboardSection.style.display = 'block';
    myTaskSection.style.display = 'none';
    settingsSection.style.display = 'none'; 
    alltaskSection.style.display = 'none'; 
});

myTaskLink.addEventListener('click', function() {
    dashboardSection.style.display = 'none';
    myTaskSection.style.display = 'block';
    settingsSection.style.display = 'none'; 
    alltaskSection.style.display = 'none'; 
});

settingLink.addEventListener('click', function() {
    dashboardSection.style.display = 'none';
    myTaskSection.style.display = 'none';
    settingsSection.style.display = 'block';
    alltaskSection.style.display = 'none'; 
});


// tab pane
// Get references to tab buttons and content
const tab1Button = document.getElementById('tab1-button');
const tab2Button = document.getElementById('tab2-button');
const tab1Content = document.getElementById('tab1-content');
const tab2Content = document.getElementById('tab2-content');

// Function to switch tabs
function switchTab(tab) {
    if (tab === 'tab1') {
        tab1Button.classList.add('active');
        tab2Button.classList.remove('active');
        tab1Content.classList.add('active');
        tab2Content.classList.remove('active');
    } else {
        tab1Button.classList.remove('active');
        tab2Button.classList.add('active');
        tab1Content.classList.remove('active');
        tab2Content.classList.add('active');
    }
}

// Add event listeners to the tab buttons
tab1Button.addEventListener('click', () => switchTab('tab1'));
tab2Button.addEventListener('click', () => switchTab('tab2'));


// view completed
function showCompletedTask() {
    document.getElementById("completed-task").style.display = "block";
    document.body.classList.add("show-popup"); 
}

function hideCompletedTask() {
    document.getElementById("completed-task").style.display = "none";
    document.body.classList.remove("show-popup");
}
// view all task sa My Task Dashboard Tab
const allTaskLink = document.getElementById('allTask-link');
const alltaskSection=document.getElementById('viewtaskContainer');

allTaskLink.addEventListener('click', function() {
    dashboardSection.style.display = 'none';
    myTaskSection.style.display = 'none';
    settingsSection.style.display = 'none'; 
    alltaskSection.style.display = 'block'; 
});
