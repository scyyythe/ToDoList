 
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


const allTaskLink = document.getElementById('allTask-link');
const viewTaskContainer = document.getElementById('viewtaskContainer');
const dashboardLink = document.getElementById('dashboard-link');
const myTaskLink = document.getElementById('my-task-link');
const settingLink = document.getElementById('settings-link');

const dashboardSection = document.getElementById('dashboard');
const myTaskSection = document.getElementById('my-task');
const settingsSection = document.getElementById('settings');

function clearActiveLinks() {
  dashboardLink.classList.remove('active');
  myTaskLink.classList.remove('active');
  settingLink.classList.remove('active');
}

allTaskLink.addEventListener('click', function() {
  clearActiveLinks();
  allTaskLink.classList.add('active');
  dashboardSection.style.display = 'none';
  myTaskSection.style.display = 'none';
  settingsSection.style.display = 'none';
  viewTaskContainer.style.display = 'block';
});

myTaskLink.addEventListener('click', function() {
  clearActiveLinks();
  myTaskLink.classList.add('active');
  dashboardSection.style.display = 'none';
  myTaskSection.style.display = 'block';
  settingsSection.style.display = 'none';
  viewTaskContainer.style.display = 'none';
  switchTab('tab1');
});

dashboardLink.addEventListener('click', function() {
  clearActiveLinks();
  dashboardLink.classList.add('active');
  dashboardSection.style.display = 'block';
  myTaskSection.style.display = 'none';
  settingsSection.style.display = 'none';
  viewTaskContainer.style.display = 'none';
});

settingLink.addEventListener('click', function() {
  clearActiveLinks();
  settingLink.classList.add('active');
  dashboardSection.style.display = 'none';
  myTaskSection.style.display = 'none';
  settingsSection.style.display = 'block';
  viewTaskContainer.style.display = 'none';
});


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


// Show the completed tasks section
function showCompletedTask() {
  document.getElementById('completed-task').style.display = 'block';
}

// Hide the completed tasks section
function hideCompletedTask() {
  document.getElementById('completed-task').style.display = 'none';
}


//show folder
document.querySelectorAll('.folder-icon').forEach(folder => {
  folder.addEventListener('click', function() {
      const folderId = this.getAttribute('data-folder-id');  
      document.getElementById('folder-id').value = folderId;  
      document.getElementById('folder-section').style.display = 'none';
      document.getElementById('folder-content').style.display = 'block';
  });
});




document.getElementById('back-btn').addEventListener('click', function() {
  
    document.getElementById('folder-section').style.display = 'block';

   
    document.getElementById('folder-content').style.display = 'none';
});

function confirmDelete() {
  const userConfirmation = confirm("Are you sure you want to delete this folder? This action cannot be undone.");

  if (userConfirmation) {
      
      document.getElementById("delete-folder-form").submit();
  }
}

// pop up note
function showPopup(title, note, dueTime) {

  document.getElementById('popupTitle').innerText = title;
  document.getElementById('popupNote').innerText = note;

  
  let [hours, minutes, seconds] = dueTime.split(':').map(Number);

  
  let currentDate = new Date();
  let deadlineDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), hours, minutes, seconds);


  let timerInterval = setInterval(function() {
      let now = new Date();
      let timeRemaining = deadlineDate - now;

      if (timeRemaining <= 0) {
          clearInterval(timerInterval); 
          document.getElementById('popupCountdown').innerText = "Deadline reached!";
      } else {
          let hoursLeft = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          let minutesLeft = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
          let secondsLeft = Math.floor((timeRemaining % (1000 * 60)) / 1000);
          
         
          document.getElementById('popupCountdown').innerText = hoursLeft + "h " + minutesLeft + "m " + secondsLeft + "s ";
      }
  }, 1000); 


  document.getElementById('notePopup').style.display = 'block';
}

function closePopup() {
  document.getElementById('notePopup').style.display = 'none';
}


//pop up trash can
function showTrashPopup() {
    document.getElementById('trashContainer').style.display = 'block';
    document.getElementById('trashContainerOverlay').style.display = 'block';
}

function closeTrashPopup() {
    document.getElementById('trashContainer').style.display = 'none';
    document.getElementById('trashContainerOverlay').style.display = 'none';
}

// Function to open the modal
function openModal() {
  document.getElementById('popupOverlay').style.display = 'block';
  document.getElementById('editNoteModal').style.display = 'block';
}

// Function to close the modal
function closeModal() {
  document.getElementById('popupOverlay').style.display = 'none';
  document.getElementById('editNoteModal').style.display = 'none';
}

