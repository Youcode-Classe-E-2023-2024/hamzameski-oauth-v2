const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const loginForm = document.getElementById('login-form');

loginForm.addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/api/login', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        body:formData
    })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                localStorage.setItem('token', data.data.token);
                location.href = '/users-table'
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
})
