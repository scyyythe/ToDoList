 
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

//logout
function showLogoutModal() {
  document.getElementById('logoutModal').style.display = 'flex';
}

function hideLogoutModal() {
  document.getElementById('logoutModal').style.display = 'none';
}

function confirmLogout() {
  window.location.href = 'logout.php'; 
}


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
function showCompletedTask() {
  document.getElementById('completed-task').style.display = 'block'; 
  document.getElementById('overlay').style.display = 'block'; 
}

function hideCompletedTask() {
  document.getElementById('completed-task').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}


document.getElementById('overlay').onclick = hideCompletedTask;

function showPopup(title, folderName, note, dueTime, imagePath) {

  document.getElementById('popupTitle').innerText = title;
  document.getElementById('popupNote').innerText = note;
  document.getElementById('folderName').innerText = folderName;
  
 
  const popupImage = document.getElementById('popupImage');
  const leftPop = document.querySelector('.leftPop');
  const rightPop = document.querySelector('.rightPop');

  if (imagePath) {
      popupImage.src = imagePath;
      popupImage.style.display = 'block';
      leftPop.style.display = 'block';
      rightPop.style.width = '70%';
  } else {
      popupImage.style.display = 'none';
      leftPop.style.display = 'none';
      rightPop.style.width = '100%';
  }


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

  // Display the popup
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


//preview image
function previewImage(event) {
  const imagePreview = document.getElementById('image-preview');
  const file = event.target.files[0];
  
  if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
          imagePreview.src = e.target.result;
      };
      reader.readAsDataURL(file);
  }
}

//edit
function openModal(note_id, title, note, deadline, image) {
  console.log("Opening modal with image:", image);

  document.getElementById('note_id').value = note_id;
  document.getElementById('title').value = title;
  document.getElementById('note').value = note;
  document.getElementById('modalDeadline').value = deadline;

  const imagePreview = document.getElementById('image-preview');
  if (imagePreview) {
      if (image) {
          console.log('Setting image preview to:', image);
          const timestamp = new Date().getTime();
          imagePreview.src = image + '?t=' + timestamp; 
          document.getElementById('task-image').disabled = false;
      } else {
          console.log('No image, clearing preview');
          imagePreview.src = '';  
          document.getElementById('task-image').disabled = true; 
      }
  }

  document.getElementById('popupOverlay').style.display = 'block';
  document.getElementById('editNoteModal').style.display = 'block';
}


// Function to close the modal
function closeModal() {
  document.getElementById('popupOverlay').style.display = 'none';
  document.getElementById('editNoteModal').style.display = 'none';
}


//show folder CONTENT IF CLIKC
document.querySelectorAll('.folder-icon').forEach(folder => {
  folder.addEventListener('click', function() {
      const folderId = this.getAttribute('data-folder-id');  
      document.getElementById('folder-id').value = folderId;  
      document.getElementById('folder-section').style.display = 'none';
      document.getElementById('folder-content').style.display = 'block';
  });
});
//back to folder section
document.getElementById('back-btn').addEventListener('click', function() {
  document.getElementById('folder-content').style.display = 'none';
  document.getElementById('folder-section').style.display = 'block';
});

function handleDeleteResponse(response) {
  const alertMessage = document.getElementById('d_alert_message');
  const alertBox = document.getElementById('d_alert');
  const alertButton = document.getElementById('d_alert_ok');
  
  if (response.message && response.status) {
    alertMessage.textContent = response.message;
    alertBox.style.display = 'flex';
    
    alertButton.onclick = function() {
      console.log('OK button clicked');
      alertBox.style.display = 'none'; 
    };
  } else {
    console.error('Invalid response:', response); 
  }
}

function handleDeleteResponse(response) {
  const alertMessage = document.getElementById('d_alert_message');
  const alertBox = document.getElementById('d_alert');
  const alertButton = document.getElementById('d_alert_ok');
  const deleteContent = document.getElementById('deleteContent'); 

  if (response.message && response.status) {
    alertMessage.textContent = response.message;
    alertBox.style.display = 'flex'; 
    
    deleteContent.style.display = 'none';

    alertButton.onclick = function() {
      console.log('OK button clicked');
      alertBox.style.display = 'none';
    };
  } else {
    console.error('Invalid response:', response); 
  }
}

