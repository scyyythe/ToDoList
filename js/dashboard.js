// Get references to the links and sections
const dashboardLink = document.getElementById('dashboard-link');
const myTaskLink = document.getElementById('my-task-link');
const settingLink = document.getElementById('settings-link'); 

const dashboardSection = document.getElementById('dashboard');
const myTaskSection = document.getElementById('my-task');
const settingsSection = document.getElementById('settings'); 

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
        tab1Content.style.display = 'block';
        tab2Content.style.display = 'none'; // Hide Tab 2
    } else {
        tab1Button.classList.remove('active');
        tab2Button.classList.add('active');
        tab1Content.style.display = 'none';
        tab2Content.style.display = 'block'; // Show Tab 2
    }
}

// Add event listeners to the tab buttons
tab1Button.addEventListener('click', () => switchTab('tab1'));
tab2Button.addEventListener('click', () => switchTab('tab2'));

// Add click event listener to the "My Task" link
myTaskLink.addEventListener('click', function() {
    // Show "My Task" section and hide others
    dashboardSection.style.display = 'none';
    myTaskSection.style.display = 'block';
    settingsSection.style.display = 'none';

    // Automatically switch to Tab 1 and hide Tab 2 when "My Task" is clicked
    switchTab('tab1');
});

// Add click event listeners to other sections
dashboardLink.addEventListener('click', function() {
    dashboardSection.style.display = 'block';
    myTaskSection.style.display = 'none';
    settingsSection.style.display = 'none';
});

settingLink.addEventListener('click', function() {
    dashboardSection.style.display = 'none';
    myTaskSection.style.display = 'none';
    settingsSection.style.display = 'block';
});


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


// tab 2 folder

const folderTasks = {};

// Add folder functionality
document.getElementById('add-folder-btn').addEventListener('click', function() {
  const folderName = document.getElementById('folder-name').value;
  if (folderName) {
    const folderId = folderName.toLowerCase().replace(/\s+/g, '-'); // Generate ID from folder name
    const folderContainer = document.createElement('div');
    folderContainer.classList.add('folder');
    folderContainer.setAttribute('data-folder-id', folderId); // Add unique folder ID

    const folderIcon = document.createElement('img');
    folderIcon.src = 'img/icons8-folder-64.png'; // Replace with your folder icon source
    folderIcon.alt = folderName;

    const folderText = document.createElement('p');
    folderText.innerText = folderName;

    folderContainer.appendChild(folderIcon);
    folderContainer.appendChild(folderText);

    // Append the new folder to the folders container
    document.getElementById('folders-container').appendChild(folderContainer);

    // Initialize the folder's task list
    folderTasks[folderId] = [];

    // Clear input field after adding folder
    document.getElementById('folder-name').value = '';
  }
});

// Event delegation: Handle clicks on folders
document.getElementById('folders-container').addEventListener('click', function(event) {
  const folder = event.target.closest('.folder'); // Find the clicked folder element
  if (folder) {
    const folderId = folder.getAttribute('data-folder-id');
    showFolderTasks(folderId); // Show tasks for the clicked folder
  }
});

// Show tasks based on folder ID
function showFolderTasks(folderId) {
  const taskListContainer = document.getElementById('task-list');
  taskListContainer.innerHTML = ''; // Clear existing tasks

  const tasks = folderTasks[folderId] || []; // Get tasks for the selected folder

  tasks.forEach(task => {
    const taskItem = document.createElement('div');
    taskItem.classList.add('dash-list'); // Add the dash-list class to each task

    // Left part (title and description)
    const taskInfo = document.createElement('div');
    taskInfo.classList.add('left-dash-list');
    taskInfo.innerHTML = `<h3>${task.title}</h3><br><p>${task.description}</p>`;

    // Right part (due date and actions)
    const taskMeta = document.createElement('div');
    taskMeta.classList.add('right-dash-list');
    taskMeta.innerHTML = `
      <p>Due Date: ${task.dueDate}</p><br>
      <a href="#" class="edit-list"><b>Edit List</b></a>
      <i class='bx bx-check-circle'></i>
      <i class='bx bxs-trash'></i>
    `;

    taskItem.appendChild(taskInfo);
    taskItem.appendChild(taskMeta);

    taskListContainer.appendChild(taskItem); // Append each task to the task list container
  });

  // Show the add-task form for the current folder
  document.getElementById('folder-content').setAttribute('data-current-folder', folderId);

  // Hide folder view and show task list view
  document.getElementById('folder-section').style.display = 'none';
  document.getElementById('folder-content').style.display = 'block';
}

// Add task functionality
document.getElementById('add-task-btn').addEventListener('click', function() {
  const currentFolderId = document.getElementById('folder-content').getAttribute('data-current-folder');
  
  const taskTitle = document.getElementById('task-title').value;
  const taskDescription = document.getElementById('task-description').value;
  const taskDueDate = document.getElementById('task-due-date').value;

  if (taskTitle && taskDescription && taskDueDate) {
    // Add the new task to the folder's task list
    const newTask = {
      title: taskTitle,
      description: taskDescription,
      dueDate: taskDueDate
    };

    folderTasks[currentFolderId].push(newTask);

    // Clear the input fields after adding the task
    document.getElementById('task-title').value = '';
    document.getElementById('task-description').value = '';
    document.getElementById('task-due-date').value = '';

    // Refresh the task list for the current folder
    showFolderTasks(currentFolderId);
  }
});

// Back button handler to return to folder view
document.getElementById('back-btn').addEventListener('click', function() {
  document.getElementById('folder-section').style.display = 'block';
  document.getElementById('folder-content').style.display = 'none';
});

  // Delete folder button (add functionality as needed)
  document.getElementById('delete-folder-btn').addEventListener('click', function() {
    alert('Folder deleted');
  });
  