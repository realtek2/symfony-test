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
