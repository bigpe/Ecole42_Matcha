
<div class="contentarea" id ="contentarea">
    <div class="effects">
        <form method="post" id='formMask' name='photo' action="" >
        <?php
        for($i=2; $i < count($data['effects']) ; $i++)
        {
            echo "<img src='/images/resources/".$data['effects'][$i]."' height='180' >".PHP_EOL;
            echo "<input name='mask' value=./images/resources/".($data['effects'][$i])." type=radio>";
        }?>
        </form>
    </div>
    <div class="main_block">
        <form method="post" id='form' name='photo' action="" enctype="multipart/form-data">
            <video id="video" width="320" height="240">Video stream not available.</video>
            <input id="userfile" name="userfile" type="file" />
            <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
            <input type="submit" name="file" id="startbutton" value="take photo">
        </form>
    </div>
    <canvas id="canvas" width="0" height="240">
    </canvas>
    <div class="output">
        <img id="photo" alt="The screen capture will appear in this box." src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAYAAABkW7XSAAAEaUlEQVR4Xu3UAQkAMAwDwdW/k5rcYC4ergrCpWR29x5HgACBgMAYrEBLIhIg8AUMlkcgQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEHjr+8DkBQk1VgAAAABJRU5ErkJggg==">
    </div>
    <div class="photo">
        <?php
        for ($i = 0; $i < count($data['photo']); $i++)
        {
            echo "<div class='user_photo'><img src='".$data['photo'][$i]['path']."' height='90'></div>".PHP_EOL;
            if ($i == 4)
                break;
        }
        ?>
    </div>
</div>
<script src="/js/script.js"></script>