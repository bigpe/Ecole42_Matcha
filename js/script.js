(function camshoot() {

    let width = 320;
    let height = 0;
    let streaming = false;
    let video = null;
    let canvas = null;
    let photo = null;
    let startbutton = null;
    let contentarea = null;
    let gallery = null;
    let mergePhoto = null;
    let mask = null;
    let userfile = null;

    function startup() {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        photo = document.getElementById('photo');
        userfile = document.getElementById('userfile');
        startbutton = document.getElementById('startbutton');
        contentarea = document.getElementById('contentarea');
        gallery = document.createElement('div');

        gallery.setAttribute('class', 'gallery');
        contentarea.appendChild(gallery);

        navigator.mediaDevices.getUserMedia({video: true, audio: false})
            .then(function(stream)
            {
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err)
            {
                console.log("An error occurred: " + err);
            });

        video.addEventListener('canplay', function(ev){
            if (!streaming)
            {
                height = video.videoHeight / (video.videoWidth/width);
                if (isNaN(height))
                {
                    height = width / (4/3);
                }

                video.setAttribute('width', width);
                video.setAttribute('height', height);
                canvas.setAttribute('width', width);
                canvas.setAttribute('height', height);
                streaming = true;
            }
        }, false);

        startbutton.addEventListener('click', function(ev){
            takepicture();
                ev.preventDefault();
        }, false);

        clearphoto();

    }

    function clearphoto()
    {
        let context = canvas.getContext('2d');
        context.fillStyle = "#AAA";
        context.fillRect(0, 0, canvas.width, canvas.height);
        let data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);

    }

    function takepicture() {
        mask = document.querySelector("input[name='mask']:checked").value;
        let context = canvas.getContext('2d');
        if (width && height)
        {
            canvas.width = width;
            canvas.height = height;
            context.drawImage(video, 0, 0, width, height);
            let data = canvas.toDataURL('image/png');
            photo.setAttribute('src', data);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", '/index/camshoot', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function olol(){
                if (xhr.readyState === 4)
                    if(xhr.status === 200)
                    {
                        mergePhoto = xhr.responseText;
                        uploadPicture();
                    }
            };
            xhr.send("photo="+ btoa(photo.src)+"&mask="+mask);
        }
        else if (userfile.files !== null)
        {
            alert(userfile.files);
            let formData = new FormData();
            formData.append("myFile", userfile.files,"a.jpg");
            let xhr1 = new XMLHttpRequest();
            xhr1.open("POST", "/index/no_camshoot", true);
            xhr1.send(formData);
        }
        else
            {
            clearphoto();
        }
    }

       function uploadPicture()
       {
           let newForm = document.createElement('form');
           let newImg = document.createElement('img');
           let inputSave = document.createElement('input');
           let inputHidden = document.createElement('input');
           newForm.setAttribute('class', 'mergePhoto');
           newForm.setAttribute('action', './index/save_photo');
           newForm.setAttribute('method', 'POST');
           inputHidden.setAttribute('type', 'hidden');
           inputHidden.setAttribute('name', 'photo');
           inputHidden.setAttribute('value', btoa('data:image/png;base64, ' + mergePhoto));
           newImg.setAttribute("src", 'data:image/png;base64, '+mergePhoto);
           newImg.setAttribute('id', 'mergePhoto');
           inputSave.setAttribute('type', 'submit');
           inputSave.setAttribute('action', './index/save.php');
           inputSave.setAttribute('id', 'buttonSave');
           newForm.appendChild(newImg);
           newForm.appendChild(inputHidden);
           newForm.appendChild(inputSave);
           gallery.appendChild(newForm);
       }

    window.addEventListener('load', startup, false);
})();

