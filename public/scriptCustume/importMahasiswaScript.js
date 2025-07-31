// Pastikan DOM sudah siap
document.addEventListener("DOMContentLoaded", function () {
    // Ambil semua elemen yang diperlukan dari DOM
    const uploadArea = document.getElementById("uploadArea");
    const fileInput = document.getElementById("fileInput");
    const fileInfo = document.getElementById("fileInfo");
    const fileName = document.getElementById("fileName");
    const removeFileBtn = document.getElementById("removeFileBtn");
    const importBtn = document.getElementById("importBtn");
    const progressWrapper = document.getElementById("progressWrapper");
    const progressBar = document.getElementById("progressBar");
    const successAlert = document.getElementById("successAlert");
    const errorAlert = document.getElementById("errorAlert");
    const errorMessage = document.getElementById("errorMessage");

    function resetAll() {
        fileInput.value = "";
        fileInfo.classList.add("d-none");
        uploadArea.classList.remove("d-none");
        importBtn.disabled = true;
        progressWrapper.classList.add("d-none");
        successAlert.classList.add("d-none");
        errorAlert.classList.add("d-none");
        progressBar.style.width = "0%";
        progressBar.textContent = "0%";
        progressBar.setAttribute("aria-valuenow", "0");
    }

    function handleFile(file) {
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                // Max 5MB
                errorMessage.innerHTML =
                    "<strong>Gagal!</strong> Ukuran file melebihi 5MB.";
                errorAlert.classList.remove("d-none");
                fileInput.value = "";
                return;
            }
            uploadArea.classList.add("d-none");
            fileInfo.classList.remove("d-none");
            fileName.textContent = file.name;
            importBtn.disabled = false;
            successAlert.classList.add("d-none");
            errorAlert.classList.add("d-none");
        }
    }

    if (uploadArea) {
        uploadArea.addEventListener("click", () => fileInput.click());

        fileInput.addEventListener("change", () => {
            handleFile(fileInput.files[0]);
        });

        uploadArea.addEventListener("dragover", (event) => {
            event.preventDefault();
            uploadArea.classList.add("drag-over");
        });

        uploadArea.addEventListener("dragleave", () => {
            uploadArea.classList.remove("drag-over");
        });

        uploadArea.addEventListener("drop", (event) => {
            event.preventDefault();
            uploadArea.classList.remove("drag-over");
            const file = event.dataTransfer.files[0];
            handleFile(file);
        });

        removeFileBtn.addEventListener("click", () => {
            resetAll();
        });

        importBtn.addEventListener("click", () => {
            if (!fileInput.files[0]) return;

            importBtn.disabled = true;
            progressWrapper.classList.remove("d-none");
            successAlert.classList.add("d-none");
            errorAlert.classList.add("d-none");

            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                progressBar.style.width = progress + "%";
                progressBar.textContent = progress + "%";
                progressBar.setAttribute("aria-valuenow", progress);

                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        if (Math.random() > 0.3) {
                            successAlert.classList.remove("d-none");
                            setTimeout(resetAll, 3000);
                        } else {
                            errorMessage.innerHTML =
                                "<strong>Gagal!</strong> Format data dalam file tidak sesuai.";
                            errorAlert.classList.remove("d-none");
                            importBtn.disabled = false;
                        }
                    }, 500);
                }
            }, 200);
        });
    }
});
