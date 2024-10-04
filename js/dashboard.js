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


// tab 2 folder
document.addEventListener("DOMContentLoaded", function() {
    const folderList = document.getElementById('folder-list');
    const taskList = document.getElementById('task-list');
    const newFolderInput = document.getElementById('new-folder');
    const addFolderBtn = document.getElementById('add-folder-btn');
    const newTaskInput = document.getElementById('new-task');
    const addTaskBtn = document.getElementById('add-task-btn');
    const selectedFolderTitle = document.getElementById('selected-folder-title');
    const taskArea = document.getElementById('task-area');
    let selectedFolder = null;

    // Function to add a new folder
    addFolderBtn.addEventListener('click', function() {
        const folderName = newFolderInput.value.trim();
        if (folderName) {
            const folderItem = document.createElement('li');
            const folderText = document.createElement('span');
            folderText.textContent = folderName;

            // Delete button for folder
            const deleteFolderBtn = document.createElement('button');
            deleteFolderBtn.textContent = 'Delete';
            deleteFolderBtn.classList.add('delete-btn');
            deleteFolderBtn.addEventListener('click', function() {
                folderItem.remove();
                taskArea.style.display = 'none'; 
            });

            folderItem.appendChild(folderText);
            folderItem.appendChild(deleteFolderBtn);

            folderItem.addEventListener('click', function() {
                openFolder(folderName);
            });

            folderList.appendChild(folderItem);
            newFolderInput.value = '';
        }
    });

    // Function to open a folder and display tasks
    function openFolder(folderName) {
        selectedFolder = folderName;
        selectedFolderTitle.textContent = `Tasks in: ${folderName}`;
        taskArea.style.display = 'block';
        taskList.innerHTML = '';  // Clear tasks when opening a new folder
    }

    // Function to add a task to the selected folder
    addTaskBtn.addEventListener('click', function() {
        const taskName = newTaskInput.value.trim();
        if (taskName && selectedFolder) {
            const taskItem = document.createElement('li');
            const taskText = document.createElement('span');
            taskText.textContent = taskName;

            // Complete button for task
            const completeTaskBtn = document.createElement('button');
            completeTaskBtn.textContent = 'Complete';
            completeTaskBtn.classList.add('complete-btn');
            completeTaskBtn.addEventListener('click', function() {
                taskItem.classList.toggle('completed'); // Mark task as completed
            });

            // Delete button for task
            const deleteTaskBtn = document.createElement('button');
            deleteTaskBtn.textContent = 'Delete';
            deleteTaskBtn.classList.add('delete-btn');
            deleteTaskBtn.addEventListener('click', function() {
                taskItem.remove();
            });

            taskItem.appendChild(taskText);
            taskItem.appendChild(completeTaskBtn);
            taskItem.appendChild(deleteTaskBtn);
            taskList.appendChild(taskItem);
            newTaskInput.value = '';
        }
    });
});
