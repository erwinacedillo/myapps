 // JavaScript for image preview
    document.getElementById('photo').onchange = function (evt) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    };
	
	
	