function showDeleteAlert() {
  const deleteContent = document.getElementById('deleteContent');
  deleteContent.style.display = 'flex';  
  
  document.getElementById('deleteConfirm').onclick = function() {
    console.log('Confirm button clicked'); 
    
    const form = document.getElementById('delete-folder-form');
    const formData = new FormData(form);
    
    fetch(form.action, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())  
    .then(data => {
      console.log('Response Data: ', data); 
      handleDeleteResponse(data);  
    })
    .catch(error => {
      console.error('Error:', error); 
    });
    
  };

  document.getElementById('deleteCancel').onclick = function() {
    deleteContent.style.display = 'none';
  };
}





//GET NOTES ON THE SPECIFIC FOLDER_ID
function getFolderId(folderElement) {
  var folderId = folderElement.getAttribute('data-folder-id');
  document.getElementById('folder-id').value = folderId;
  console.log("Folder ID: ", folderId);

  fetch('include/getNotesByFolder.php', {
    method: 'POST',
    body: new URLSearchParams({
        'folder_id': folderId
    })
  })
  .then(response => {
      if (!response.ok) {
          throw new Error('Network response was not ok');
      }
      return response.text();
  })
  .then(responseText => {
      try {
          const data = JSON.parse(responseText);  
          console.log('Response Data: ', data);
          
          const notesContainer = document.querySelector('.all-task-container');
          if (notesContainer) {
              notesContainer.innerHTML = ''; 

              if (data.tasks && data.tasks.length > 0) {
                  data.tasks.forEach(task => {
                      const taskElement = document.createElement('div');
                      taskElement.classList.add('task-box');

                      taskElement.innerHTML = `
                          <div class="check-task">      
                              <form method="POST" action="dashboard.php">
                                  <input type="hidden" name="note_id" value="${task.task_id}">
                                  <button type="submit" name="complete_note" class="complete-note-btn mark-complete-btn">
                                      <i class='bx bx-check-circle'></i>
                                  </button>
                              </form>
                          </div>
                          <div class="task-box-top">
                              <h3>${task.task_name}</h3>
                              <p>
                                  ${task.task_description.split(' ').slice(0, 30).join(' ')}${task.task_description.split(' ').length > 30 ? '...' : ''} 
                              </p>
                          </div>
                          <div class="task-box-bottom">
                              <div class="task-due">
                                  <p>Deadline: <span class="countdown" data-deadline="${task.task_deadline}"></span></p><br>
                              </div>
                              <div class="task-actions">
                                  <form method="POST" action="dashboard.php" onsubmit="return confirm('Are you sure you want to delete this note?');">
                                      <input type="hidden" name="note_id" value="${task.task_id}">
                                      <button type="submit" class="delete-note-btn" name="deleteNote">
                                          <i class='bx bxs-trash'></i>
                                      </button>
                                  </form>
                                  <form method="POST" action="#" onsubmit="event.preventDefault(); openModal(${task.task_id}, '${task.task_name}', '${task.task_description}', '${task.task_deadline}','${task.task_image}' )">
                                      <button type="submit">
                                          <i class='bx bxs-edit'></i>
                                      </button>
                                  </form>
                              </div>
                          </div>
                      `;
                      const taskBoxTop = taskElement.querySelector('.task-box-top');
                      const taskBoxBottom = taskElement.querySelector('.task-box-bottom');
                      taskBoxTop.onclick = () => showPopup(task.task_name, task.task_folder, task.task_description, task.task_deadline, task.task_image);
                      taskBoxBottom.onclick = () => showPopup(task.task_name, task.task_folder, task.task_description, task.task_deadline, task.task_image);

                      notesContainer.appendChild(taskElement);
                  });
              } else {
                  notesContainer.innerHTML = '<p>No tasks available in this folder.</p>';
              }
          } else {
              console.error('Notes container not found');
          }
      } catch (error) {
          console.error('Error parsing JSON:', error);
          console.error('Response Text:', responseText);
      }
  })
  .catch(error => console.error('Error:', error));
}


