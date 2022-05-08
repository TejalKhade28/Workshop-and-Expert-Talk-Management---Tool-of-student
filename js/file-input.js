function showFileName(input, info){
  var input = document.getElementById(input);
  var info = document.getElementById(info);
  if (input){
    input.addEventListener('change', function(event){
      var input = event.srcElement;
      var fileName = input.files[0].name;
      info.textContent = fileName;
    });    
  }
}

showFileName("fileUpload1", "file-upload-filename1");
showFileName("fileUpload2", "file-upload-filename2");
showFileName("fileUpload3", "file-upload-filename3");
showFileName("fileUpload4", "file-upload-filename4");
showFileName("fileUpload5", "file-upload-filename5");
showFileName("fileUpload6", "file-upload-filename6");
showFileName("fileUpload7", "file-upload-filename7");
showFileName("fileUpload8", "file-upload-filename8");