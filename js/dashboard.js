 
 // Current Date and Time
 function getFormattedDate() {
  const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];

  const date = new Date();
  const dayName = days[date.getDay()];
  const day = String(date.getDate()).padStart(2, '0');
  const monthName = months[date.getMonth()];
  const year = date.getFullYear();

  return `${dayName} ${day} ${monthName} ${year}`;
}

document.getElementById('live-date').innerText = getFormattedDate();

// Show All Tasks Section
const allTaskLink = document.getElementById('allTask-link');
const viewTaskContainer = document.getElementById('viewtaskContainer');

allTaskLink.addEventListener('click', function() {
  dashboardSection.style.display = 'none';
  myTaskSection.style.display = 'none';
  settingsSection.style.display = 'none';
  viewTaskContainer.style.display = 'block';
});

// Section Display Logic
const dashboardLink = document.getElementById('dashboard-link');
const myTaskLink = document.getElementById('my-task-link');
const settingLink = document.getElementById('settings-link');

const dashboardSection = document.getElementById('dashboard');
const myTaskSection = document.getElementById('my-task');
const settingsSection = document.getElementById('settings');

myTaskLink.addEventListener('click', function() {
  dashboardSection.style.display = 'none';
  myTaskSection.style.display = 'block';
  settingsSection.style.display = 'none';
  viewTaskContainer.style.display = 'none';
  switchTab('tab1');
});

dashboardLink.addEventListener('click', function() {
  dashboardSection.style.display = 'block';
  myTaskSection.style.display = 'none';
  settingsSection.style.display = 'none';
  viewTaskContainer.style.display = 'none';
});

settingLink.addEventListener('click', function() {
  dashboardSection.style.display = 'none';
  myTaskSection.style.display = 'none';
  settingsSection.style.display = 'block';
  viewTaskContainer.style.display = 'none';
});

// Tab Switching Logic
const tab1Button = document.getElementById('tab1-button');
const tab2Button = document.getElementById('tab2-button');
const tab1Content = document.getElementById('tab1-content');
const tab2Content = document.getElementById('tab2-content');

function switchTab(tab) {
  if (tab === 'tab1') {
    tab1Button.classList.add('active');
    tab2Button.classList.remove('active');
    tab1Content.style.display = 'block';
    tab2Content.style.display = 'none';
  } else {
    tab1Button.classList.remove('active');
    tab2Button.classList.add('active');
    tab1Content.style.display = 'none';
    tab2Content.style.display = 'block';
  }
}

tab1Button.addEventListener('click', () => switchTab('tab1'));
tab2Button.addEventListener('click', () => switchTab('tab2'));

// Show Completed Tasks
function showCompletedTask() {
  document.getElementById("completed-task").style.display = "block";
  document.body.classList.add("show-popup");
}

function hideCompletedTask() {
  document.getElementById("completed-task").style.display = "none";
  document.body.classList.remove("show-popup");
}

//show folder
document.querySelectorAll('.folder-icon').forEach(folder => {
  folder.addEventListener('click', function() {
      const folderId = this.getAttribute('data-folder-id');
      // Logic to load folder content goes here
      // For example, you can make an AJAX call to fetch tasks related to this folder
      document.getElementById('folder-content').style.display = 'block';
  });
});
