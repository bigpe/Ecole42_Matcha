<link rel="stylesheet" type="text/css" href="/css/profile.css">
<div class="profile">
    <div class="user_pic" id="d_photo">
        <h3>Add your photo</h3>
        <button id="upload_button">Upload</button>
        <div id="images_block"></div>
    </div>
    <div class="f_name" id="dfname">
        <h3>Change first name</h3>
        <form method="POST" action="">
            <input type="text" id="f_name" placeholder="Input your first name">
            <input type="submit" value="Change" onclick="changeFname()">
        </form>
    </div>
    <div class="l_name" id="dlname">
        <h3>Change last name</h3>
        <form method="POST" action="">
            <input type="text" id="l_name" placeholder="Input your last name">
            <input type="submit" value="Change" onclick="changeLname()">
        </form>
    </div>
    <div class="sex" id="dsex">
        <h3>Change sex</h3>
        <form action=""  method="post">
            <select class="sex" name="sex" id="sex">
            <option value="none" hidden="">Сhoose gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            </select>
            <p><input type="submit"  value="Change" onclick="changSex()"></p>
        </form>
    </div>
    <div class="sex_pref" id="dsex_pref">
        <h3>Change sexual preferences</h3>
        <form action=""  method="post">
            <select class="sex_pref" id="sex_pref">
            <option value="none" hidden="">Сhoose sexual preferences</option>
            <option value="Heterosexual">Heterosexual</option>
            <option value="Homosexual">Homosexual</option>
            <option value="Bisexual">Bisexual</option>
            </select>
            <input type="submit" value="Change" onclick="changeSexPref()">
        </form>
    </div>
    <div class="info" id="dinfo">
        <h3>Change info</h3>
        <form method="POST" action="">
            <input type="text" id="info" placeholder="Input your info">
            <input type="submit" value="Change" onclick="changeInfo()">
        </form>
    </div>
    <div class="tags" id="dtags">
        <h3>Change tags</h3>
        <form method="POST" action="">
            <input type="text" id="tags" placeholder="Input your tags">
            <input type="submit" value="Change" onclick="changeTags()">
        </form>
    </div>
</div>

<script src="/js/change_info.js"></script>