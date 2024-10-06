// current na date and time

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
document.getElementById('live-date-mytask').innerText = getFormattedDate();

//e hide ang uban section then e link
const dashboardLink = document.getElementById('dashboard-link');
const myTaskLink = document.getElementById('my-task-link');
const settingLink = document.getElementById('settings-link'); 

const dashboardSection = document.getElementById('dashboard');
const myTaskSection = document.getElementById('my-task');
const settingsSection = document.getElementById('settings'); 

//tab button sa mylist section
const tab1Button = document.getElementById('tab1-button');
const tab2Button = document.getElementById('tab2-button');
const tab1Content = document.getElementById('tab1-content');
const tab2Content = document.getElementById('tab2-content');

// switch ang mga tabs
function switchTab(tab) {
    if (tab === 'tab1') {
        tab1Button.classList.add('active');
        tab2Button.classList.remove('active');
        tab1Content.style.display = 'block';
        tab2Content.style.display = 'none'; // hide tab2
    } else {
        tab1Button.classList.remove('active');
        tab2Button.classList.add('active');
        tab1Content.style.display = 'none';
        tab2Content.style.display = 'block'; //e show tab2
    }
}

tab1Button.addEventListener('click', () => switchTab('tab1'));
tab2Button.addEventListener('click', () => switchTab('tab2'));

myTaskLink.addEventListener('click', function() {
    dashboardSection.style.display = 'none';
    myTaskSection.style.display = 'block';
    settingsSection.style.display = 'none';
    switchTab('tab1');
});

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



// view completed na mga to do list POop Up
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

document.getElementById('add-folder-btn').addEventListener('click', function() {
  const folderName = document.getElementById('folder-name').value;
  if (folderName) {
    const folderId = folderName.toLowerCase().replace(/\s+/g, '-'); 
    const folderContainer = document.createElement('div');
    folderContainer.classList.add('folder');
    folderContainer.setAttribute('data-folder-id', folderId); 

    const folderIcon = document.createElement('img');
    folderIcon.src = 'img/icons8-folder-64.png'; 
    folderIcon.alt = folderName;

    const folderText = document.createElement('p');
    folderText.innerText = folderName;

    folderContainer.appendChild(folderIcon);
    folderContainer.appendChild(folderText);

    document.getElementById('folders-container').appendChild(folderContainer);


    folderTasks[folderId] = [];

    document.getElementById('folder-name').value = '';
  }
});


document.getElementById('folders-container').addEventListener('click', function(event) {
  const folder = event.target.closest('.folder'); 
  if (folder) {
    const folderId = folder.getAttribute('data-folder-id');
    showFolderTasks(folderId); 
  }
});

// Show tasks based on folder ID
function showFolderTasks(folderId) {
  const taskListContainer = document.getElementById('task-list');
  taskListContainer.innerHTML = ''; 
  const tasks = folderTasks[folderId] || []; 

  tasks.forEach(task => {
    const taskItem = document.createElement('div');
    taskItem.classList.add('dash-list'); 


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

    taskListContainer.appendChild(taskItem); 
  });


  document.getElementById('folder-content').setAttribute('data-current-folder', folderId);

  document.getElementById('folder-section').style.display = 'none';
  document.getElementById('folder-content').style.display = 'block';
}

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

 
    document.getElementById('task-title').value = '';
    document.getElementById('task-description').value = '';
    document.getElementById('task-due-date').value = '';

    
    showFolderTasks(currentFolderId);
  }
});

document.getElementById('back-btn').addEventListener('click', function() {
  document.getElementById('folder-section').style.display = 'block';
  document.getElementById('folder-content').style.display = 'none';
});


  document.getElementById('delete-folder-btn').addEventListener('click', function() {
    alert('Folder deleted');
  });
  