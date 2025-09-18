const searchInput = document.querySelector('.search-bar');
const tRows = document.querySelectorAll('.t-row'); 

fetch('../pages/GetData.php')
  .then(response => response.json())
  .then(data => {
    infos = data;
  })
  .catch(error => console.error('Error:', error));

// Search function
searchInput.addEventListener("input", e => {
    const value = e.target.value.toLowerCase();
    
    tRows.forEach((row, index) => {
        const text = Object.values(infos[index]).join('').toLowerCase();
        const isVisible = text.includes(value);
        row.style.display = isVisible ? '' : 'none';
    });
});