window.onload = function() {
    const sidebar = document.querySelector(".sidebar");
    const pageContainer = document.querySelector("#page-container");

    // Pastikan sidebar selalu terbuka
    sidebar.classList.add("open");
    pageContainer.classList.add("shifted"); // Menambahkan class untuk menggeser konten utama
}
