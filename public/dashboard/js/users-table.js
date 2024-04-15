
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const token = localStorage.getItem('token');

const dashboardContainer = document.getElementById('dashboard-container');
dashboardContainer.classList.add(`h-[${document.documentElement.clientHeight - 50}px]`)
const displayRolesOverlay = document.getElementById('display-roles-overlay');
const assignRolesOverlay = document.getElementById('assign-roles-overlay');
const deleteUserOverlay = document.getElementById('delete-user-overlay');
const editUserOverlay = document.getElementById('edit-user-overlay');
const usersContainer = document.getElementById('users-container');




if(!token) {
    location.href = '/';
}

HTML_RENDERING();

function HTML_RENDERING() {
    fetch('/api/users', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => response.json())
        .then(users => {
            usersContainer.innerHTML = '';
            users.forEach(user => {
                render_user_row_html(user);
            })

            /********************** display and unset roles popup **************************/
            displayRolesPopup();

            /********************** assign role **************************/
            assignRolesPopup();

            /********************** delete user **************************/
            deleteUserPopup();

            /********************** edit user **************************/
            editUserPopup();
        })
}


function render_user_row_html(user) {
    usersContainer.innerHTML += `
    <tr>
        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
            <div class="flex items-center">
                <div>
                    <div class="text-sm leading-5 text-gray-800">#${user.id}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
            <div class="text-sm leading-5 text-blue-900">${user.name}</div>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap border-b text-blue-900 border-gray-500 text-sm leading-5">
            <button userId="${user.id}" class="display-roles px-5 py-2 border-green-500 border text-green-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">Display Roles</button>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap border-b text-blue-900 border-gray-500 text-sm leading-5">
            <button userId="${user.id}" class="assign-roles px-5 py-2 border-purple-500 border text-purple-500 rounded transition duration-300 hover:bg-purple-700 hover:text-white focus:outline-none">Assign role</button>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap border-b text-blue-900 border-gray-500 text-sm leading-5">
            <button userId="${user.id}" class="delete-user px-5 py-2 border-red-500 border text-red-500 rounded transition duration-300 hover:bg-red-700 hover:text-white focus:outline-none">Delete</button>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-500 text-sm leading-5">
            <button userId="${user.id}" class="edit-user px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">View Details</button>
        </td>
    </tr>
    `;
}





