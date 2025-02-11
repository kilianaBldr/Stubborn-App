document.addEventListener("DOMContentLoaded", function(){
    const fileInput = document.getElementById("fileInput");
    const imageEdit = document.getElementById("imageEdit");

    if (fileInput) {
        fileInput.addEventListener("change", function(event) {
            var file = event.target.file[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imageEdit.src = e.target.result;
                    imageEdit.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        })
    }
})