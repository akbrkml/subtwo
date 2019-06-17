<!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>
 
<script type="text/javascript">
    function processImage(sourceImageUrl) {
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************
 
        // Replace <Subscription Key> with your valid subscription key.
        var subscriptionKey = "60eb9be950584d3881f27208eb75ceb1";
 
        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
 
        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
 
        // Display the image.
        document.querySelector("#sourceImage").src = sourceImageUrl;
 
        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),
 
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
 
            type: "POST",
 
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })
 
        .done(function(data) {
            // Show formatted JSON on webpage.
            var json = JSON.stringify(data, null, 2);
            var jsonData = JSON.parse(json);
            document.getElementById("response").innerHTML = jsonData.description.captions[0].text;
        })
 
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
    };
</script>
 
<h1>Analyze image:</h1>
Enter the URL to an image to <strong>Analyze image and upload to server</strong>.
<br><br>
Image to analyze:
<form action="index.php?upload_picture" method="POST" enctype="multipart/form-data">
  <input type="file" name="picture" accept="image/*">
  <input type="submit">
</form>
<br><br>
<div id="wrapper" style="width:1020px; display:table;">
    <div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br><br>
        <label id="response"></label>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
</div>

<?php
    require_once 'phpQS.php';
    if (isset($_GET["upload_picture"])) {
        if(isset($_GET["picture"]))
            // $target = 'pictures/'.basename($_FILES['picture']['name']);
            $tempfile = str_replace("\\","/", $_FILES['picture']['tmp_name']);
            $urlSourceImage = uploadPicture($_FILES['picture']['tmp_name'], basename($_FILES['picture']['name'])); ?>
            <script type="text/javascript">
                var url = "<?php echo $urlSourceImage?>";
                processImage(url);
            </script>
	<?php
    }
?> 

</body>
</html>