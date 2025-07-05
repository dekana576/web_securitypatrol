const sidebar = document.getElementById('sidebar');
const toggle = document.getElementById('toggleSidebar');

toggle.addEventListener('click', () => {
  sidebar.classList.toggle('active');
});


document.getElementById('cameraInput').addEventListener('change', function (event) {
  const file = event.target.files[0];
  if (file) {
    alert("Gambar berhasil diambil!");
  }
});

