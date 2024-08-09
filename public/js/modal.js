        function openModal() {
            document.getElementById("postModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("postModal").style.display = "none";
        }

        document.getElementById("openModal").addEventListener("click", openModal);
        document.getElementById("closeModal").addEventListener("click", closeModal);
        document.getElementById("closeModalFooter").addEventListener("click", closeModal);

        window.onclick = function(event) {
            if (event.target == document.getElementById("postModal")) {
                closeModal();
            }
        }