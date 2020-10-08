const companies = document.getElementById('companies');

if (companies) {
  companies.addEventListener('click', e => {
    console.log('sad');
    if (e.target.className === 'btn btn-danger delete-company') {
      if (confirm('Are you sure?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`/company/delete/${id}`, {
          method: 'DELETE'
        }).then(res => window.location.reload());
      }
    }
  });
}

const roles = document.getElementById('roles');

if (roles) {
  roles.addEventListener('click', e => {
    console.log('sad');
    if (e.target.className === 'btn btn-danger delete-role') {
      if (confirm('Are you sure?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`/role/delete/${id}`, {
          method: 'DELETE'
        }).then(res => window.location.reload());
      }
    }
  });
}