<?php
if (strpos($_SERVER['REMOTE_ADDR'], '192.168.0.') !== 0) {
  header('HTTP/1.0 403 Forbidden');
  die('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($_FILES) {
    @mkdir('f');
    chmod("f", 0777);
    $file = array_shift($_FILES);

    if (move_uploaded_file($file['tmp_name'], 'f/' . $file['name'])) {
      die('http://' . $_SERVER['HTTP_HOST'] . '/f/' . $file['name']);
    }
    else {
      die($file['name'] . ': upload failed');
    }
  }
  die('POST failed');
}
?>
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Upload files</title>
  <meta name="description" content="Upload files">
  <meta name="author" content="kenny">

  <meta property="og:title" content="Upload files">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://files.uuu.ru/">
  <meta property="og:description" content="Upload files">
  <!-- meta property="og:image" content="image.png" -->

  <link rel="icon" href="/favicon.ico">
  <!-- link rel="icon" href="/favicon.svg" type="image/svg+xml" -->
  <!-- link rel="apple-touch-icon" href="/apple-touch-icon.png" -->

  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

  <!-- link rel="stylesheet" href="css/styles.css?v=1.0" -->
  <style>
  * {
    padding: 0;
    margin: 0;
  }
  #drop_zone {
    padding-bottom: 20vh;

    display: table-cell;
    vertical-align: middle;
    text-align: center;

    width: 100vw;
    height: 100vh;

    background-color: #cdeeff;
    box-sizing: border-box;
    cursor: pointer;
  }
  textarea {
    background: transparent;
    border: 1px solid gray;
  }
  .over #drop_zone {
    background-color: #ccf4b4;
  }
  textarea {
    margin-top: 10px;

    width: 80vw;
    height: 50vh;
  }
  .helper {
    color: gray;
  }
  p {
    font-family: Lobster;
  }
  .text {
    font-size: 42px;
  }
  </style>

</head>

<body>
  <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);">
    <p class='text'>Drag one or more files to this drop zone ...</p>
    <div class='textarea' style='display: none'>
      <textarea readonly=readonly></textarea>
      <p class="helper">Click to copy</p>
    </div>
  </div>

  <!-- your content here... -->
  <script src="/jquery-3.6.0.min.js"></script>
  <script>
// При обновлении страницы скидывать содержимое поля
$('textarea').val('');

// Инициализировать элемент формы для загрузки файлов
var input = document.createElement('input');
input.type = 'file';
input.multiple = 'multiple';

// Есть загрузка файлов в элемент формы
input.onchange = e => {
  $('.textarea').show();

  var files = input.files;
  for (var i = 0; i < files.length; i++) {
    var file = files[i];
    console.log({file: file});
    uploadFile(file);
  }
}

// Отркыть окно с выбором файлов
$('#drop_zone').on('click', function(e) {
  console.log({e: e, id: e.target.id, name: e.target.tagName});
  if (e.target.id != 'drop_zone' && e.target.tagName != 'P') return;
  input.click();
});

// Копирование ссылок из textarea
$('body').on('click', 'textarea', function() {
  $(this).select();
  document.execCommand('copy');
});

// Сброс ссылок в поле textarea
$('body').on('dblclick', 'textarea', function() {
  $('textarea').val('');
  $('.textarea').hide();
});

function dropHandler(ev) {
  console.log('File(s) dropped');

  $('body').removeClass('over');
  $('.textarea').show();

  // Prevent default behavior (Prevent file from being opened)
  ev.preventDefault();

  if (ev.dataTransfer.items) {
    // Use DataTransferItemList interface to access the file(s)
    for (var i = 0; i < ev.dataTransfer.items.length; i++) {
      // If dropped items aren't files, reject them
      if (ev.dataTransfer.items[i].kind === 'file') {
        var file = ev.dataTransfer.items[i].getAsFile();
        console.log('... file[' + i + '].name = ' + file.name);
        uploadFile(file);
console.log(file);
      }
    }
  } else {
    // Use DataTransfer interface to access the file(s)
    for (var i = 0; i < ev.dataTransfer.files.length; i++) {
      console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i].name);
      uploadFile(ev.dataTransfer.files[i]);
console.log(file);
    }
  }
}

function dragLeaveHandler(ev) {
  console.log('File(s) out of drop zone');

  $('body').removeClass('over');

  // Prevent default behavior (Prevent file from being opened)
  ev.preventDefault();
}

function dragOverHandler(ev) {
  console.log('File(s) in drop zone');

  $('body').addClass('over');

  // Prevent default behavior (Prevent file from being opened)
  ev.preventDefault();
}

// Загрузка конкретного файла
function uploadFile(file) {
  if (file.size > 200 * 1000 * 1000) {
    $('textarea').val($('textarea').val() + file.name + ' (size=' + new Intl.NumberFormat('ru-RU').format(file.size) + ') is too big\n');

    return;
  }

  let formData = new FormData();
  formData.append('file', file);

  fetch('/', {
    method: 'POST',
    body: formData
  })
  //.then((e) => { console.log({ok: e});})
  .then(response => response.text())
  .then((body) => {
    console.log({body: body});
    $('textarea').val($('textarea').val() + body + '\n');
  })
  .catch((e) => { console.log({error: e});});
}

  </script>
</body>
</html>