/* ****************************************************** */
function uncheck_boxes() {
    const checkboxes = document.querySelectorAll('.checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    })
}

function displayRolesPopup() {
    const displayRoles = document.querySelectorAll('.display-roles');
    for(const btn of displayRoles) {
        btn.addEventListener('click', function(event) {
            const userId = btn.getAttribute('userId');

            getUserRolesNames(userId, event);
        })
    }
}

function getUserRolesNames(userId, event) {
    fetch('/api/users/getUserRolesNames/' + userId, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => response.json())
        .then(data => {
            const userId = data[0]?.pivot.user_id;
            const popup = document.querySelector('.popup');
            uncheck_boxes();
            const checkboxesContainer = document.getElementById('checkboxes-container');
            checkboxesContainer.innerHTML = '';
            data.forEach(role => {
                checkboxesContainer.innerHTML += `
                     <li>
                        <label class="inline-flex items-center">
                            <input roleId="${role.role_id}" type="checkbox" class="checkbox h-5 w-5 text-green-500">
                            <span class="ml-2">${role.name}</span>
                        </label>
                    </li>
                `;
            })

            // display popup
            displayRolesOverlay.classList.remove('hidden')
            dashboardContainer.classList.add('overflow-hidden')

            // remove popup
            const cancel = document.querySelector('.cancel-display-roles-popup');
            cancel.addEventListener('click', function () {
                displayRolesOverlay.classList.add('hidden')
                dashboardContainer.classList.remove('overflow-hidden')
            })

            // remove popup
            event.stopPropagation();
            document.addEventListener('click', function(event) {
                if(!popup.contains(event.target) && !(popup === event.target)) {
                    displayRolesOverlay.classList.add('hidden')
                    dashboardContainer.classList.remove('overflow-hidden')
                }
            })

            // handle checkboxes
            const unsetBtn = document.getElementById('unset-btn');
            unsetBtn.classList.remove('hidden');
            if(userId === undefined) {
                unsetBtn.classList.add('hidden');
                checkboxesContainer.textContent = 'No roles set to that user'
                return;
            }

            unsetRoles(userId, unsetBtn);
        })
}

function unsetRoles(userId, unsetBtn) {
    const rolesIDs = [];
    const checkboxes = document.querySelectorAll('.checkbox');
    for(const checkbox of checkboxes) {
        checkbox.addEventListener('click', function() {
            const roleId = this.getAttribute('roleId');
            if(this.checked === true) {
                rolesIDs.push(roleId)
            }else{
                let index = rolesIDs.indexOf(roleId);
                if (index !== -1) {
                    rolesIDs.splice(index, 1)
                }
            }
        })
    }


    unsetBtn.onclick = unset;
    function unset() {
        console.log(rolesIDs);
        rolesIDs.forEach(roleId => {
            fetch('/api/role/deleteRoleFromUser/' + roleId + '/'+ userId, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(res => res.text())
                .then(data => {
                    console.log(data);
                })
        })
        displayRolesOverlay.classList.add('hidden')
        dashboardContainer.classList.remove('overflow-hidden')
    }
}
/* ****************************************************** */






/* ****************************************************** */
function assignRolesPopup() {
    const assignRoles = document.querySelectorAll('.assign-roles');
    for(const btn of assignRoles) {
        btn.addEventListener('click', function(event) {
            const userId = btn.getAttribute('userId');

            getRolesNames(userId, event);
        })
    }
}

function getRolesNames(userId, event) {
    fetch('/api/role/getRolesNames', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => response.json())
        .then(data => {
            const popup = document.querySelector('.assign-roles-popup');
            uncheck_boxes();
            const checkboxesContainer = document.getElementById('assign-roles-checkboxes-container');
            checkboxesContainer.innerHTML = '';
            data.forEach(role => {
                checkboxesContainer.innerHTML += `
                     <li>
                        <label class="inline-flex items-center">
                            <input roleId="${role.id}" type="checkbox" class="checkbox h-5 w-5 text-green-500">
                            <span class="ml-2">${role.name}</span>
                        </label>
                    </li>
                `;
            })

            // display popup
            assignRolesOverlay.classList.remove('hidden')
            dashboardContainer.classList.add('overflow-hidden')

            // remove popup
            const cancel = document.querySelector('.cancel-assign-roles-popup');
            cancel.addEventListener('click', function () {
                assignRolesOverlay.classList.add('hidden')
                dashboardContainer.classList.remove('overflow-hidden')
            })

            // remove popup
            event.stopPropagation();
            document.addEventListener('click', function(event) {
                if(!popup.contains(event.target) && !(popup === event.target)) {
                    assignRolesOverlay.classList.add('hidden')
                    dashboardContainer.classList.remove('overflow-hidden')
                }
            })

            // handle checkboxes
            const assignBtn = document.getElementById('assign-btn');
            assignBtn.classList.remove('hidden');
            if(data.length === 0) {
                assignBtn.classList.add('hidden');
                checkboxesContainer.textContent = 'There is no roles in Roles table'
                return;
            }

            assignRoles(userId, assignBtn);
        })
}


function assignRoles(userId, assignBtn) {
    const rolesIDs = [];
    const checkboxes = document.querySelectorAll('.checkbox');
    console.log(checkboxes)
    for(const checkbox of checkboxes) {
        checkbox.addEventListener('click', function() {
            const roleId = this.getAttribute('roleId');
            if(this.checked === true) {
                rolesIDs.push(roleId)
            }else{
                let index = rolesIDs.indexOf(roleId);
                if (index !== -1) {
                    rolesIDs.splice(index, 1)
                }
            }
        })
    }


    assignBtn.onclick = assign;
    function assign() {
        console.log(rolesIDs);
        rolesIDs.forEach(roleId => {
            fetch('/api/role/giveRoleToUser/' + roleId + '/'+ userId, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(res => res.text())
                .then(data => {
                    console.log(data);
                })
        })
        assignRolesOverlay.classList.add('hidden')
        dashboardContainer.classList.remove('overflow-hidden')
    }
}
/* ****************************************************** */





/* ****************************************************** */
function deleteUserPopup() {
    const deleteUsers = document.querySelectorAll('.delete-user');
    for(const btn of deleteUsers) {
        btn.addEventListener('click', function(event) {
            const userId = btn.getAttribute('userId');

            const popup = document.querySelector('.delete-user-popup');

            // display popup
            deleteUserOverlay.classList.remove('hidden')
            dashboardContainer.classList.add('overflow-hidden')

            // remove popup
            const cancel = document.querySelector('.cancel-delete-user-popup');
            cancel.addEventListener('click', function () {
                deleteUserOverlay.classList.add('hidden')
                dashboardContainer.classList.remove('overflow-hidden')
            })

            // remove popup
            event.stopPropagation();
            document.addEventListener('click', function(event) {
                if(!popup.contains(event.target) && !(popup === event.target)) {
                    deleteUserOverlay.classList.add('hidden')
                    dashboardContainer.classList.remove('overflow-hidden')
                }
            })

            const deleteBtn = document.getElementById('delete-btn');
            deleteBtn.addEventListener('click', function() {
                fetch('api/users/' + userId, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(res => res.text())
                    .then(data => {
                        console.log(data);
                        deleteUserOverlay.classList.add('hidden')
                        dashboardContainer.classList.remove('overflow-hidden')

                        HTML_RENDERING();
                    })
            })

        })
    }
}
/* ****************************************************** */







/* ****************************************************** */
function editUserPopup() {
    const editUsers = document.querySelectorAll('.edit-user');
    for(const btn of editUsers) {
        btn.addEventListener('click', function(event) {
            const userId = btn.getAttribute('userId');

            getUserInfo(userId, event);
        })
    }
}


function getUserInfo(userId, event) {
    fetch('/api/users/' + userId, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data)

            const popup = document.querySelector('.edit-user-popup');
            const email = document.getElementById('email');
            const name = document.getElementById('name');
            const password = document.getElementById('password');

            email.textContent = data.user.email;
            name.textContent = data.user.name;


            // display popup
            editUserOverlay.classList.remove('hidden')
            dashboardContainer.classList.add('overflow-hidden')

            // remove popup
            const cancel = document.querySelector('.cancel-edit-user-popup');
            cancel.addEventListener('click', function () {
                editUserOverlay.classList.add('hidden')
                dashboardContainer.classList.remove('overflow-hidden')
            })

            // remove popup
            event.stopPropagation();
            document.addEventListener('click', function(event) {
                if(!popup.contains(event.target) && !(popup === event.target)) {
                    editUserOverlay.classList.add('hidden')
                    dashboardContainer.classList.remove('overflow-hidden')
                }
            })

            // const deleteBtn = document.getElementById('edit-btn');
            // deleteBtn.addEventListener('click', function() {
            //     fetch('api/users/' + userId, {
            //         method: 'GET',
            //         headers: {
            //             'Authorization': `Bearer ${token}`,
            //             'X-CSRF-TOKEN': csrfToken
            //         }
            //     })
            //         .then(res => res.text())
            //         .then(data => {
            //             console.log(data);
            //             editUserOverlay.classList.add('hidden')
            //             dashboardContainer.classList.remove('overflow-hidden')
            //
            //             HTML_RENDERING();
            //         })
            // })
        })
}
