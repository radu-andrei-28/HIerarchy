<!DOCTYPE html>
<head>
  
    <link href="~/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/custom.css">
    <script type="text/javascript" src="js/jquery-1.11.4.min.js"></script>
    <script type="text/javascript" src="js/bootstrap3.3.2.min.js"></script>
</head>
<body>
      <div class="container">
            <div id="content">
        <div id="settingsPanel">
            <div class="control-group">
                <label class="control-label">Organization<span class="oblique"> (mandatory)</span></label>
                <div class="controls">
<input class="text-box single-line" id="topOuName" name="topOuName" type="text" value="">                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Section  <span class="oblique"> (mandatory)</span></label>
                <div class="controls">
                    <select id="l0" class="single-line selectSubLevels">
                        <option value="0">0 Sections</option>
                        <option value="1">1 Section</option>
                        <option value="2">2 Sections</option>
                        <option value="3">3 Sections</option>
                        <option value="4">4 Sections</option>
                        <option value="5">5 Sections</option>
                    </select>
                </div>
            </div>
            <div class="settingsItem" id="settingsItem"></div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" id="btnSubmit" onclick="saveHierarchy()" class="btn btn-primary" value="Save" style="float: left;">
                <button id="btnCancel" class="btn btn-default" style="float: left;"><a href="/">Cancel</a></button>
            </div>
        </div>
    </div>
    </div>
</body>  
<script type="text/javascript">
    var lastLevel = [];
       $(document).on("change", ".selectSubLevels", function () {
           $(this).parent().parent().siblings('.settingsItem').empty();
           var selectId = this.id;
           if (this.value !== 0) {
               var newHTML = '';
               for (var i = 0; i < this.value; i++) {
                   newHTML += '<div class="level level_' + selectId + '_' + i + '"><div class="control-group"><label class="control-label">Location name<span class="oblique">(mandatory)</span></label><div class="controls"><input id="OuName" name="OuName" type="text" value=""></div></div><div class="control-group"><label class="control-label">Location sublevels <span class="oblique">(mandatory)</span></label><div class="controls"><select class="single-line selectSubLevels" id="' + selectId + '_' + i + '"><option value="0">0 Sub Level</option><option value="1">1 Sub Level</option><option value="2">2 Sub Level</option><option value="3">3 Sub Level</option><option value="4">4 Sub Level</option><option value="5">5 Sub Level</option></select></div></div><div class="settingsItem"></div></div>';
                   lastLevel.push('level_' + selectId + '_' + i);
               };
               var closestDiv = $(this).parent().parent().siblings('.settingsItem');
               $(closestDiv).append(newHTML);
               return true;
           }
           else {
               $(this).parent().parent().siblings('.settingsItem').empty();
           }
       });

       function Getchild(parent) {
           console.log(parent)
           if (parent !== null) {
               var f = parent.find("input[name*='OuName']:first");
               var hierarchyString;
               var classNames = [];
               var testClass = f.parent().parent().parent().prop('className');
               parent.children('.settingsItem').children('.level').each(function () {
                   classNames.push($(this).prop('className'))
               })
               var ouChildStr = [];
               for (var i = 0; i < classNames.length; i++) {
                   var class2 = classNames[i].substr(classNames[i].indexOf(" ") + 1);
                   ouChildStr.push(Getchild($('.' + class2)));
               }
               hierarchyString = {
                   "Name": f.val(),
                   "Children": ouChildStr
               };
               return hierarchyString;
           }
       }
      
       //save hierarchy
       function saveHierarchy() {
           var secondlevel = $('#settingsItem').children('.level').find('.settingsItem');
           var parent = $('#settingsPanel');//.children('.level');
           console.log(parent);
           var outree = Getchild(parent);

           var postData = JSON.stringify(outree);
           $.ajax({
               url: '@Url.Content("~/Settings/CreateOrganization")',
            data: postData,
            type: "POST",
            contentType: "application/json",
            dataType: 'JSON',
            success: function (hierOrg) {
                for (var i = 0; i < hierOrg.SubOu.length; i++)
                    console.log(hierOrg.SubOu[i]);
            },
            error: function (hierOrg) {
                //if (data.responseText == "NotLoggedIn")
                //    document.getElementById('logoutForm').submit();
                console.log('Fail to return... ');
            }
        });
    }
</script>
</html>




